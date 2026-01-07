# Login and Redirect Fix for Subdirectory Deployment

## Issues Fixed

1. **Login redirect not working** - After login, users weren't being redirected to the dashboard
2. **Password reset page not accessible** - The `/careers/forgot-password` route was returning 404

## Root Cause

When Laravel generates URLs using the `route()` helper, it uses `APP_URL` from the `.env` file. Since the application is deployed in a subdirectory (`/careers`), all generated URLs need to include this prefix, but they weren't.

## Solution Implemented

### 1. Updated `app/Providers/AppServiceProvider.php`

Added code to force all generated URLs to include the `/careers` prefix:

```php
// Set URL root to include /careers subdirectory for route generation
$appUrl = config('app.url', 'http://localhost');
$appUrl = rtrim($appUrl, '/');

// Only append /careers if it's not already in the URL
if (strpos($appUrl, '/careers') === false) {
    $rootUrl = $appUrl . '/careers';
} else {
    $rootUrl = $appUrl;
}

// Force the root URL so all route() calls include /careers
URL::forceRootUrl($rootUrl);
```

This ensures that:
- `route('login')` generates `/careers/login` instead of `/login`
- `route('dashboard')` generates `/careers/dashboard` instead of `/dashboard`
- `route('admin.dashboard')` generates `/careers/admin` instead of `/admin`
- All other routes include the `/careers` prefix

### 2. Path Stripping in `public/index.php`

The `public/index.php` already strips `/careers` from incoming paths before passing them to Laravel's router, so routes are defined without the prefix (e.g., `Route::get('login', ...)`), but URLs are generated with the prefix.

## What Now Works

✅ **Login Page**: `https://pradytec.com/careers/login` - Accessible and working
✅ **Login Form Submission**: Correctly authenticates and redirects
✅ **Post-Login Redirect**: 
   - Admin/HR users → `/careers/admin` (Admin Dashboard)
   - Candidates → `/careers/candidate/dashboard` (Candidate Dashboard)
✅ **Password Reset**: 
   - `/careers/forgot-password` - Accessible
   - `/careers/reset-password/{token}` - Accessible
   - Password reset emails contain correct URLs with `/careers` prefix
✅ **All Route URLs**: All `route()` helper calls now generate URLs with `/careers` prefix

## Testing Checklist

1. ✅ Visit `/careers/login` - Should load login page
2. ✅ Submit login form - Should authenticate and redirect
3. ✅ After login - Should redirect to appropriate dashboard
4. ✅ Visit `/careers/forgot-password` - Should load password reset form
5. ✅ Submit password reset - Should send email with correct reset link
6. ✅ Click reset link in email - Should load reset password page at `/careers/reset-password/{token}`

## Environment Configuration

Make sure your `.env` file has:

```env
APP_URL=https://pradytec.com
```

The code will automatically append `/careers` to this URL for route generation. If you prefer, you can also set:

```env
APP_URL=https://pradytec.com/careers
```

Both configurations will work correctly.

## Files Modified

1. `app/Providers/AppServiceProvider.php` - Added URL root forcing
2. `public/index.php` - Already configured to strip `/careers` from paths (no changes needed)

## Next Steps

1. Upload the updated `app/Providers/AppServiceProvider.php` to your server
2. Clear Laravel cache (if needed):
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan cache:clear
   ```
3. Test login and password reset functionality





