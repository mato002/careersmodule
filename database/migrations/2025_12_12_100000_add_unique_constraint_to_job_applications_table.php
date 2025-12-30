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
            // Add unique constraint to prevent duplicate applications
            // Same email + phone + job_post_id combination cannot be submitted twice
            $table->unique(['email', 'phone', 'job_post_id'], 'unique_application_per_job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropUnique('unique_application_per_job');
        });
    }
};

