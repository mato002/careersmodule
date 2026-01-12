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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            
            // Template type: offer_letter, contract
            $table->enum('document_type', ['offer_letter', 'contract'])->unique();
            
            // File path
            $table->string('template_path');
            
            // Metadata
            $table->string('name')->nullable(); // Display name
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            
            // Version tracking
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true);
            
            // Who uploaded/updated
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['document_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
