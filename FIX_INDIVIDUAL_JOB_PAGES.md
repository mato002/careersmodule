# Fix Individual Job Pages and Applications

## Current Issue
- ✅ `/careers` page works (main listing)
- ❌ `/careers/senior-software-developer` returns 404 (individual job page)
- ❌ `/careers/senior-software-developer/apply` returns 404 (application form)

## Solution

You need to update `public_html/Careers/public/index.php` on your server with the fix that prepends `/careers` to the REQUEST_URI.

### Step 1: Update index.php on Server

1. **Log into cPanel**
2. **Go to File Manager**
3. **Navigate to:** `public_html/Careers/public/`
4. **Edit `index.php`**

### Step 2: Add the Fix Code

Find this line (around line 14):
```php
require __DIR__.'/../vendor/autoload.php';
```

**Add this code RIGHT AFTER that line:**

```php
// Fix REQUEST_URI for subdirectory deployment
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    if (!str_starts_with($requestUri, '/careers')) {
        if ($requestUri === '/' || $requestUri === '') {
            $_SERVER['REQUEST_URI'] = '/careers';
        } else {
            $_SERVER['REQUEST_URI'] = '/careers' . $requestUri;
        }
    }
}
```

### Step 3: Complete Updated File

Your `public_html/Careers/public/index.php` should look like this:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Fix REQUEST_URI for subdirectory deployment
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    if (!str_starts_with($requestUri, '/careers')) {
        if ($requestUri === '/' || $requestUri === '') {
            $_SERVER['REQUEST_URI'] = '/careers';
        } else {
            $_SERVER['REQUEST_URI'] = '/careers' . $requestUri;
        }
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
```

### Step 4: Save and Test

1. **Save** the file
2. **Clear browser cache** (Ctrl+F5 or Cmd+Shift+R)
3. **Test:**
   - Visit `https://pradytec.com/careers` ✅
   - Click on a job listing (e.g., "Senior Software Developer") ✅
   - Click "Apply" or "View Details" ✅

## Why This Fix Works

**The Problem:**
- When you visit `/careers/senior-software-developer`
- `.htaccess` routes it to `/Careers/public/senior-software-developer`
- Laravel receives `REQUEST_URI` as `/senior-software-developer` (missing `/careers`)
- Your route expects `/careers/{jobPost:slug}`
- Route doesn't match → 404 error

**The Solution:**
- The fix prepends `/careers` back to the URI
- Laravel now sees `/careers/senior-software-developer`
- Route matches correctly ✅

## If It Still Doesn't Work

### Option 1: Debug Version (Temporary)

Use the debug version to see what Laravel is receiving:

1. Replace `index.php` with content from `public/index_debug.php`
2. Visit a job page
3. Check error logs: `public_html/Careers/storage/logs/laravel.log`
4. Look for "DEBUG INFO" entries
5. Share what you see

### Option 2: Check .htaccess

Make sure your `public_html/.htaccess` has the correct routing rules from `public_html_htaccess_final.txt`.

### Option 3: Verify Routes

Check that your routes are correct:
- Route: `/careers/{jobPost:slug}` ✅
- Route: `/careers/{jobPost:slug}/apply` ✅

These should be in `routes/web.php`.

## Quick Test

After updating `index.php`, try accessing directly:
- `https://pradytec.com/careers/senior-software-developer`

If it works, the fix is successful! 🎉





