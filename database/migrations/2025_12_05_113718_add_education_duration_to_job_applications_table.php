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
            $table->year('education_start_year')->nullable()->after('education_status');
            $table->year('education_end_year')->nullable()->after('education_start_year');
            $table->year('education_expected_completion_year')->nullable()->after('education_end_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'education_start_year',
                'education_end_year',
                'education_expected_completion_year'
            ]);
        });
    }
};
