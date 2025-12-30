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
            // Drop the old foreign key and column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Add new candidate_id column
            $table->foreignId('candidate_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Drop candidate_id
            $table->dropForeign(['candidate_id']);
            $table->dropColumn('candidate_id');
            
            // Restore user_id
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }
};

