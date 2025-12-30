-- SQL queries to create both Admin and HR Manager users
-- Both use password: @Kenya1234
-- 
-- STEP 1: Generate password hash first
-- Run: php artisan tinker
-- Then: Hash::make('@Kenya1234')
-- Copy the hash and replace YOUR_PASSWORD_HASH_HERE in both queries below

-- ============================================
-- Admin User
-- ============================================
INSERT INTO `users` (
    `name`, 
    `email`, 
    `password`, 
    `email_verified_at`, 
    `is_admin`, 
    `is_banned`, 
    `role`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Fortress Admin',
    'admin@fortresslenders.com',
    '$2y$12$YOUR_PASSWORD_HASH_HERE', -- Replace with hash from Hash::make('@Kenya1234')
    NOW(),
    1,
    0,
    'admin',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- ============================================
-- HR Manager User
-- ============================================
INSERT INTO `users` (
    `name`, 
    `email`, 
    `password`, 
    `email_verified_at`, 
    `is_admin`, 
    `is_banned`, 
    `role`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'HR Manager',
    'hr@fortresslenders.com',
    '$2y$12$YOUR_PASSWORD_HASH_HERE', -- Use the SAME hash as above
    NOW(),
    0,
    0,
    'hr_manager',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- ============================================
-- EASIER METHOD: Use PHP script instead
-- ============================================
-- Just run: php update_admin_password.php
-- This will create/update both users automatically with the correct password hash

