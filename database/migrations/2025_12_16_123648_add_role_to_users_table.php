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
        // Check if column already exists before adding it
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('is_admin');
            });
        }

        // Migrate existing is_admin to role (only if role column exists and has null values)
        if (Schema::hasColumn('users', 'role')) {
            \DB::table('users')
                ->where('is_admin', true)
                ->where(function($query) {
                    $query->whereNull('role')->orWhere('role', '');
                })
                ->update(['role' => 'admin']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
