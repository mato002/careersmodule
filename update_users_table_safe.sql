-- Safe SQL queries to add missing columns to production users table
-- This script checks if columns exist before adding them
-- Run this entire script - it will skip columns that already exist

-- ============================================
-- Add is_banned column (if it doesn't exist)
-- ============================================
SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'is_banned';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1', -- Column exists, do nothing
  CONCAT('ALTER TABLE `', @tablename, '` ADD COLUMN `', @columnname, '` tinyint(1) NOT NULL DEFAULT ''0'' AFTER `is_admin`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================
-- Add role column (if it doesn't exist)
-- ============================================
SET @columnname = 'role';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1', -- Column exists, do nothing
  CONCAT('ALTER TABLE `', @tablename, '` ADD COLUMN `', @columnname, '` varchar(255) NOT NULL DEFAULT ''user'' AFTER `is_admin`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================
-- Migrate existing is_admin values to role
-- ============================================
-- Update existing admin users to have role='admin'
UPDATE `users` 
SET `role` = 'admin' 
WHERE `is_admin` = 1 AND (`role` IS NULL OR `role` = '' OR `role` = 'user');

-- ============================================
-- Verify the changes
-- ============================================
-- Check table structure
DESCRIBE `users`;

-- Check users and their roles
SELECT id, name, email, is_admin, is_banned, role FROM `users`;







