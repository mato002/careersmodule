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
            $table->integer('self_interview_score')->nullable()->after('aptitude_test_completed_at');
            $table->boolean('self_interview_passed')->nullable()->after('self_interview_score');
            $table->timestamp('self_interview_completed_at')->nullable()->after('self_interview_passed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'self_interview_score',
                'self_interview_passed',
                'self_interview_completed_at',
            ]);
        });
    }
};


