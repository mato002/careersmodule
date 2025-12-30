<?php

/**
 * Script to create a sample candidate user
 * Run: php create_sample_candidate.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Hash;

echo "Creating sample candidate user...\n\n";

try {
    // Check if user already exists
    $existingUser = User::where('email', 'john.doe@example.com')->first();
    
    if ($existingUser) {
        echo "⚠ User with email 'john.doe@example.com' already exists!\n";
        echo "  Updating existing user to candidate role...\n\n";
        $existingUser->update([
            'role' => 'candidate',
            'is_admin' => false,
        ]);
        $candidate = $existingUser;
    } else {
        // Create candidate user
        $candidate = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
            'role' => 'candidate',
            'is_admin' => false,
        ]);
    }

    echo "✓ Candidate user created/updated successfully!\n";
    echo "  ID: {$candidate->id}\n";
    echo "  Name: {$candidate->name}\n";
    echo "  Email: {$candidate->email}\n";
    echo "  Password: password123\n";
    echo "  Role: {$candidate->role}\n\n";

    // Link any existing applications with this email
    $linkedCount = JobApplication::where('email', $candidate->email)
        ->whereNull('user_id')
        ->update(['user_id' => $candidate->id]);

    if ($linkedCount > 0) {
        echo "✓ Linked {$linkedCount} existing application(s) to this candidate.\n\n";
    } else {
        echo "ℹ No existing applications found with this email to link.\n\n";
    }

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Login Credentials:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  URL: " . url('/login') . "\n";
    echo "  Email: {$candidate->email}\n";
    echo "  Password: password123\n\n";
    echo "Dashboard URL: " . url('/candidate/dashboard') . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\nDone! ✓\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

