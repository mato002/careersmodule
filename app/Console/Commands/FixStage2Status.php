<?php

namespace App\Console\Commands;

use App\Models\JobApplication;
use App\Models\JobApplicationStatusHistory;
use Illuminate\Console\Command;

class FixStage2Status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:fix-stage2-status 
                            {--dry-run : Run without making changes}
                            {--force : Force update even if status is already correct}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update job application statuses to stage_2_passed if both aptitude test and self-interview are passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('Finding applications with both aptitude test and self-interview passed...');

        // Find applications where both tests are passed but status is not stage_2_passed
        $query = JobApplication::where('aptitude_test_passed', true)
            ->where('self_interview_passed', true)
            ->whereNotNull('aptitude_test_completed_at')
            ->whereNotNull('self_interview_completed_at');

        if (!$force) {
            $query->where('status', '!=', 'stage_2_passed');
        }

        $applications = $query->get();

        if ($applications->isEmpty()) {
            $this->info('No applications found that need updating.');
            return 0;
        }

        $this->info("Found {$applications->count()} application(s) that need updating.");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made.');
            $this->newLine();
        }

        $updated = 0;
        $skipped = 0;

        foreach ($applications as $application) {
            $previousStatus = $application->status;

            if ($previousStatus === 'stage_2_passed' && !$force) {
                $skipped++;
                continue;
            }

            $this->line("Application ID: {$application->id}");
            $this->line("  Candidate: {$application->name} ({$application->email})");
            $this->line("  Current Status: {$previousStatus}");
            $this->line("  Aptitude Test: Passed ({$application->aptitude_test_score}%)");
            $this->line("  Self Interview: Passed ({$application->self_interview_score}%)");
            $this->line("  → Will update to: stage_2_passed");

            if (!$dryRun) {
                $application->update(['status' => 'stage_2_passed']);

                // Record status change in history
                JobApplicationStatusHistory::create([
                    'job_application_id' => $application->id,
                    'previous_status' => $previousStatus,
                    'new_status' => 'stage_2_passed',
                    'changed_by' => null,
                    'source' => 'fix_stage2_status_command',
                    'notes' => 'Automated fix: Both aptitude test and self-interview are passed',
                ]);

                $updated++;
            } else {
                $updated++;
            }

            $this->newLine();
        }

        if ($dryRun) {
            $this->info("DRY RUN: Would update {$updated} application(s).");
        } else {
            $this->info("Successfully updated {$updated} application(s).");
            if ($skipped > 0) {
                $this->comment("Skipped {$skipped} application(s) (already have correct status).");
            }
        }

        return 0;
    }
}

