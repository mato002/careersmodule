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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // e.g., 'login', 'logout', 'create', 'update', 'delete', 'view'
            $table->string('model_type')->nullable(); // e.g., 'Product', 'ContactMessage', etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected model
            $table->string('description'); // Human-readable description
            $table->text('metadata')->nullable(); // JSON data for additional context
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('route')->nullable(); // Route name or path
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};


