# Fix Subpage Routing - Update index.php

## The Problem
Subpages like `/careers/senior-software-developer` return 404 because Laravel receives the path without the `/careers` prefix.

## The Solution
Update `public_html/Careers/public/index.php` to prepend `/careers` to the REQUEST_URI.

## Step-by-Step Instructions

### Step 1: Open index.php in cPanel
1. Log into cPanel
2. Go to **File Manager**
3. Navigate to `public_html/Careers/public/`
4. Click on `index.php` to edit it

### Step 2: Add the Fix
Find this section (around line 14-20):
```php
// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
```

**Add this code BETWEEN those two sections:**
```php
// Fix REQUEST_URI for subdirectory deployment
// When deployed in /careers subdirectory, ensure REQUEST_URI includes /careers prefix
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // If the URI doesn't start with /careers, prepend it
    if (!str_starts_with($requestUri, '/careers')) {
        // Prepend /careers to the URI (unless it's already /)
        if ($requestUri === '/' || $requestUri === '') {
            $_SERVER['REQUEST_URI'] = '/careers';
        } else {
            $_SERVER['REQUEST_URI'] = '/careers' . $requestUri;
        }
    }
}
```

### Step 3: Complete Updated File
Your `index.php` should look like this:

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
// When deployed in /careers subdirectory, ensure REQUEST_URI includes /careers prefix
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // If the URI doesn't start with /careers, prepend it
    if (!str_starts_with($requestUri, '/careers')) {
        // Prepend /careers to the URI (unless it's already /)
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
1. **Save** the file in cPanel
2. **Clear browser cache**
3. Test:
   - `https://pradytec.com/careers` - should work
   - `https://pradytec.com/careers/senior-software-developer` - should now work!

## Why This Works

When `.htaccess` routes `/careers/senior-software-developer` to `/Careers/public/senior-software-developer`, Laravel's `REQUEST_URI` becomes `/senior-software-developer` (relative to the public directory).

But your routes expect `/careers/senior-software-developer`. This fix prepends `/careers` back to the URI before Laravel processes it.

## Troubleshooting

If it still doesn't work:
1. Check that you saved the file correctly
2. Clear Laravel cache: `php artisan cache:clear` (via SSH if available)
3. Check error logs: `storage/logs/laravel.log`





