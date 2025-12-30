<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // machine name / slug, e.g. admin, hr_manager
            $table->string('name'); // human readable label
            $table->boolean('is_protected')->default(false); // built‑in roles that cannot be deleted
            $table->timestamps();
        });

        // Seed the core roles so existing behaviour keeps working
        DB::table('roles')->insert([
            ['key' => 'user',         'name' => 'User',          'is_protected' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'admin',        'name' => 'Administrator', 'is_protected' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hr_manager',   'name' => 'HR Manager',    'is_protected' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'loan_manager', 'name' => 'Loan Manager',  'is_protected' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'editor',       'name' => 'Editor',        'is_protected' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};



