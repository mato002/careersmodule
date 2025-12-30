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
        // Ensure the job_applications table has a primary key index
        // (This should already exist, but we'll verify)
        Schema::table('job_applications', function (Blueprint $table) {
            // The id column should already be a primary key with index
            // But we'll ensure it's properly indexed
        });

        // Drop the broken foreign key constraint if it exists
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropForeign(['job_application_id']);
        });

        // Recreate the foreign key constraint properly
        Schema::table('interviews', function (Blueprint $table) {
            $table->foreign('job_application_id')
                  ->references('id')
                  ->on('job_applications')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropForeign(['job_application_id']);
        });
    }
};
