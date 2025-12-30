-- SQL query to create HR Manager user
-- Email: hr@fortresslenders.com
-- Password: @Kenya1234
-- Role: hr_manager

-- IMPORTANT: First generate the password hash by running:
-- php artisan tinker
-- Then type: Hash::make('@Kenya1234')
-- Copy the output hash and replace the hash below

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
    '$2y$12$YOUR_PASSWORD_HASH_HERE', -- Replace with hash from: Hash::make('@Kenya1234')
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

-- OR use the PHP script instead (easier):
-- php create_hr_manager.php
