<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if companies table exists
        if (!Schema::hasTable('companies')) {
            return; // Skip if companies table doesn't exist yet
        }

        // Create or find Fortress Lenders company
        $company = DB::table('companies')
            ->where('slug', 'fortress-lenders')
            ->orWhere('name', 'Fortress Lenders')
            ->first();

        if (!$company) {
            $companyId = DB::table('companies')->insertGetId([
                'name' => 'Fortress Lenders',
                'slug' => 'fortress-lenders',
                'email' => 'info@fortresslenders.com',
                'subscription_plan' => 'enterprise',
                'subscription_status' => 'active',
                'is_active' => true,
                'api_key' => Str::random(32),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $companyId = $company->id;
        }

        // Update existing job_posts
        if (Schema::hasTable('job_posts') && Schema::hasColumn('job_posts', 'company_id')) {
            DB::table('job_posts')
                ->whereNull('company_id')
                ->update(['company_id' => $companyId]);
        }

        // Update existing job_applications
        if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'company_id')) {
            DB::table('job_applications')
                ->whereNull('company_id')
                ->update(['company_id' => $companyId]);
        }

        // Update existing aptitude_test_questions
        if (Schema::hasTable('aptitude_test_questions') && Schema::hasColumn('aptitude_test_questions', 'company_id')) {
            DB::table('aptitude_test_questions')
                ->whereNull('company_id')
                ->update(['company_id' => $companyId]);
        }

        // Update existing self_interview_questions
        if (Schema::hasTable('self_interview_questions') && Schema::hasColumn('self_interview_questions', 'company_id')) {
            DB::table('self_interview_questions')
                ->whereNull('company_id')
                ->update(['company_id' => $companyId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Find Fortress Lenders company
        $company = DB::table('companies')
            ->where('slug', 'fortress-lenders')
            ->orWhere('name', 'Fortress Lenders')
            ->first();

        if (!$company) {
            return;
        }

        $companyId = $company->id;

        // Set company_id to null for all records belonging to Fortress Lenders
        if (Schema::hasTable('job_posts') && Schema::hasColumn('job_posts', 'company_id')) {
            DB::table('job_posts')
                ->where('company_id', $companyId)
                ->update(['company_id' => null]);
        }

        if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'company_id')) {
            DB::table('job_applications')
                ->where('company_id', $companyId)
                ->update(['company_id' => null]);
        }

        if (Schema::hasTable('aptitude_test_questions') && Schema::hasColumn('aptitude_test_questions', 'company_id')) {
            DB::table('aptitude_test_questions')
                ->where('company_id', $companyId)
                ->update(['company_id' => null]);
        }

        if (Schema::hasTable('self_interview_questions') && Schema::hasColumn('self_interview_questions', 'company_id')) {
            DB::table('self_interview_questions')
                ->where('company_id', $companyId)
                ->update(['company_id' => null]);
        }
    }
};
