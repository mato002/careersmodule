# Fixing Subpage Routing (404 on /careers/job-slug)

## Problem
The main `/careers` page works, but subpages like `/careers/senior-software-developer` return 404 errors.

## Root Cause
The `.htaccess` routing is stripping the `/careers` prefix from the path, so Laravel doesn't see the full route path it expects.

## Solution

### Step 1: Update public_html/.htaccess

Replace your `public_html/.htaccess` with the content from `public_html_htaccess_final.txt`.

**Key change:** The rewrite rule now routes to `/Careers/public/$1` which preserves the path structure, and Laravel's `.htaccess` will handle the rest.

### Step 2: Verify public/.htaccess

The `public_html/Careers/public/.htaccess` should already be correct (it's the standard Laravel `.htaccess`). 

If you need to verify, it should match the content in `public_htaccess_subdirectory.txt`.

### Step 3: How It Works

1. Request: `https://pradytec.com/careers/senior-software-developer`
2. `public_html/.htaccess` matches `/careers` and routes to `/Careers/public/senior-software-developer`
3. `Careers/public/.htaccess` sees the request and routes to `index.php`
4. Laravel's `Request::capture()` sees `REQUEST_URI` as `/careers/senior-software-developer`
5. Laravel matches the route `/careers/{jobPost:slug}` ✅

### Step 4: Test

After updating `.htaccess`:
1. Clear browser cache
2. Visit `https://pradytec.com/careers` - should work
3. Click on a job listing - should now work
4. Visit `https://pradytec.com/careers/senior-software-developer` directly - should work

## Alternative: If Still Not Working

If the above doesn't work, we may need to configure Laravel to handle the subdirectory base path. This would require:

1. Setting `APP_URL=https://pradytec.com/careers` in `.env`
2. Potentially updating route definitions (not recommended)

But try the `.htaccess` fix first - it should work!

## Troubleshooting

### Still Getting 404?
1. Check that `public_html/.htaccess` has the updated rules
2. Verify `Careers/public/.htaccess` exists and is correct
3. Check Laravel logs: `storage/logs/laravel.log`
4. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`

### Check REQUEST_URI
Add this temporarily to `public/index.php` to see what Laravel receives:
```php
error_log('REQUEST_URI: ' . $_SERVER['REQUEST_URI']);
```

Then check your error logs to see what path Laravel is receiving.





