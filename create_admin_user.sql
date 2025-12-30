-- SQL query to create/update admin user
-- Email: admin@fortresslenders.com
-- Password: @Kenya1234
-- Role: admin

-- IMPORTANT: First generate the password hash by running:
-- php artisan tinker
-- Then type: Hash::make('@Kenya1234')
-- Copy the output hash and replace YOUR_PASSWORD_HASH_HERE below

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
    '$2y$12$YOUR_PASSWORD_HASH_HERE', -- Replace with hash from: Hash::make('@Kenya1234')
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

-- OR use the PHP script instead (easier - it will generate the hash automatically):
-- php update_admin_password.php

