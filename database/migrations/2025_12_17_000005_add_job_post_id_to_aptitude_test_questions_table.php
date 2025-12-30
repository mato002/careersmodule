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
        Schema::table('aptitude_test_questions', function (Blueprint $table) {
            $table->foreignId('job_post_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('job_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aptitude_test_questions', function (Blueprint $table) {
            $table->dropForeign(['job_post_id']);
            $table->dropIndex(['job_post_id']);
            $table->dropColumn('job_post_id');
        });
    }
};

