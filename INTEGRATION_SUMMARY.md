# Complete Integration Summary: Career Module + pradytec.com

## ✅ What's Been Done

1. **Laravel Career Module** is uploaded to `public_html/Careers/`
2. **Configuration files created** for subdirectory deployment
3. **Vite config updated** to handle subdirectory base path

## 📋 What You Need to Do Next

### Step 1: Configure .htaccess in public_html (CRITICAL)

**Location:** `public_html/.htaccess`

Add these rules to route `/careers` requests to the Laravel app:

```apache
RewriteEngine On

# Route /careers to Laravel application
RewriteCond %{REQUEST_URI} ^/careers(/.*)?$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^careers(/.*)?$ /Careers/public/$1 [L]

# If request is for /careers and it's a file (CSS, JS, images), serve it directly
RewriteCond %{REQUEST_URI} ^/careers/.*$
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^careers/(.*)$ /Careers/public/$1 [L]
```

**Important:** Add these rules BEFORE any existing catch-all rules in your `.htaccess`.

### Step 2: Configure Laravel Environment

**Location:** `public_html/Careers/.env`

Create or update the `.env` file with:

```env
APP_NAME="Career Module"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pradytec.com/careers

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session Configuration
SESSION_DOMAIN=.pradytec.com
SESSION_PATH=/careers
```

**Generate App Key:**
- Via SSH: `cd public_html/Careers && php artisan key:generate`
- Or manually set `APP_KEY` in `.env`

### Step 3: Set File Permissions

Set these permissions via cPanel File Manager:
- `public_html/Careers/storage/` → **775**
- `public_html/Careers/bootstrap/cache/` → **775**

### Step 4: Database Setup

1. Create database in cPanel (or use existing)
2. Update `.env` with database credentials
3. Run migrations (via SSH or cPanel Terminal):
   ```bash
   cd public_html/Careers
   php artisan migrate --force
   ```

### Step 5: Build Assets

**Option A: If you have Node.js/npm access:**
```bash
cd public_html/Careers
npm install
npm run build
```

**Option B: If no Node.js access:**
- Build assets locally on your computer
- Upload the `public/build/` folder to `public_html/Careers/public/build/`

### Step 6: Add Navigation Link to Existing Site

**Location:** `public_html/header.php`

Find your navigation menu and add:

```php
<li><a href="/careers">Careers</a></li>
```

Or if using a different structure:

```php
<a href="/careers" class="nav-link">Careers</a>
```

**See `ADD_NAVIGATION_LINK.md` for detailed instructions.**

### Step 7: Test Everything

1. ✅ Visit `https://pradytec.com/careers` - should show careers homepage
2. ✅ Click "Careers" link from main site - should navigate correctly
3. ✅ Test admin login: `https://pradytec.com/careers/admin`
4. ✅ Check CSS/JS assets load correctly
5. ✅ Test job application form submission

## 🔗 URL Structure

After setup, your URLs will be:

- **Main Site:** `https://pradytec.com`
- **Careers Homepage:** `https://pradytec.com/careers`
- **Job Listing:** `https://pradytec.com/careers/job-slug`
- **Apply to Job:** `https://pradytec.com/careers/job-slug/apply`
- **Admin Panel:** `https://pradytec.com/careers/admin`
- **Application Status:** `https://pradytec.com/careers/application/status/lookup`

## 📚 Documentation Files Created

1. **DEPLOYMENT_SUBDIRECTORY.md** - Complete deployment guide
2. **QUICK_SETUP_CHECKLIST.md** - Quick reference checklist
3. **ADD_NAVIGATION_LINK.md** - How to add Careers link to navigation
4. **public_html_htaccess_example.txt** - .htaccess rules template
5. **header_example_with_careers.php** - Example header with Careers link

## ⚠️ Common Issues & Solutions

### Issue: 404 Not Found when visiting /careers
**Solution:**
- Check `.htaccess` in `public_html/` has the routing rules
- Verify mod_rewrite is enabled in cPanel
- Check file permissions

### Issue: CSS/JS Not Loading
**Solution:**
- Rebuild assets: `npm run build`
- Check browser console for 404 errors
- Verify `vite.config.js` has `base: '/careers/'`

### Issue: Database Connection Error
**Solution:**
- Verify database credentials in `.env`
- Check database user has proper permissions
- Ensure database exists

### Issue: Permission Denied
**Solution:**
- Set storage permissions: `chmod -R 775 storage bootstrap/cache`
- Check file ownership matches web server user

## 🎯 Quick Start (Minimum Steps)

If you want to get it working quickly:

1. Add `.htaccess` rules to `public_html/.htaccess`
2. Create `.env` in `Careers/` with database config
3. Set permissions on `storage/` and `bootstrap/cache/`
4. Run `php artisan migrate --force`
5. Add Careers link to `header.php`
6. Test at `https://pradytec.com/careers`

## 📞 Need Help?

Refer to the detailed guides:
- **DEPLOYMENT_SUBDIRECTORY.md** for step-by-step instructions
- **QUICK_SETUP_CHECKLIST.md** for a checklist format
- **ADD_NAVIGATION_LINK.md** for navigation integration

## ✅ Final Checklist

- [ ] `.htaccess` rules added to `public_html/`
- [ ] `.env` file created in `Careers/` with correct settings
- [ ] App key generated
- [ ] File permissions set (storage, bootstrap/cache)
- [ ] Database created and migrations run
- [ ] Assets built (`npm run build` or uploaded)
- [ ] Careers link added to `header.php`
- [ ] Tested at `https://pradytec.com/careers`
- [ ] Tested navigation link from main site
- [ ] Tested admin login
- [ ] Tested job application form

---

**Once all steps are complete, your Career Module will be fully integrated with pradytec.com!** 🎉


