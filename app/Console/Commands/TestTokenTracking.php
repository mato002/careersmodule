<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\TokenUsageLog;
use App\Models\CvParsedData;
use App\Services\AIAnalysisService;
use App\Services\AISievingService;
use App\Services\TokenService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestTokenTracking extends Command
{
    protected $signature = 'test:token-tracking {--application-id= : Test specific application ID}';
    protected $description = 'Test token tracking by processing a job application and verifying token usage';

    protected TokenService $tokenService;
    protected AIAnalysisService $aiAnalysisService;
    protected AISievingService $sievingService;

    public function __construct()
    {
        parent::__construct();
        $this->tokenService = app(TokenService::class);
        $this->aiAnalysisService = app(AIAnalysisService::class);
        $this->sievingService = app(AISievingService::class);
    }

    public function handle()
    {
        $this->info('=== TOKEN TRACKING TEST ===');
        $this->newLine();

        // Get company
        $company = Company::first();
        if (!$company) {
            $this->error('No company found! Please create a company first.');
            return Command::FAILURE;
        }

        $this->info("Company: {$company->name} (ID: {$company->id})");
        $this->newLine();

        // Check token balance before
        $balanceBefore = $this->tokenService->getBalance($company->id);
        $this->info('📊 Token Balance BEFORE:');
        $this->line("   Remaining: " . number_format($balanceBefore['remaining'] ?? 0));
        $this->line("   Used: " . number_format($balanceBefore['used'] ?? 0));
        $this->line("   Total Allocated: " . number_format($balanceBefore['total_allocated'] ?? 0));
        $this->newLine();

        // Get or create test application
        $applicationId = $this->option('application-id');
        if ($applicationId) {
            $application = JobApplication::find($applicationId);
            if (!$application) {
                $this->error("Application #{$applicationId} not found!");
                return Command::FAILURE;
            }
        } else {
            $application = $this->getOrCreateTestApplication($company);
        }

        $this->info("Testing with Application #{$application->id}");
        $this->line("   Name: {$application->name}");
        $this->line("   Email: {$application->email}");
        $this->line("   Company ID: " . ($application->company_id ?? 'NULL'));
        $this->line("   Job Post: " . ($application->jobPost?->title ?? 'N/A'));
        $this->newLine();

        // Check if company_id is set
        if (!$application->company_id) {
            $this->warn('⚠️  Application missing company_id! Fixing...');
            if ($application->jobPost && $application->jobPost->company_id) {
                $application->update(['company_id' => $application->jobPost->company_id]);
                $this->info("   ✓ Set company_id to {$application->company_id}");
            } else {
                $application->update(['company_id' => $company->id]);
                $this->info("   ✓ Set company_id to {$company->id}");
            }
            $application->refresh();
        }

        // Count usage logs before
        $logsBefore = TokenUsageLog::where('company_id', $company->id)->count();
        $this->info("Usage logs before: {$logsBefore}");
        $this->newLine();

        // Test 1: CV Analysis
        $this->info('🧪 Test 1: Running CV Analysis...');
        try {
            $analysis = $this->aiAnalysisService->analyzeCv($application);
            if (!empty($analysis)) {
                $this->info('   ✓ CV Analysis completed');
                $this->line('   Summary: ' . substr($analysis['summary'] ?? 'N/A', 0, 100) . '...');
            } else {
                $this->warn('   ⚠️  CV Analysis returned empty result');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ CV Analysis failed: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 2: Application Analysis
        $this->info('🧪 Test 2: Running Application Analysis...');
        try {
            $appAnalysis = $this->aiAnalysisService->analyzeApplication($application);
            if (!empty($appAnalysis)) {
                $this->info('   ✓ Application Analysis completed');
                $this->line('   Match Score: ' . ($appAnalysis['match_score'] ?? 'N/A'));
            } else {
                $this->warn('   ⚠️  Application Analysis returned empty result');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Application Analysis failed: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 3: AI Sieving
        $this->info('🧪 Test 3: Running AI Sieving...');
        try {
            $decision = $this->sievingService->evaluate($application);
            if ($decision) {
                $this->info('   ✓ AI Sieving completed');
                $this->line('   Decision: ' . strtoupper($decision->ai_decision ?? 'N/A'));
                $this->line('   Score: ' . ($decision->ai_score ?? 'N/A'));
            } else {
                $this->warn('   ⚠️  AI Sieving returned no decision');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ AI Sieving failed: ' . $e->getMessage());
        }
        $this->newLine();

        // Wait a moment for logs to be written
        sleep(1);

        // Check token balance after
        $balanceAfter = $this->tokenService->getBalance($company->id);
        $this->info('📊 Token Balance AFTER:');
        $this->line("   Remaining: " . number_format($balanceAfter['remaining'] ?? 0));
        $this->line("   Used: " . number_format($balanceAfter['used'] ?? 0));
        $this->line("   Total Allocated: " . number_format($balanceAfter['total_allocated'] ?? 0));
        $this->newLine();

        // Calculate tokens used
        $tokensUsed = ($balanceBefore['remaining'] ?? 0) - ($balanceAfter['remaining'] ?? 0);
        if ($tokensUsed > 0) {
            $this->info("✅ Tokens Used: " . number_format($tokensUsed));
        } else {
            $this->warn("⚠️  No tokens were deducted!");
        }
        $this->newLine();

        // Count usage logs after
        $logsAfter = TokenUsageLog::where('company_id', $company->id)->count();
        $newLogs = $logsAfter - $logsBefore;
        $this->info("Usage logs after: {$logsAfter} (+{$newLogs} new logs)");
        $this->newLine();

        // Show recent usage logs
        if ($newLogs > 0) {
            $this->info('📋 Recent Usage Logs:');
            $recentLogs = TokenUsageLog::where('company_id', $company->id)
                ->where('job_application_id', $application->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $tableData = [];
            foreach ($recentLogs as $log) {
                $tableData[] = [
                    'ID' => $log->id,
                    'Operation' => $log->operation_type,
                    'Tokens' => number_format($log->tokens_used),
                    'Input' => number_format($log->input_tokens),
                    'Output' => number_format($log->output_tokens),
                    'Cost' => '$' . number_format($log->total_cost, 4),
                    'Time' => $log->created_at->format('H:i:s'),
                ];
            }

            $this->table(
                ['ID', 'Operation', 'Tokens', 'Input', 'Output', 'Cost', 'Time'],
                $tableData
            );
        } else {
            $this->warn('⚠️  No new usage logs were created!');
            $this->line('   This could mean:');
            $this->line('   1. Token deduction failed (check logs)');
            $this->line('   2. No active token allocation');
            $this->line('   3. Insufficient tokens');
        }

        // Final summary
        $this->newLine();
        if ($tokensUsed > 0 && $newLogs > 0) {
            $this->info('✅ TEST PASSED: Token tracking is working correctly!');
            $this->line("   - {$newLogs} usage log(s) created");
            $this->line("   - " . number_format($tokensUsed) . " tokens deducted");
        } else {
            $this->error('❌ TEST FAILED: Token tracking is not working!');
            $this->line('   Check the logs above for errors.');
            $this->line('   Run: tail -f storage/logs/laravel.log | grep -i token');
        }

        return Command::SUCCESS;
    }

    protected function getOrCreateTestApplication(Company $company): JobApplication
    {
        // Try to find an existing application with a CV
        $application = JobApplication::where('company_id', $company->id)
            ->whereNotNull('cv_path')
            ->first();

        if ($application) {
            $this->info("Using existing application #{$application->id}");
            return $application;
        }

        // Find or create a job post
        $jobPost = JobPost::where('company_id', $company->id)
            ->where('is_active', true)
            ->first();

        if (!$jobPost) {
            $this->info('Creating test job post...');
            $jobPost = JobPost::create([
                'title' => 'Test Software Developer',
                'slug' => 'test-software-developer-' . time(),
                'description' => 'Test job post for token tracking verification',
                'requirements' => 'PHP, Laravel, JavaScript, MySQL',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'company_id' => $company->id,
                'is_active' => true,
            ]);
            $this->info("   ✓ Created job post #{$jobPost->id}");
        }

        // Create a test application
        $this->info('Creating test application...');
        $application = JobApplication::create([
            'job_post_id' => $jobPost->id,
            'company_id' => $company->id,
            'name' => 'Test Candidate ' . time(),
            'email' => 'test' . time() . '@example.com',
            'phone' => '+1234567890',
            'education_level' => 'Bachelor\'s Degree',
            'area_of_study' => 'Computer Science',
            'current_job_title' => 'Software Developer',
            'current_company' => 'Test Company',
            'relevant_skills' => 'PHP, Laravel, JavaScript, MySQL',
            'status' => 'pending',
        ]);

        // Create a simple CV parsed data (simulating CV upload)
        $this->info('Creating test CV parsed data...');
        CvParsedData::create([
            'job_application_id' => $application->id,
            'raw_text' => "TEST CV FOR TOKEN TRACKING\n\nName: Test Candidate\nEmail: test@example.com\n\nEducation:\nBachelor's Degree in Computer Science\n\nExperience:\nSoftware Developer at Test Company\n- Developed web applications using PHP and Laravel\n- Worked with MySQL databases\n- Created RESTful APIs\n\nSkills:\n- PHP\n- Laravel\n- JavaScript\n- MySQL\n- Git",
            'parsing_confidence' => 0.95,
            'extracted_data' => [
                'name' => 'Test Candidate',
                'email' => 'test@example.com',
                'education' => "Bachelor's Degree in Computer Science",
                'experience' => 'Software Developer at Test Company',
                'skills' => ['PHP', 'Laravel', 'JavaScript', 'MySQL', 'Git'],
            ],
        ]);

        $this->info("   ✓ Created application #{$application->id}");
        return $application;
    }
}

