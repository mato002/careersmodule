-- SQL Query to add unique constraint to job_applications table in production
-- This prevents duplicate applications (same email + phone + job_post_id)
-- Run this query in your production database

-- First, check if there are any existing duplicates and handle them
-- (You may want to keep the most recent one or delete duplicates)
-- SELECT email, phone, job_post_id, COUNT(*) as count
-- FROM job_applications
-- GROUP BY email, phone, job_post_id
-- HAVING COUNT(*) > 1;

-- Add the unique constraint
ALTER TABLE `job_applications`
ADD UNIQUE KEY `unique_application_per_job` (`email`, `phone`, `job_post_id`);

