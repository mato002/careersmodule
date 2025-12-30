<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$password = '@Kenya1234';
$hash = Hash::make($password);

echo "Password Hash for '@Kenya1234':\n";
echo $hash . "\n\n";

// Update admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@fortresslenders.com'],
    [
        'name' => 'Fortress Admin',
        'password' => $hash,
        'email_verified_at' => now(),
        'is_admin' => true,
        'role' => 'admin',
        'is_banned' => false,
    ]
);

echo "✓ Admin user updated with new password!\n";
echo "Email: admin@fortresslenders.com\n";
echo "Password: @Kenya1234\n\n";

// Also create HR Manager with same password
$hr = User::updateOrCreate(
    ['email' => 'hr@fortresslenders.com'],
    [
        'name' => 'HR Manager',
        'password' => $hash,
        'email_verified_at' => now(),
        'is_admin' => false,
        'role' => 'hr_manager',
        'is_banned' => false,
    ]
);

echo "✓ HR Manager user created/updated!\n";
echo "Email: hr@fortresslenders.com\n";
echo "Password: @Kenya1234\n\n";

echo "SQL Query:\n";
echo "UPDATE `users` SET `password` = '{$hash}' WHERE `email` = 'admin@fortresslenders.com';\n";

