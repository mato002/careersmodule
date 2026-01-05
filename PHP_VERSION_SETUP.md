# PHP Version Setup Guide for Career Module

## Requirement
The Career Module (Laravel 12) requires **PHP 8.2 or higher**.

## How to Check Current PHP Version

### Method 1: Via cPanel
1. Log into cPanel
2. Go to **"Select PHP Version"** or **"MultiPHP Manager"**
3. Check the current version displayed

### Method 2: Via SSH
```bash
php -v
```

### Method 3: Create a test file
Create a file `phpinfo.php` in `public_html/`:
```php
<?php phpinfo(); ?>
```
Visit `https://pradytec.com/phpinfo.php` and check the PHP version. **Delete this file after checking!**

## How to Change PHP Version in cPanel

### Step 1: Access PHP Selector
1. Log into cPanel
2. Find and click **"Select PHP Version"** or **"MultiPHP Manager"**
   - Usually located in the "Software" section
   - May also be under "Software" → "Select PHP Version"

### Step 2: Select PHP Version
1. You'll see a list of available PHP versions
2. Select **PHP 8.2** or **PHP 8.3** (or higher)
3. Click **"Set as current"** or **"Apply"**

### Step 3: Set for Specific Directory (Optional)
If you want to set PHP 8.2 only for the Career Module:

1. In **"MultiPHP Manager"**, you can set PHP version per directory
2. Navigate to `public_html/Careers/`
3. Select PHP 8.2 for that directory

### Step 4: Verify
1. Check PHP version again using one of the methods above
2. Verify it shows PHP 8.2 or higher

## Alternative: Update .htaccess for Specific Directory

If you can't change the global PHP version, you can set it for the Careers directory only by adding this to `public_html/Careers/.htaccess`:

```apache
# Set PHP version for this directory
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php82 .php .php8 .phtml
</IfModule>
```

**Note:** Replace `ea-php82` with the correct handler name for your hosting:
- `ea-php82` for PHP 8.2
- `ea-php83` for PHP 8.3
- `ea-php81` for PHP 8.1 (not recommended, but minimum)

## After Changing PHP Version

1. **Clear Composer cache:**
   ```bash
   cd public_html/Careers
   composer clear-cache
   ```

2. **Reinstall dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Clear Laravel cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

## Troubleshooting

### Issue: PHP 8.2 Not Available
**Solution:**
- Contact your hosting provider to enable PHP 8.2
- Most modern cPanel hosts support PHP 8.2+
- If unavailable, consider upgrading hosting plan

### Issue: Composer Still Shows Error
**Solution:**
1. Verify PHP version: `php -v`
2. Check which PHP Composer is using: `composer --version`
3. If using SSH, ensure you're using the correct PHP:
   ```bash
   which php
   /usr/bin/php -v
   ```

### Issue: Site Breaks After PHP Change
**Solution:**
- Your existing pradytec.com site might need PHP 8.1 (based on your .htaccess)
- Use **MultiPHP Manager** to set:
  - `public_html/` → PHP 8.1 (for existing site)
  - `public_html/Careers/` → PHP 8.2 (for Laravel app)

### Issue: Can't Access MultiPHP Manager
**Solution:**
- Contact your hosting provider
- They may need to enable it for your account
- Some shared hosts restrict PHP version changes

## Recommended Setup

For best compatibility:
- **Main site** (`public_html/`): PHP 8.1 (if your existing site requires it)
- **Career Module** (`public_html/Careers/`): PHP 8.2 or higher

This way, both sites can run on their optimal PHP versions.

## Quick Checklist

- [ ] Checked current PHP version
- [ ] Changed PHP version to 8.2+ in cPanel
- [ ] Verified PHP version change
- [ ] Cleared Composer cache
- [ ] Ran `composer install` successfully
- [ ] Cleared Laravel caches
- [ ] Tested the Career Module


