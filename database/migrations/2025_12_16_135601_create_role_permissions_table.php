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
        if (Schema::hasTable('role_permissions')) {
            return;
        }
        
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission_key')->unique(); // e.g., 'dashboard', 'loan_applications'
            $table->string('permission_name'); // e.g., 'Dashboard', 'Loan Applications'
            $table->string('permission_group')->nullable(); // e.g., 'general', 'management'
            $table->json('roles'); // Array of roles that have access: ['admin', 'loan_manager'] - No default for JSON in MySQL
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // Insert default permissions
        $defaultPermissions = [
            ['permission_key' => 'dashboard', 'permission_name' => 'Dashboard', 'permission_group' => 'general', 'roles' => json_encode(['admin', 'hr_manager', 'loan_manager', 'editor']), 'display_order' => 1],
            ['permission_key' => 'profile_settings', 'permission_name' => 'Profile Settings', 'permission_group' => 'general', 'roles' => json_encode(['admin', 'hr_manager', 'loan_manager', 'editor']), 'display_order' => 2],
            ['permission_key' => 'products', 'permission_name' => 'Products', 'permission_group' => 'content', 'roles' => json_encode(['admin', 'hr_manager', 'loan_manager', 'editor']), 'display_order' => 3],
            ['permission_key' => 'content_management', 'permission_name' => 'Content Management', 'permission_group' => 'content', 'roles' => json_encode(['admin', 'hr_manager', 'loan_manager', 'editor']), 'display_order' => 4],
            ['permission_key' => 'contact_messages', 'permission_name' => 'Contact Messages', 'permission_group' => 'communication', 'roles' => json_encode(['admin', 'hr_manager', 'loan_manager', 'editor']), 'display_order' => 5],
            ['permission_key' => 'loan_applications', 'permission_name' => 'Loan Applications', 'permission_group' => 'management', 'roles' => json_encode(['admin', 'loan_manager']), 'display_order' => 6],
            ['permission_key' => 'careers', 'permission_name' => 'Careers', 'permission_group' => 'management', 'roles' => json_encode(['admin', 'hr_manager']), 'display_order' => 7],
            ['permission_key' => 'team_members', 'permission_name' => 'Team Members', 'permission_group' => 'admin', 'roles' => json_encode(['admin']), 'display_order' => 8],
            ['permission_key' => 'branches', 'permission_name' => 'Branches', 'permission_group' => 'admin', 'roles' => json_encode(['admin']), 'display_order' => 9],
            ['permission_key' => 'activity_logs', 'permission_name' => 'Activity Logs', 'permission_group' => 'admin', 'roles' => json_encode(['admin']), 'display_order' => 10],
            ['permission_key' => 'settings', 'permission_name' => 'Settings', 'permission_group' => 'admin', 'roles' => json_encode(['admin']), 'display_order' => 11],
            ['permission_key' => 'user_management', 'permission_name' => 'User Management', 'permission_group' => 'admin', 'roles' => json_encode(['admin']), 'display_order' => 12],
        ];

        foreach ($defaultPermissions as $permission) {
            // Ensure roles is properly formatted as JSON string
            if (is_array($permission['roles'])) {
                $permission['roles'] = json_encode($permission['roles']);
            }
            DB::table('role_permissions')->insert(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
