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
        Schema::create('job_sieving_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->unique()->constrained()->onDelete('cascade');
            $table->json('criteria_json');
            $table->integer('auto_pass_threshold')->default(75);
            $table->integer('auto_reject_threshold')->default(35);
            $table->decimal('auto_pass_confidence', 3, 2)->default(0.85);
            $table->decimal('auto_reject_confidence', 3, 2)->default(0.90);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_sieving_criteria');
    }
};

