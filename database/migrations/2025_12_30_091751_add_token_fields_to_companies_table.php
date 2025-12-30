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
        Schema::table('companies', function (Blueprint $table) {
            $table->enum('token_package_type', ['prepaid', 'postpaid', 'unlimited'])->default('prepaid')->after('subscription_status');
            $table->bigInteger('token_limit_per_month')->nullable()->after('token_package_type');
            $table->integer('token_alert_threshold')->default(20)->after('token_limit_per_month'); // Alert when 20% remaining
            $table->boolean('ai_enabled')->default(true)->after('token_alert_threshold');
            $table->boolean('ai_auto_sieve')->default(false)->after('ai_enabled');
            $table->decimal('ai_threshold', 3, 1)->default(7.0)->after('ai_auto_sieve'); // Minimum score to pass
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'token_package_type',
                'token_limit_per_month',
                'token_alert_threshold',
                'ai_enabled',
                'ai_auto_sieve',
                'ai_threshold',
            ]);
        });
    }
};

