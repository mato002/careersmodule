<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'aptitude_failed' to the status enum
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'pending', 
            'sieving_passed', 
            'sieving_rejected',
            'pending_manual_review',
            'aptitude_failed',
            'stage_2_passed',
            'reviewed', 
            'shortlisted', 
            'rejected', 
            'interview_scheduled', 
            'interview_passed', 
            'interview_failed', 
            'second_interview', 
            'written_test', 
            'case_study', 
            'hired'
        ) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'aptitude_failed' from the status enum
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'pending', 
            'sieving_passed', 
            'sieving_rejected',
            'pending_manual_review',
            'stage_2_passed',
            'reviewed', 
            'shortlisted', 
            'rejected', 
            'interview_scheduled', 
            'interview_passed', 
            'interview_failed', 
            'second_interview', 
            'written_test', 
            'case_study', 
            'hired'
        ) DEFAULT 'pending'");
    }
};






