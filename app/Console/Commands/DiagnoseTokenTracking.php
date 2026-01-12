<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\TokenUsageLog;
use App\Models\CompanyTokenAllocation;
use App\Services\AIAnalysisService;
use Illuminate\Support\Facades\Log;

class DiagnoseTokenTracking extends Command
{
    protected $signature = 'diagnose:token-tracking {--application-id= : Check specific application}';
    protected $description = 'Diagnose why token tracking is not working';

    public function handle()
    {
        $this->info('=== TOKEN TRACKING DIAGNOSIS ===');
        $this->newLine();

        // Check company
        $company = Company::first();
        if (!$company) {
            $this->error('No company found!');
            return Command::FAILURE;
        }

        $this->info("Company: {$company->name} (ID: {$company->id})");
        $this->newLine();

        // Check allocation
        $this->info('1. Checking Token Allocation...');
        $allocation = $company->activeTokenAllocation();
        if ($allocation) {
            $this->info("   ✓ Active allocation found (ID: {$allocation->id})");
            $this->line("      Status: {$allocation->status}");
            $this->line("      Remaining: " . number_format($allocation->remaining_tokens));
            $this->line("      Used: " . number_format($allocation->used_tokens));
        } else {
            $this->warn("   ⚠️  No active allocation found");
            $this->line("      This is OK - system will create tracking-only allocation");
        }
        $this->newLine();

        // Check usage logs
        $this->info('2. Checking Usage Logs...');
        $logsCount = TokenUsageLog::where('company_id', $company->id)->count();
        $this->line("   Total logs: {$logsCount}");
        
        if ($logsCount > 0) {
            $recentLogs = TokenUsageLog::where('company_id', $company->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $this->line("   Recent logs:");
            foreach ($recentLogs as $log) {
                $this->line("      - Log #{$log->id}: {$log->operation_type}, {$log->tokens_used} tokens, App #{$log->job_application_id}, " . $log->created_at->format('Y-m-d H:i:s'));
            }
        } else {
            $this->warn("   ⚠️  No usage logs found");
        }
        $this->newLine();

        // Check specific application if provided
        $appId = $this->option('application-id');
        if ($appId) {
            $this->info("3. Checking Application #{$appId}...");
            $application = JobApplication::find($appId);
            
            if (!$application) {
                $this->error("   ✗ Application not found");
                return Command::FAILURE;
            }

            $this->line("   Name: {$application->name}");
            $this->line("   Company ID: " . ($application->company_id ?? 'NULL'));
            $this->line("   Job Post Company ID: " . ($application->jobPost?->company_id ?? 'NULL'));
            $this->line("   Has AI Summary: " . (!empty($application->ai_summary) ? 'YES' : 'NO'));
            $this->line("   Has CV Parsed Data: " . ($application->cvParsedData ? 'YES' : 'NO'));
            
            // Check logs for this application
            $appLogs = TokenUsageLog::where('job_application_id', $appId)->count();
            $this->line("   Token Usage Logs: {$appLogs}");
            
            if ($appLogs == 0 && !empty($application->ai_summary)) {
                $this->warn("   ⚠️  Application has AI summary but no token logs!");
                $this->line("      This means AI ran but tokens weren't tracked.");
            }
            $this->newLine();
        }

        // Test token deduction
        $this->info('4. Testing Token Deduction...');
        try {
            $tokenService = app(\App\Services\TokenService::class);
            
            // Try to get or create allocation
            $allocation = $tokenService->getOrCreateAllocation($company->id);
            $this->info("   ✓ Allocation ready (ID: {$allocation->id}, Status: {$allocation->status})");
            
            // Try a test deduction
            $testResult = $tokenService->deductTokens(
                $company->id,
                100, // Test with 100 tokens
                'test',
                ['test' => true],
                null
            );
            
            if ($testResult) {
                $this->info("   ✓ Test deduction successful");
                
                // Check if log was created
                $testLog = TokenUsageLog::where('company_id', $company->id)
                    ->where('operation_type', 'test')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($testLog) {
                    $this->info("   ✓ Test usage log created (ID: {$testLog->id})");
                } else {
                    $this->error("   ✗ Test usage log NOT created!");
                }
            } else {
                $this->error("   ✗ Test deduction failed");
            }
        } catch (\Exception $e) {
            $this->error("   ✗ Test failed: " . $e->getMessage());
            $this->line("      " . $e->getTraceAsString());
        }
        $this->newLine();

        // Check recent Laravel logs
        $this->info('5. Checking Recent Logs...');
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -50); // Last 50 lines
            
            $tokenRelated = [];
            foreach ($recentLines as $line) {
                if (stripos($line, 'token') !== false || 
                    stripos($line, 'deduct') !== false ||
                    stripos($line, 'allocation') !== false) {
                    $tokenRelated[] = trim($line);
                }
            }
            
            if (count($tokenRelated) > 0) {
                $this->line("   Found " . count($tokenRelated) . " token-related log entries:");
                foreach (array_slice($tokenRelated, -10) as $line) {
                    $this->line("      " . substr($line, 0, 100) . '...');
                }
            } else {
                $this->warn("   ⚠️  No token-related log entries found");
                $this->line("      This suggests token deduction is not being called");
            }
        } else {
            $this->warn("   ⚠️  Log file not found");
        }

        $this->newLine();
        $this->info('=== DIAGNOSIS COMPLETE ===');
        
        return Command::SUCCESS;
    }
}




