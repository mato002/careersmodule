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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('candidate_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->index(['candidate_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['candidate_id', 'created_at']);
            $table->dropForeign(['candidate_id']);
            $table->dropColumn('candidate_id');
        });
    }
};

