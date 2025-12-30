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
        Schema::create('company_token_usage_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->date('period'); // Monthly period (YYYY-MM-01)
            $table->bigInteger('total_tokens_used')->default(0);
            $table->bigInteger('cv_parse_tokens')->default(0);
            $table->bigInteger('cv_analyze_tokens')->default(0);
            $table->bigInteger('scoring_tokens')->default(0);
            $table->bigInteger('decision_tokens')->default(0);
            $table->bigInteger('other_tokens')->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->integer('operations_count')->default(0); // Number of operations
            $table->timestamps();
            
            $table->unique(['company_id', 'period']);
            $table->index('period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_token_usage_summary');
    }
};

