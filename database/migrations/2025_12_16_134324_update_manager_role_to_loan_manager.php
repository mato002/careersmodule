<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'manager' role to 'loan_manager'
        \DB::table('users')
            ->where('role', 'manager')
            ->update(['role' => 'loan_manager']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'loan_manager' back to 'manager'
        \DB::table('users')
            ->where('role', 'loan_manager')
            ->update(['role' => 'manager']);
    }
};
