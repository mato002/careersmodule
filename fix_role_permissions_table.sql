-- SQL to fix the role_permissions table
-- This removes the default value from the JSON column which causes MySQL error

-- First, drop the table if it exists (if you haven't created it yet, skip this)
-- DROP TABLE IF EXISTS `role_permissions`;

-- Create the table without default on JSON column
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permission_key` varchar(255) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `permission_group` varchar(255) DEFAULT NULL,
  `roles` json NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_permission_key_unique` (`permission_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default permissions
INSERT INTO `role_permissions` (`permission_key`, `permission_name`, `permission_group`, `roles`, `display_order`, `created_at`, `updated_at`) VALUES
('dashboard', 'Dashboard', 'general', '["admin","hr_manager","loan_manager","editor"]', 1, NOW(), NOW()),
('profile_settings', 'Profile Settings', 'general', '["admin","hr_manager","loan_manager","editor"]', 2, NOW(), NOW()),
('products', 'Products', 'content', '["admin","hr_manager","loan_manager","editor"]', 3, NOW(), NOW()),
('content_management', 'Content Management', 'content', '["admin","hr_manager","loan_manager","editor"]', 4, NOW(), NOW()),
('contact_messages', 'Contact Messages', 'communication', '["admin","hr_manager","loan_manager","editor"]', 5, NOW(), NOW()),
('loan_applications', 'Loan Applications', 'management', '["admin","loan_manager"]', 6, NOW(), NOW()),
('careers', 'Careers', 'management', '["admin","hr_manager"]', 7, NOW(), NOW()),
('team_members', 'Team Members', 'admin', '["admin"]', 8, NOW(), NOW()),
('branches', 'Branches', 'admin', '["admin"]', 9, NOW(), NOW()),
('activity_logs', 'Activity Logs', 'admin', '["admin"]', 10, NOW(), NOW()),
('settings', 'Settings', 'admin', '["admin"]', 11, NOW(), NOW()),
('user_management', 'User Management', 'admin', '["admin"]', 12, NOW(), NOW());







