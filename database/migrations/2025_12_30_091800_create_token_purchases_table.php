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
        Schema::create('token_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date');
            $table->bigInteger('total_tokens'); // Total tokens purchased
            $table->decimal('cost_per_token', 10, 8); // Cost per token (e.g., 0.00003)
            $table->decimal('total_cost', 12, 2); // Total cost of purchase
            $table->string('provider')->default('openai'); // openai, anthropic, custom
            $table->enum('status', ['active', 'exhausted', 'expired'])->default('active');
            $table->timestamp('expires_at')->nullable(); // Optional expiration
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
            
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_purchases');
    }
};

