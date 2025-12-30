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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique(); // For multi-tenant routing
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('api_key')->unique()->nullable(); // For widget integration
            $table->enum('subscription_plan', ['starter', 'professional', 'enterprise'])->default('starter');
            $table->enum('subscription_status', ['active', 'suspended', 'cancelled', 'trial'])->default('trial');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Additional settings
            $table->timestamps();
            
            $table->index('slug');
            $table->index('domain');
            $table->index('api_key');
            $table->index('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

