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
        Schema::create('company_token_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('token_purchase_id')->nullable()->constrained('token_purchases')->onDelete('set null');
            $table->bigInteger('allocated_tokens'); // Tokens allocated to company
            $table->bigInteger('used_tokens')->default(0); // Tokens used so far
            $table->bigInteger('remaining_tokens'); // Calculated: allocated - used
            $table->timestamp('allocated_at');
            $table->timestamp('expires_at')->nullable(); // Optional expiration
            $table->enum('status', ['active', 'exhausted', 'expired'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_token_allocations');
    }
};

