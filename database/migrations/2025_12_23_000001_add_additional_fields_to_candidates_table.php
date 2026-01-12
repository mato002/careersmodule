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
        Schema::table('candidates', function (Blueprint $table) {
            // Contact Information
            $table->string('phone')->nullable()->after('email');
            $table->string('phone_country_code', 5)->nullable()->after('phone');
            
            // Location Information
            $table->text('address')->nullable()->after('phone_country_code');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('country');
            
            // Profile Information
            $table->date('date_of_birth')->nullable()->after('postal_code');
            $table->string('profile_photo_path')->nullable()->after('date_of_birth');
            $table->string('preferred_language', 10)->default('en')->after('profile_photo_path');
            
            // Account Status & Security
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active')->after('preferred_language');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            
            // Soft Deletes
            $table->softDeletes()->after('updated_at');
            
            // Indexes for performance
            $table->index('status');
            $table->index('phone');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'created_at']);
            
            $table->dropColumn([
                'phone',
                'phone_country_code',
                'address',
                'city',
                'state',
                'country',
                'postal_code',
                'date_of_birth',
                'profile_photo_path',
                'preferred_language',
                'status',
                'last_login_at',
                'last_login_ip',
                'deleted_at',
            ]);
        });
    }
};

