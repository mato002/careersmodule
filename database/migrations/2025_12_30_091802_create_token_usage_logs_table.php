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
        Schema::create('token_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('job_application_id')->nullable()->constrained('job_applications')->onDelete('set null');
            $table->foreignId('company_token_allocation_id')->nullable()->constrained('company_token_allocations')->onDelete('set null');
            $table->enum('operation_type', ['cv_parse', 'cv_analyze', 'scoring', 'decision', 'other']);
            $table->integer('tokens_used'); // Total tokens used
            $table->integer('input_tokens')->default(0); // Input tokens
            $table->integer('output_tokens')->default(0); // Output tokens
            $table->string('model_used', 50)->default('gpt-4'); // Model used (gpt-4, gpt-4-turbo, etc.)
            $table->decimal('cost_per_token', 10, 8); // Cost per token at time of use
            $table->decimal('total_cost', 12, 2); // Total cost: tokens_used * cost_per_token
            $table->json('metadata')->nullable(); // Additional info (prompt length, response length, etc.)
            $table->timestamps();
            
            $table->index(['company_id', 'created_at']);
            $table->index('operation_type');
            $table->index('job_application_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_usage_logs');
    }
};

