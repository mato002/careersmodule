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
        Schema::create('ai_sieving_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->unique()->constrained()->onDelete('cascade');
            $table->enum('ai_decision', ['pass', 'reject', 'manual_review'])->default('manual_review');
            $table->decimal('ai_confidence', 3, 2)->default(0.00); // 0.00 to 1.00
            $table->integer('ai_score')->default(0); // 0 to 100
            $table->text('ai_reasoning')->nullable();
            $table->json('ai_strengths')->nullable();
            $table->json('ai_weaknesses')->nullable();
            $table->boolean('human_override')->default(false);
            $table->enum('human_decision', ['pass', 'reject'])->nullable();
            $table->text('human_feedback')->nullable();
            $table->boolean('was_ai_correct')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_sieving_decisions');
    }
};

