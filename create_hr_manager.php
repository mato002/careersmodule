<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['email' => 'hr@fortresslenders.com'],
    [
        'name' => 'HR Manager',
        'password' => Hash::make('@Kenya1234'),
        'email_verified_at' => now(),
        'is_admin' => false,
        'is_banned' => false,
        'role' => 'hr_manager',
    ]
);

echo "HR Manager user created successfully!\n";
echo "Email: hr@fortresslenders.com\n";
echo "Password: @Kenya1234\n";
echo "Role: hr_manager\n";

