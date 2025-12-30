<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyTokenAllocation;
use App\Models\CompanyTokenUsageSummary;
use App\Models\TokenPurchase;
use App\Models\TokenUsageLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TokenService
{
    /**
     * Allocate tokens to a company
     */
    public function allocateTokens(
        int $companyId,
        int $tokenAmount,
        ?int $tokenPurchaseId = null,
        ?\DateTime $expiresAt = null,
        ?string $notes = null
    ): CompanyTokenAllocation {
        return DB::transaction(function () use ($companyId, $tokenAmount, $tokenPurchaseId, $expiresAt, $notes) {
            // If token purchase ID is provided, verify it has enough tokens
            if ($tokenPurchaseId) {
                $purchase = TokenPurchase::findOrFail($tokenPurchaseId);
                if ($purchase->remaining_tokens < $tokenAmount) {
                    throw new \Exception("Insufficient tokens in purchase. Available: {$purchase->remaining_tokens}, Required: {$tokenAmount}");
                }
            }

            // Create allocation
            $allocation = CompanyTokenAllocation::create([
                'company_id' => $companyId,
                'token_purchase_id' => $tokenPurchaseId,
                'allocated_tokens' => $tokenAmount,
                'used_tokens' => 0,
                'remaining_tokens' => $tokenAmount,
                'allocated_at' => now(),
                'expires_at' => $expiresAt,
                'status' => 'active',
                'notes' => $notes,
            ]);

            return $allocation;
        });
    }

    /**
     * Check if company has enough tokens
     */
    public function hasEnoughTokens(int $companyId, int $requiredTokens): bool
    {
        $company = Company::findOrFail($companyId);
        return $company->hasEnoughTokens($requiredTokens);
    }

    /**
     * Get active allocation for a company
     */
    public function getActiveAllocation(int $companyId): ?CompanyTokenAllocation
    {
        $company = Company::findOrFail($companyId);
        return $company->activeTokenAllocation();
    }

    /**
     * Deduct tokens after AI operation
     */
    public function deductTokens(
        int $companyId,
        int $tokensUsed,
        string $operationType,
        array $metadata = [],
        ?int $jobApplicationId = null
    ): bool {
        return DB::transaction(function () use ($companyId, $tokensUsed, $operationType, $metadata, $jobApplicationId) {
            // Get active allocation
            $allocation = $this->getActiveAllocation($companyId);

            if (!$allocation) {
                Log::error("No active token allocation found for company {$companyId}");
                return false;
            }

            // Check if enough tokens
            if ($allocation->remaining_tokens < $tokensUsed) {
                Log::warning("Insufficient tokens for company {$companyId}. Required: {$tokensUsed}, Available: {$allocation->remaining_tokens}");
                return false;
            }

            // Get cost per token from allocation's purchase or default
            $costPerToken = $allocation->tokenPurchase?->cost_per_token ?? config('services.openai.cost_per_token', 0.00003);
            $totalCost = $tokensUsed * $costPerToken;

            // Deduct from allocation
            if (!$allocation->deductTokens($tokensUsed)) {
                return false;
            }

            // Log usage
            $usageLog = TokenUsageLog::create([
                'company_id' => $companyId,
                'job_application_id' => $jobApplicationId,
                'company_token_allocation_id' => $allocation->id,
                'operation_type' => $operationType,
                'tokens_used' => $tokensUsed,
                'input_tokens' => $metadata['input_tokens'] ?? 0,
                'output_tokens' => $metadata['output_tokens'] ?? 0,
                'model_used' => $metadata['model'] ?? 'gpt-4',
                'cost_per_token' => $costPerToken,
                'total_cost' => $totalCost,
                'metadata' => $metadata,
            ]);

            // Update monthly summary
            $this->updateMonthlySummary($companyId, $operationType, $tokensUsed, $totalCost);

            // Check if low on tokens and send alert
            $this->checkLowTokenAlert($companyId, $allocation);

            return true;
        });
    }

    /**
     * Update monthly usage summary
     */
    protected function updateMonthlySummary(int $companyId, string $operationType, int $tokens, float $cost): void
    {
        $period = now()->startOfMonth()->format('Y-m-d');

        $summary = CompanyTokenUsageSummary::updateOrCreate(
            [
                'company_id' => $companyId,
                'period' => $period,
            ],
            [
                'total_tokens_used' => 0,
                'cv_parse_tokens' => 0,
                'cv_analyze_tokens' => 0,
                'scoring_tokens' => 0,
                'decision_tokens' => 0,
                'other_tokens' => 0,
                'total_cost' => 0,
                'operations_count' => 0,
            ]
        );

        $summary->incrementUsage($operationType, $tokens, $cost);
    }

    /**
     * Check if company is low on tokens and send alert
     */
    protected function checkLowTokenAlert(int $companyId, CompanyTokenAllocation $allocation): void
    {
        $company = Company::findOrFail($companyId);
        $threshold = $company->token_alert_threshold ?? 20; // Default 20%

        $percentageRemaining = ($allocation->remaining_tokens / $allocation->allocated_tokens) * 100;

        if ($percentageRemaining <= $threshold) {
            // TODO: Send notification (email, in-app, etc.)
            Log::warning("Company {$companyId} is low on tokens. {$allocation->remaining_tokens} remaining ({$percentageRemaining}%)");
            
            // You can dispatch a notification job here
            // Notification::send($company, new LowTokenAlert($allocation));
        }
    }

    /**
     * Get token usage statistics
     */
    public function getUsageStats(int $companyId, string $period = 'month'): array
    {
        $company = Company::findOrFail($companyId);

        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $endDate = now();

        $logs = TokenUsageLog::where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $summary = [
            'total_tokens' => $logs->sum('tokens_used'),
            'total_cost' => $logs->sum('total_cost'),
            'operations_count' => $logs->count(),
            'by_operation' => [
                'cv_parse' => [
                    'tokens' => $logs->where('operation_type', 'cv_parse')->sum('tokens_used'),
                    'cost' => $logs->where('operation_type', 'cv_parse')->sum('total_cost'),
                    'count' => $logs->where('operation_type', 'cv_parse')->count(),
                ],
                'cv_analyze' => [
                    'tokens' => $logs->where('operation_type', 'cv_analyze')->sum('tokens_used'),
                    'cost' => $logs->where('operation_type', 'cv_analyze')->sum('total_cost'),
                    'count' => $logs->where('operation_type', 'cv_analyze')->count(),
                ],
                'scoring' => [
                    'tokens' => $logs->where('operation_type', 'scoring')->sum('tokens_used'),
                    'cost' => $logs->where('operation_type', 'scoring')->sum('total_cost'),
                    'count' => $logs->where('operation_type', 'scoring')->count(),
                ],
                'decision' => [
                    'tokens' => $logs->where('operation_type', 'decision')->sum('tokens_used'),
                    'cost' => $logs->where('operation_type', 'decision')->sum('total_cost'),
                    'count' => $logs->where('operation_type', 'decision')->count(),
                ],
            ],
            'current_balance' => $company->total_remaining_tokens,
            'period' => $period,
        ];

        return $summary;
    }

    /**
     * Estimate tokens needed for operation
     */
    public function estimateTokens(string $operationType, int $dataSize = 0): int
    {
        // Rough estimates based on operation type
        return match($operationType) {
            'cv_parse' => max(3000, (int)($dataSize / 4)), // ~4 chars per token
            'cv_analyze' => max(4000, (int)($dataSize / 3)),
            'scoring' => max(4500, (int)($dataSize / 3)),
            'decision' => max(2300, (int)($dataSize / 5)),
            default => max(2000, (int)($dataSize / 4)),
        };
    }

    /**
     * Create a token purchase record
     */
    public function createPurchase(array $data): TokenPurchase
    {
        return TokenPurchase::create([
            'purchase_date' => $data['purchase_date'] ?? now(),
            'total_tokens' => $data['total_tokens'],
            'cost_per_token' => $data['cost_per_token'],
            'total_cost' => $data['total_tokens'] * $data['cost_per_token'],
            'provider' => $data['provider'] ?? 'openai',
            'status' => 'active',
            'expires_at' => $data['expires_at'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Get company token balance
     */
    public function getBalance(int $companyId): array
    {
        $company = Company::findOrFail($companyId);
        $allocation = $company->activeTokenAllocation();

        if (!$allocation) {
            return [
                'total_allocated' => 0,
                'used' => 0,
                'remaining' => 0,
                'percentage_used' => 0,
                'percentage_remaining' => 0,
                'status' => 'no_allocation',
            ];
        }

        $percentageUsed = ($allocation->used_tokens / $allocation->allocated_tokens) * 100;

        return [
            'total_allocated' => $allocation->allocated_tokens,
            'used' => $allocation->used_tokens,
            'remaining' => $allocation->remaining_tokens,
            'percentage_used' => round($percentageUsed, 2),
            'percentage_remaining' => round(100 - $percentageUsed, 2),
            'status' => $allocation->status,
            'expires_at' => $allocation->expires_at?->format('Y-m-d H:i:s'),
        ];
    }
}

