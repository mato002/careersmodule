<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Support\Facades\DB;

class FixTokenTracking extends Command
{
    protected $signature = 'tokens:fix-tracking';
    protected $description = 'Fix token tracking by setting company_id on applications and job posts';

    public function handle()
    {
        $this->info('=== Fixing Token Tracking ===');
        $this->newLine();

        // Get or create company
        $company = Company::first();
        if (!$company) {
            $this->error('No company found! Please create a company first.');
            return 1;
        }

        $this->info("Company: {$company->name} (ID: {$company->id})");
        $this->newLine();

        // Fix job posts
        $this->info('1. Fixing job posts...');
        $jobPostsFixed = DB::update("UPDATE job_posts SET company_id = ? WHERE company_id IS NULL", [$company->id]);
        $this->line("   → Fixed {$jobPostsFixed} job posts");

        // Fix applications from job posts
        $this->info('2. Fixing applications from job posts...');
        $appsFixed1 = DB::update("
            UPDATE job_applications ja
            INNER JOIN job_posts jp ON ja.job_post_id = jp.id
            SET ja.company_id = jp.company_id
            WHERE ja.company_id IS NULL 
            AND jp.company_id IS NOT NULL
        ");
        $this->line("   → Fixed {$appsFixed1} applications from job posts");

        // Fix remaining applications
        $this->info('3. Fixing remaining applications...');
        $appsFixed2 = DB::update("
            UPDATE job_applications 
            SET company_id = ?
            WHERE company_id IS NULL
        ", [$company->id]);
        $this->line("   → Fixed {$appsFixed2} applications");

        // Verify
        $total = JobApplication::count();
        $withCompany = JobApplication::whereNotNull('company_id')->count();
        $withoutCompany = $total - $withCompany;

        $this->newLine();
        $this->info('=== Results ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Applications', $total],
                ['With company_id', $withCompany],
                ['Without company_id', $withoutCompany],
            ]
        );

        // Check allocation
        $allocation = $company->activeTokenAllocation();
        if ($allocation) {
            $this->newLine();
            $this->info("✓ Active allocation found:");
            $this->line("   - Remaining tokens: " . number_format($allocation->remaining_tokens));
        } else {
            $this->newLine();
            $this->warn("⚠️  No active allocation found!");
            $this->line("   → Go to Token Management page to allocate tokens");
        }

        // Check usage logs
        $logs = \App\Models\TokenUsageLog::where('company_id', $company->id)->count();
        $this->newLine();
        $this->info("Token usage logs: {$logs}");

        if ($withoutCompany == 0 && $allocation) {
            $this->newLine();
            $this->info('✓ All fixed! Token tracking should work now.');
            $this->line('   → Try re-sieving an application to test.');
        }

        return 0;
    }
}
