<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyTokenAllocation;
use App\Models\TokenPurchase;
use App\Models\TokenUsageLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Display token dashboard
     */
    public function index()
    {
        try {
            // Get or create default company (for single-tenant setup)
            $company = Company::first();

            if (!$company) {
                // Create a default company if none exists
                $starterPlan = config('token_plans.plans.starter');
                $company = Company::create([
                    'name' => config('app.name', 'Fortress Lenders'),
                    'slug' => 'default',
                    'email' => config('mail.from.address'),
                    'subscription_plan' => 'starter',
                    'subscription_status' => 'active',
                    'is_active' => true,
                    'ai_enabled' => true,
                    'ai_auto_sieve' => false,
                    'ai_threshold' => 7.0,
                    'token_package_type' => 'prepaid',
                    'token_limit_per_month' => $starterPlan['monthly_tokens'] ?? null,
                    'token_alert_threshold' => 20,
                ]);
            }
        } catch (\Exception $e) {
            // If companies table doesn't exist, show migration message
            return redirect()->route('admin.dashboard')
                ->with('error', 'Token management requires database migrations. Please run: php artisan migrate');
        }

        $balance = $this->tokenService->getBalance($company->id);
        $usageStats = $this->tokenService->getUsageStats($company->id, 'month');
        $plans = config('token_plans.plans', []);
        
        // Get recent usage logs
        $recentLogs = TokenUsageLog::where('company_id', $company->id)
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get allocations
        $allocations = CompanyTokenAllocation::where('company_id', $company->id)
            ->with('tokenPurchase')
            ->orderBy('allocated_at', 'desc')
            ->get();

        return view('admin.tokens.index', compact(
            'company',
            'balance',
            'usageStats',
            'recentLogs',
            'allocations',
            'plans'
        ));
    }

    /**
     * Show usage statistics
     */
    public function usage(Request $request)
    {
        $company = Company::first();
        if (!$company) {
            return redirect()->route('admin.tokens.index');
        }
        $period = $request->get('period', 'month');

        $usageStats = $this->tokenService->getUsageStats($company->id, $period);

        // Get detailed logs for the period
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $logs = TokenUsageLog::where('company_id', $company->id)
            ->whereBetween('created_at', [$startDate, now()])
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.tokens.usage', compact('usageStats', 'logs', 'period'));
    }

    /**
     * Show token purchase management (admin only)
     */
    public function purchases()
    {
        $purchases = TokenPurchase::with('allocations')
            ->orderBy('purchase_date', 'desc')
            ->paginate(20);

        return view('admin.tokens.purchases', compact('purchases'));
    }

    /**
     * Create new token purchase
     */
    public function createPurchase(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'total_tokens' => 'required|integer|min:1',
            'cost_per_token' => 'required|numeric|min:0',
            'provider' => 'required|string|in:openai,anthropic,custom',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $purchase = $this->tokenService->createPurchase($validated);

        return redirect()->route('admin.tokens.purchases')
            ->with('success', "Token purchase of {$purchase->total_tokens} tokens created successfully.");
    }

    /**
     * Allocate tokens to a company
     */
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'token_amount' => 'required|integer|min:1',
            'token_purchase_id' => 'nullable|exists:token_purchases,id',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $allocation = $this->tokenService->allocateTokens(
                $validated['company_id'],
                $validated['token_amount'],
                $validated['token_purchase_id'] ?? null,
                $validated['expires_at'] ? new \DateTime($validated['expires_at']) : null,
                $validated['notes'] ?? null
            );

            return redirect()->back()
                ->with('success', "Successfully allocated {$validated['token_amount']} tokens to company.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Apply a subscription plan to the company and (optionally) allocate tokens
     */
    public function applyPlan(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan_code' => 'required|string',
            'allocate_now' => 'sometimes|boolean',
        ]);

        $company = Company::findOrFail($validated['company_id']);
        $plans = config('token_plans.plans', []);
        $planCode = $validated['plan_code'];

        if (!isset($plans[$planCode])) {
            return back()->withErrors(['error' => 'Invalid plan selected.']);
        }

        $plan = $plans[$planCode];

        // Update company subscription info
        $company->update([
            'subscription_plan' => $planCode,
            'token_limit_per_month' => $plan['monthly_tokens'],
            'token_package_type' => 'prepaid',
        ]);

        $message = "Subscription plan updated to {$plan['name']} ({$plan['monthly_tokens']} tokens / month).";

        // Optionally allocate tokens immediately
        if ($request->boolean('allocate_now')) {
            $amountToAllocate = $plan['monthly_tokens'];
            
            // Try to find a purchase with enough tokens
            // Note: remaining_tokens is a computed accessor, so we need to check it in PHP
            $purchases = TokenPurchase::where('status', 'active')
                ->orderBy('purchase_date', 'asc')
                ->get();
            
            $purchase = null;
            foreach ($purchases as $p) {
                if ($p->remaining_tokens >= $amountToAllocate) {
                    $purchase = $p;
                    break;
                }
            }

            if ($purchase) {
                // Allocate from purchase
                try {
                    $this->tokenService->allocateTokens(
                        $company->id,
                        $amountToAllocate,
                        $purchase->id,
                        null,
                        "Initial allocation for {$plan['name']} plan"
                    );
                    $message .= " Allocated {$amountToAllocate} tokens from purchase #{$purchase->id}.";
                } catch (\Exception $e) {
                    return back()->with('warning', $message . ' Allocation failed: ' . $e->getMessage());
                }
            } else {
                // No purchase with enough tokens - try to allocate what's available or do manual allocation
                $totalAvailable = 0;
                foreach ($purchases as $p) {
                    $totalAvailable += $p->remaining_tokens;
                }
                
                if ($totalAvailable > 0 && $totalAvailable < $amountToAllocate) {
                    // Allocate what's available from all purchases
                    $allocated = 0;
                    // Filter purchases with remaining tokens > 0
                    $purchasesWithTokens = $purchases->filter(function($p) {
                        return $p->remaining_tokens > 0;
                    });
                    
                    foreach ($purchasesWithTokens as $p) {
                        $toAllocate = min($p->remaining_tokens, $amountToAllocate - $allocated);
                        if ($toAllocate > 0) {
                            try {
                                $this->tokenService->allocateTokens(
                                    $company->id,
                                    $toAllocate,
                                    $p->id,
                                    null,
                                    "Partial allocation for {$plan['name']} plan"
                                );
                                $allocated += $toAllocate;
                            } catch (\Exception $e) {
                                // Continue with next purchase
                            }
                        }
                        if ($allocated >= $amountToAllocate) break;
                    }
                    
                    if ($allocated > 0) {
                        $message .= " Allocated {$allocated} tokens (out of {$amountToAllocate} requested) from available purchases. Please create a larger purchase to allocate the remaining " . number_format($amountToAllocate - $allocated) . " tokens.";
                    } else {
                        $message .= " Could not allocate tokens. Available in purchases: " . number_format($totalAvailable) . ". Please create a purchase with at least " . number_format($amountToAllocate) . " tokens, or allocate manually.";
                    }
                } else {
                    // No purchase or not enough - do manual allocation (without purchase)
                    try {
                        $this->tokenService->allocateTokens(
                            $company->id,
                            $amountToAllocate,
                            null, // No purchase - manual allocation
                            null,
                            "Manual allocation for {$plan['name']} plan (no purchase linked)"
                        );
                        $message .= " Allocated {$amountToAllocate} tokens manually (no purchase linked).";
                    } catch (\Exception $e) {
                        $message .= " Could not allocate tokens: " . $e->getMessage() . ". Please create a token purchase or allocate manually.";
                    }
                }
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Get token balance via API
     */
    public function balance(Request $request)
    {
        $company = Company::first(); // Later: get from API key or auth
        
        if (!$company) {
            return response()->json(['error' => 'No company found'], 404);
        }
        
        $balance = $this->tokenService->getBalance($company->id);
        
        return response()->json($balance);
    }

    /**
     * Get usage statistics via API
     */
    public function stats(Request $request)
    {
        $company = Company::first(); // Later: get from API key or auth
        
        if (!$company) {
            return response()->json(['error' => 'No company found'], 404);
        }
        
        $period = $request->get('period', 'month');

        $stats = $this->tokenService->getUsageStats($company->id, $period);

        return response()->json($stats);
    }
}

