<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixJobPostSlugs extends Command
{
    protected $signature = 'jobs:fix-slugs';
    protected $description = 'Ensure all job posts have valid slugs';

    public function handle()
    {
        $jobs = JobPost::all();
        $fixed = 0;

        foreach ($jobs as $job) {
            if (empty($job->slug) || $job->slug !== Str::slug($job->title)) {
                $oldSlug = $job->slug;
                $job->slug = Str::slug($job->title);
                $job->save();
                $this->info("Fixed slug for '{$job->title}': {$oldSlug} -> {$job->slug}");
                $fixed++;
            }
        }

        if ($fixed === 0) {
            $this->info('All job posts already have valid slugs.');
        } else {
            $this->info("Fixed {$fixed} job post slug(s).");
        }

        // Show all jobs and their slugs
        $this->newLine();
        $this->info('Current job posts:');
        foreach ($jobs as $job) {
            $this->line("  - {$job->title} (slug: {$job->slug}, active: " . ($job->is_active ? 'yes' : 'no') . ")");
        }

        return 0;
    }
}






