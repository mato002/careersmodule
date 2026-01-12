<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the enum
        DB::statement("ALTER TABLE company_token_allocations MODIFY COLUMN status ENUM('active', 'exhausted', 'expired', 'tracking_only') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE company_token_allocations MODIFY COLUMN status ENUM('active', 'exhausted', 'expired') DEFAULT 'active'");
    }
};




