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
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            
            // Document type: offer_letter, contract, id, kra, sha
            $table->enum('document_type', ['offer_letter', 'contract', 'id', 'kra', 'sha']);
            
            // For offer letter and contract: HR uploads template, candidate uploads filled version
            $table->enum('uploaded_by', ['hr', 'candidate'])->default('candidate');
            
            // File paths
            $table->string('template_path')->nullable(); // HR uploaded template (for offer letter and contract)
            $table->string('filled_path')->nullable(); // Candidate uploaded filled version
            
            // Status
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            
            // Metadata
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // HR user who uploaded
            
            $table->timestamps();
            
            // Indexes
            $table->index(['candidate_id', 'document_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};
