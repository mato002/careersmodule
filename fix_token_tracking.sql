-- Fix Token Tracking Issues
-- Run this in phpMyAdmin or MySQL

-- 1. Fix job posts without company_id (assign to first company)
UPDATE job_posts 
SET company_id = (SELECT id FROM companies LIMIT 1)
WHERE company_id IS NULL;

-- 2. Fix applications from job posts
UPDATE job_applications ja
INNER JOIN job_posts jp ON ja.job_post_id = jp.id
SET ja.company_id = jp.company_id
WHERE ja.company_id IS NULL 
AND jp.company_id IS NOT NULL;

-- 3. Fix remaining applications (assign to first company)
UPDATE job_applications 
SET company_id = (SELECT id FROM companies LIMIT 1)
WHERE company_id IS NULL;

-- Verify
SELECT 
    COUNT(*) as total_apps,
    SUM(CASE WHEN company_id IS NOT NULL THEN 1 ELSE 0 END) as with_company,
    SUM(CASE WHEN company_id IS NULL THEN 1 ELSE 0 END) as without_company
FROM job_applications;

