-- SAFE VERSION: View what will be deleted before actually deleting
-- Run this first to see which records will be removed

-- View duplicates that will be kept (most recent)
SELECT 
    ja1.id as 'Will Keep (Most Recent)',
    ja1.email,
    ja1.phone,
    ja1.job_post_id,
    ja1.created_at as 'Application Date',
    ja1.name
FROM job_applications ja1
INNER JOIN (
    SELECT email, phone, job_post_id, MAX(created_at) as max_created_at
    FROM job_applications
    GROUP BY email, phone, job_post_id
    HAVING COUNT(*) > 1
) duplicates ON ja1.email = duplicates.email
    AND ja1.phone = duplicates.phone
    AND ja1.job_post_id = duplicates.job_post_id
    AND ja1.created_at = duplicates.max_created_at
ORDER BY ja1.email, ja1.phone, ja1.job_post_id;

-- View duplicates that will be deleted (older ones)
SELECT 
    ja1.id as 'Will Delete (Older)',
    ja1.email,
    ja1.phone,
    ja1.job_post_id,
    ja1.created_at as 'Application Date',
    ja1.name
FROM job_applications ja1
INNER JOIN job_applications ja2
WHERE ja1.email = ja2.email
  AND ja1.phone = ja2.phone
  AND ja1.job_post_id = ja2.job_post_id
  AND ja1.id < ja2.id
ORDER BY ja1.email, ja1.phone, ja1.job_post_id, ja1.created_at;

-- If the above looks correct, then run the DELETE query from cleanup_duplicate_job_applications.sql

