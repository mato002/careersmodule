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
        Schema::create('cv_parsed_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->unique()->constrained('job_applications')->onDelete('cascade');
            
            // Personal Information
            $table->string('parsed_name')->nullable();
            $table->string('parsed_email')->nullable();
            $table->string('parsed_phone')->nullable();
            $table->text('parsed_address')->nullable();
            
            // Work Experience (JSON)
            $table->json('parsed_work_experience')->nullable(); // [{company, role, start_date, end_date, description, location}]
            
            // Education (JSON)
            $table->json('parsed_education')->nullable(); // [{institution, degree, field, start_date, end_date, grade, status}]
            
            // Skills (JSON)
            $table->json('parsed_skills')->nullable(); // {technical: [], soft: []}
            
            // Certifications (JSON)
            $table->json('parsed_certifications')->nullable(); // [{name, issuer, date, expiry}]
            
            // Languages (JSON)
            $table->json('parsed_languages')->nullable(); // [{language, proficiency}]
            
            // Projects/Portfolio (JSON)
            $table->json('parsed_projects')->nullable(); // [{name, description, url, technologies}]
            
            // Raw extracted text
            $table->longText('raw_text')->nullable();
            
            // Parsing metadata
            $table->string('parser_version')->nullable();
            $table->decimal('parsing_confidence', 3, 2)->default(0.00);
            $table->timestamp('parsed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_parsed_data');
    }
};


