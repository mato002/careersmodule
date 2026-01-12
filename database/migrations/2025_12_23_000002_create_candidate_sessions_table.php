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
        Schema::create('candidate_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, tablet, desktop
            $table->string('browser')->nullable();
            $table->string('platform')->nullable(); // Windows, macOS, Linux, iOS, Android
            $table->string('location')->nullable();
            $table->timestamp('last_activity');
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->index(['candidate_id', 'last_activity']);
            $table->index('session_id');
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_sessions');
    }
};

