# Routes Updated for Subdirectory Deployment

## Changes Made

I've updated `routes/web.php` to remove the `/careers` prefix from the public career routes since the application is deployed in the `/careers` subdirectory.

### What Changed

**Before:**
```php
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
```

**After:**
```php
Route::get('/', [CareerController::class, 'index'])->name('careers.index');
Route::get('/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
```

### What Stayed the Same

- ✅ Route names are unchanged (`careers.index`, `careers.show`, `careers.apply`, etc.)
- ✅ All other routes remain unchanged
- ✅ Admin routes remain unchanged
- ✅ Application status routes remain unchanged

## Next Steps

1. **Upload the updated `routes/web.php`** to your server at `public_html/Careers/routes/web.php`

2. **Clear Laravel caches** (if you have SSH access):
   ```bash
   cd public_html/Careers
   php artisan route:clear
   php artisan config:clear
   php artisan cache:clear
   ```

   Or via browser (if you have admin access):
   - Visit: `https://pradytec.com/careers/admin/maintenance/clear-caches`

3. **Test the routes:**
   - `https://pradytec.com/careers` - should show job listings ✅
   - `https://pradytec.com/careers/senior-software-developer` - should show job details ✅
   - `https://pradytec.com/careers/senior-software-developer/apply` - should show application form ✅

## How It Works Now

- When you visit `/careers/senior-software-developer`
- `.htaccess` routes to `/Careers/public/senior-software-developer`
- Laravel receives path as `/senior-software-developer` (without `/careers`)
- Route `/{jobPost:slug}` matches it ✅
- Laravel generates URLs with `/careers` prefix because `APP_URL=https://pradytec.com/careers`

## Important Notes

1. **URL Generation**: When you use `route('careers.show', $jobPost)` in your views, Laravel will still generate URLs like `/careers/senior-software-developer` because `APP_URL` includes `/careers`. This is correct!

2. **Route Names**: All route names stay the same, so existing `route()` calls in your Blade templates will continue to work without any changes.

3. **Other Routes**: Routes like `/application/status/lookup`, `/admin/*`, etc. are not affected and continue to work as before.

## If Something Doesn't Work

1. Make sure you uploaded the updated `routes/web.php`
2. Clear all Laravel caches
3. Check that `APP_URL=https://pradytec.com/careers` in your `.env` file
4. Verify the `.htaccess` routing is working (main page should load)



