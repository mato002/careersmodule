<?php

/**
 * Script to restore/create admin user
 * Run this if you've lost your users
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Restoring admin user...\n\n";

// Get admin credentials from environment or use defaults
$adminEmail = env('ADMIN_EMAIL', 'admin@fortresslenders.com');
$adminName = env('ADMIN_NAME', 'Fortress Admin');
$adminPassword = env('ADMIN_PASSWORD', 'ChangeMe123!');

// Create or update admin user
$admin = User::updateOrCreate(
    ['email' => $adminEmail],
    [
        'name' => $adminName,
        'password' => Hash::make($adminPassword),
        'email_verified_at' => now(),
        'is_admin' => true,
        'role' => 'admin',
        'is_banned' => false,
    ]
);

echo "✓ Admin user created/updated successfully!\n\n";
echo "Login Details:\n";
echo "Email: {$adminEmail}\n";
echo "Password: {$adminPassword}\n";
echo "Role: Admin\n\n";
echo "You can now login at: http://127.0.0.1:8000/login\n";

