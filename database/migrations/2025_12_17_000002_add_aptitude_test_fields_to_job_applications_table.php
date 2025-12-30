<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->integer('aptitude_test_score')->nullable()->after('status');
            $table->boolean('aptitude_test_passed')->nullable()->after('aptitude_test_score');
            $table->timestamp('aptitude_test_completed_at')->nullable()->after('aptitude_test_passed');
        });
        
        // Use raw SQL to modify enum (Laravel doesn't support enum changes directly)
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'aptitude_test_score',
                'aptitude_test_passed',
                'aptitude_test_completed_at'
            ]);
        });
        
        // Revert status enum
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'pending', 
            'sieving_passed', 
            'sieving_rejected',
            'pending_manual_review',
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

