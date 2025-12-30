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
        Schema::create('self_interview_questions', function (Blueprint $table) {
            $table->id();
            // Optional link to a specific job post. Null = global question.
            $table->foreignId('job_post_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('question');
            // Store answer options as JSON (e.g. a,b,c,d) similar to aptitude test
            $table->json('options')->nullable();
            // Optional correct answer key (e.g. 'a', 'b', 'c', 'd') for auto‑marking
            $table->string('correct_answer')->nullable();
            $table->integer('points')->default(4);
            $table->text('explanation')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_interview_questions');
    }
};


