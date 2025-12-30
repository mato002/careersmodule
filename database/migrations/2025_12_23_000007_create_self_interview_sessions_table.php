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
        Schema::create('self_interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->unique()->constrained()->onDelete('cascade');
            // Store question_id => answer mapping
            $table->json('answers')->nullable();
            $table->integer('total_score')->default(0);
            $table->integer('total_possible_score')->default(0);
            $table->boolean('is_passed')->default(false);
            $table->integer('pass_threshold')->default(70);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_taken_seconds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_interview_sessions');
    }
};


