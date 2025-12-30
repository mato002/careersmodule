-- SQL queries to add missing columns to production users table
-- This adds: is_banned and role columns
-- 
-- IMPORTANT: Run these queries one at a time in phpMyAdmin
-- If a column already exists, you'll get an error - just skip that query

-- ============================================
-- Step 1: Add is_banned column
-- ============================================
-- If column doesn't exist, run this:
ALTER TABLE `users` 
ADD COLUMN `is_banned` tinyint(1) NOT NULL DEFAULT '0' 
AFTER `is_admin`;

-- ============================================
-- Step 2: Add role column
-- ============================================
-- If column doesn't exist, run this:
ALTER TABLE `users` 
ADD COLUMN `role` varchar(255) NOT NULL DEFAULT 'user' 
AFTER `is_admin`;

-- ============================================
-- Step 3: Migrate existing is_admin values to role
-- ============================================
-- Update existing admin users to have role='admin'
UPDATE `users` 
SET `role` = 'admin' 
WHERE `is_admin` = 1 AND (`role` IS NULL OR `role` = '' OR `role` = 'user');

-- ============================================
-- Verify the changes
-- ============================================
-- Run this to check the table structure:
-- DESCRIBE `users`;

-- Run this to check users and their roles:
-- SELECT id, name, email, is_admin, is_banned, role FROM `users`;

