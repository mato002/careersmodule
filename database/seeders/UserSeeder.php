<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin User
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            // HR Manager
            [
                'name' => 'HR Manager',
                'email' => 'hr@example.com',
                'password' => Hash::make('password123'),
                'role' => 'hr_manager',
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            // Editor
            [
                'name' => 'Content Editor',
                'email' => 'editor@example.com',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            // Regular User
            [
                'name' => 'John Doe',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            // Candidate User
            [
                'name' => 'Jane Candidate',
                'email' => 'candidate@example.com',
                'password' => Hash::make('password123'),
                'role' => 'candidate',
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            // Another HR Manager
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.hr@example.com',
                'password' => Hash::make('password123'),
                'role' => 'hr_manager',
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            // Another Admin
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('HR Manager:');
        $this->command->info('  Email: hr@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Editor:');
        $this->command->info('  Email: editor@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('User:');
        $this->command->info('  Email: user@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Candidate:');
        $this->command->info('  Email: candidate@example.com');
        $this->command->info('  Password: password123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}

