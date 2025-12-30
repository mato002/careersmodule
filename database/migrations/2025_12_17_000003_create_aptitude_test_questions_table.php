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
        Schema::create('aptitude_test_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('section', ['numerical', 'logical', 'verbal', 'scenario'])->index();
            $table->text('question');
            $table->json('options'); // Array of answer options
            $table->string('correct_answer'); // The correct answer key (e.g., 'a', 'b', 'c', 'd')
            $table->integer('points')->default(4);
            $table->text('explanation')->nullable(); // Explanation for the answer
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aptitude_test_questions');
    }
};

