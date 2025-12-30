<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate candidates from users table to candidates table
        $candidateUsers = DB::table('users')
            ->where(function($query) {
                $query->where('role', 'candidate')
                      ->orWhere('role', 'user')
                      ->orWhereNull('role');
            })
            ->where('is_admin', false)
            ->get();

        foreach ($candidateUsers as $user) {
            // Check if candidate already exists
            $existingCandidate = DB::table('candidates')
                ->where('email', $user->email)
                ->first();

            if (!$existingCandidate) {
                // Create candidate
                $candidateId = DB::table('candidates')->insertGetId([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'email_verified_at' => $user->email_verified_at,
                    'remember_token' => $user->remember_token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);

                // Update job applications to use candidate_id
                DB::table('job_applications')
                    ->where('user_id', $user->id)
                    ->update(['candidate_id' => $candidateId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // Data would need to be moved back manually if needed
    }
};

