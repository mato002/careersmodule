<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained()->onDelete('cascade');
            
            // Page 1: Personal & Education
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('education_level')->nullable();
            $table->string('area_of_study')->nullable();
            $table->string('institution')->nullable();
            $table->string('education_status')->nullable();
            $table->text('other_achievements')->nullable();
            
            // Work Experience
            $table->text('work_experience')->nullable(); // JSON field for multiple experiences
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->boolean('currently_working')->default(false);
            $table->text('duties_and_responsibilities')->nullable();
            $table->text('other_experiences')->nullable();
            
            // AI Analysis
            $table->text('ai_summary')->nullable();
            $table->text('ai_details')->nullable();
            
            // Page 2: Support Details
            $table->text('support_details')->nullable();
            
            // Page 3: References & Agreement
            $table->text('referrers')->nullable(); // JSON field for multiple referrers
            $table->string('notice_period')->nullable();
            $table->boolean('agreement_accepted')->default(false);
            
            // CV Upload
            $table->string('cv_path')->nullable();
            
            // Application Message
            $table->text('application_message')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected', 'interview_scheduled', 'interview_passed', 'interview_failed', 'second_interview', 'written_test', 'case_study', 'hired'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};


