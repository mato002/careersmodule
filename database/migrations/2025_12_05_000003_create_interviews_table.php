<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->enum('interview_type', ['first', 'second', 'written_test', 'case_study'])->default('first');
            $table->dateTime('scheduled_at')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->enum('result', ['pending', 'pass', 'fail'])->default('pending');
            $table->text('feedback')->nullable();
            $table->string('test_submission_email')->nullable();
            $table->string('test_document_path')->nullable();
            $table->foreignId('conducted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};


