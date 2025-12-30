# Production Deployment Instructions

## After deploying updated files, run these commands on production:

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized caches (optional, for better performance)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Files that need to be deployed:

1. `app/Http/Controllers/Admin/JobPostController.php` - Contains the new `toggleStatus()` method
2. `routes/web.php` - Contains the new toggle-status route
3. `resources/views/admin/jobs/index.blade.php` - Updated with activate/deactivate button
4. `resources/views/admin/jobs/show.blade.php` - Updated with toggle button

## Quick fix command (run on production server):

```bash
cd /home/fortress/FotressLenders
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

