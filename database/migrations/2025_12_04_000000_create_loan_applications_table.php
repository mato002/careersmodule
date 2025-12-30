<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('town')->nullable();
            $table->string('residence')->nullable();
            $table->enum('client_type', ['business', 'employed', 'casual', 'student'])->nullable();
            $table->string('loan_type')->nullable();
            $table->decimal('amount_requested', 12, 2)->nullable();
            $table->string('repayment_period')->nullable();
            $table->text('purpose')->nullable();
            $table->boolean('agreed_to_terms')->default(false);
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};









