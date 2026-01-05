# Deployment Guide: Laravel Career Module in Subdirectory

## Current Setup
- Laravel app location: `public_html/Careers/`
- Target URL: `pradytec.com/careers`
- Existing site: `pradytec.com` (simple PHP)

## Prerequisites

### ⚠️ IMPORTANT: PHP Version Requirement
**The Career Module requires PHP 8.2 or higher.**

Before proceeding, ensure your server has PHP 8.2+ available:
1. Log into cPanel
2. Go to **"Select PHP Version"** or **"MultiPHP Manager"**
3. Select **PHP 8.2** or higher
4. If your existing site needs PHP 8.1, use **MultiPHP Manager** to set:
   - `public_html/` → PHP 8.1 (for existing site)
   - `public_html/Careers/` → PHP 8.2 (for Laravel app)

**See `PHP_VERSION_SETUP.md` for detailed instructions.**

## Step-by-Step Deployment Instructions

### Step 1: Create .htaccess in public_html Root

Create a new file `public_html/.htaccess` (or update existing one) with the following content to route `/careers` requests to the Laravel app:

```apache
# Existing pradytec.com rules (keep your current rules here)

# Route /careers to Laravel application
RewriteEngine On

# If request is for /careers, route to Careers/public/
RewriteCond %{REQUEST_URI} ^/careers(/.*)?$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^careers(/.*)?$ /Careers/public/$1 [L]

# If request is for /careers and it's a file, serve it directly
RewriteCond %{REQUEST_URI} ^/careers/.*$
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^careers/(.*)$ /Careers/public/$1 [L]
```

**Important:** If you already have a `.htaccess` in `public_html`, add these rules BEFORE any existing rules that might catch all requests.

### Step 2: Update Laravel's public/.htaccess

The existing `public/.htaccess` should work, but we need to ensure it handles the subdirectory correctly. The current `.htaccess` is already configured correctly.

### Step 3: Verify PHP Version

Before proceeding, verify PHP version:
```bash
cd public_html/Careers
php -v
```
Should show PHP 8.2 or higher. If not, see `PHP_VERSION_SETUP.md`.

### Step 4: Environment Configuration

1. **Create/Update `.env` file** in `public_html/Careers/`:

```env
APP_NAME="Career Module"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://pradytec.com/careers

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session Configuration (important for subdirectory)
SESSION_DOMAIN=.pradytec.com
SESSION_PATH=/careers

# Other configurations...
```

2. **Generate Application Key** (if not already done):
   - Via SSH: `cd public_html/Careers && php artisan key:generate`
   - Or manually set `APP_KEY` in `.env`

### Step 5: Set Proper Permissions

Set these permissions via cPanel File Manager or SSH:

```bash
# Storage and cache directories must be writable
chmod -R 775 public_html/Careers/storage
chmod -R 775 public_html/Careers/bootstrap/cache
```

### Step 6: Update Vite Configuration for Assets

Update `vite.config.js` to handle subdirectory base path:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: '/careers/',
});
```

**Important:** After updating `vite.config.js`, rebuild assets:
```bash
npm run build
```

### Step 7: Database Setup

1. Create a database in cPanel (or use existing)
2. Update `.env` with database credentials
3. Run migrations:
   ```bash
   cd public_html/Careers
   php artisan migrate --force
   ```

### Step 8: Build Assets

If you have access to Node.js/npm:
```bash
cd public_html/Careers
npm install
npm run build
```

If you don't have Node.js access, you can:
- Build assets locally and upload the `public/build` folder
- Or use CDN alternatives for CSS/JS

### Step 9: Add Navigation Link to Existing Site

Add this to your existing PHP site's navigation (likely in `header.php`):

```php
<li><a href="/careers">Careers</a></li>
```

Or if you want it styled consistently:
```php
<li><a href="/careers" class="nav-link">Careers</a></li>
```

### Step 10: Test the Installation

1. Visit `https://pradytec.com/careers` - should show the careers homepage
2. Test admin login: `https://pradytec.com/careers/admin`
3. Check that CSS/JS assets load correctly
4. Test a job application form

## Troubleshooting

### Issue: 404 Not Found
- Check that `.htaccess` in `public_html` is correct
- Verify mod_rewrite is enabled in cPanel
- Check file permissions

### Issue: CSS/JS Not Loading
- Rebuild assets: `npm run build`
- Check browser console for 404 errors
- Verify `vite.config.js` has `base: '/careers/'`

### Issue: Database Connection Error
- Verify database credentials in `.env`
- Check database user has proper permissions
- Ensure database exists

### Issue: PHP Version Error
- **Error:** "Composer detected issues in your platform: Your Composer dependencies require a PHP version >= 8.2.0"
- **Solution:** See `PHP_VERSION_SETUP.md` for detailed instructions
- Change PHP version to 8.2+ in cPanel's "Select PHP Version" or "MultiPHP Manager"
- Use MultiPHP Manager to set different PHP versions for main site vs Career Module

### Issue: Permission Denied
- Set storage permissions: `chmod -R 775 storage bootstrap/cache`
- Check file ownership matches web server user

## Alternative: Using Subdomain

If subdirectory causes issues, consider using `careers.pradytec.com`:
1. Create subdomain in cPanel
2. Point document root to `public_html/Careers/public`
3. Update `APP_URL` in `.env` to `https://careers.pradytec.com`
4. No `.htaccess` routing needed

