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
        Schema::create('candidate_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            
            // Type: performance_review, hr_communication, warning
            $table->enum('type', ['performance_review', 'hr_communication', 'warning']);
            
            // Title and content
            $table->string('title');
            $table->text('content');
            
            // For performance reviews
            $table->integer('rating')->nullable(); // 1-5 or 1-10 scale
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            
            // For warnings
            $table->enum('warning_level', ['verbal', 'written', 'final'])->nullable();
            $table->date('warning_date')->nullable();
            
            // Attachments
            $table->json('attachments')->nullable(); // Array of file paths
            
            // Status
            $table->enum('status', ['draft', 'published', 'acknowledged'])->default('published');
            
            // Metadata
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade'); // HR user
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('acknowledgment_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['candidate_id', 'type']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_appraisals');
    }
};
