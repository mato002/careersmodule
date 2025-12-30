<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Job-related questions (after personal info)
            $table->text('why_interested')->nullable()->after('email');
            $table->text('why_good_fit')->nullable()->after('why_interested');
            $table->text('career_goals')->nullable()->after('why_good_fit');
            $table->string('salary_expectations')->nullable()->after('career_goals');
            $table->date('availability_date')->nullable()->after('salary_expectations');
            $table->text('relevant_skills')->nullable()->after('availability_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'why_interested',
                'why_good_fit',
                'career_goals',
                'salary_expectations',
                'availability_date',
                'relevant_skills'
            ]);
        });
    }
};
