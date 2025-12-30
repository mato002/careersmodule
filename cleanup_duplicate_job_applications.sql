-- SQL Queries to clean up duplicate job applications
-- This will keep the most recent application and delete older duplicates

-- Step 1: View duplicates (for verification)
SELECT email, phone, job_post_id, COUNT(*) as count
FROM job_applications
GROUP BY email, phone, job_post_id
HAVING COUNT(*) > 1;

-- Step 2: Delete duplicates, keeping only the most recent one (by created_at)
-- This query deletes older duplicates, keeping the most recent application
DELETE ja1 FROM job_applications ja1
INNER JOIN (
    SELECT email, phone, job_post_id, MAX(created_at) as max_created_at
    FROM job_applications
    GROUP BY email, phone, job_post_id
    HAVING COUNT(*) > 1
) duplicates ON ja1.email = duplicates.email
    AND ja1.phone = duplicates.phone
    AND ja1.job_post_id = duplicates.job_post_id
WHERE ja1.created_at < duplicates.max_created_at;

-- Step 3: Verify duplicates are removed
SELECT email, phone, job_post_id, COUNT(*) as count
FROM job_applications
GROUP BY email, phone, job_post_id
HAVING COUNT(*) > 1;
-- Should return 0 rows if cleanup was successful

-- Step 4: After cleanup, add the unique constraint
ALTER TABLE `job_applications`
ADD UNIQUE KEY `unique_application_per_job` (`email`, `phone`, `job_post_id`);

