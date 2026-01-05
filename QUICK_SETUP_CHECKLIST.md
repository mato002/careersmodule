# Quick Setup Checklist for Career Module Deployment

## ✅ Pre-Deployment Checklist

- [ ] Laravel files uploaded to `public_html/Careers/`
- [ ] Database created in cPanel
- [ ] PHP version is 8.2 or higher (check in cPanel)
- [ ] mod_rewrite is enabled (usually enabled by default)

## 📋 Step-by-Step Setup

### 1. Create/Update `.htaccess` in `public_html/`
- [ ] Copy rules from `public_html_htaccess_example.txt`
- [ ] Add to existing `.htaccess` or create new one
- [ ] Place rules BEFORE any existing catch-all rules

### 2. Configure Laravel Environment
- [ ] Create `.env` file in `public_html/Careers/`
- [ ] Set `APP_URL=https://pradytec.com/careers`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Generate app key: `php artisan key:generate`

### 3. Set File Permissions
- [ ] `storage/` folder: 775
- [ ] `bootstrap/cache/` folder: 775
- [ ] `.env` file: 644

### 4. Database Setup
- [ ] Run migrations: `php artisan migrate --force`
- [ ] (Optional) Run seeders if needed

### 5. Build Assets
- [ ] Update `vite.config.js` (already done - has base path)
- [ ] Run `npm install` (if Node.js available)
- [ ] Run `npm run build`
- [ ] Or upload pre-built `public/build/` folder

### 6. Add Navigation Link
- [ ] Edit `public_html/header.php` (or your navigation file)
- [ ] Add: `<li><a href="/careers">Careers</a></li>`

### 7. Test
- [ ] Visit `https://pradytec.com/careers`
- [ ] Test admin login: `https://pradytec.com/careers/admin`
- [ ] Check CSS/JS loading
- [ ] Test job application form

## 🔧 Common Issues & Solutions

### CSS/JS Not Loading
**Solution:** 
- Rebuild assets: `npm run build`
- Check browser console for errors
- Verify `vite.config.js` has correct base path

### 404 Errors
**Solution:**
- Check `.htaccess` in `public_html/`
- Verify mod_rewrite is enabled
- Check file permissions

### Database Connection Failed
**Solution:**
- Verify credentials in `.env`
- Check database user permissions
- Ensure database exists

### Permission Denied
**Solution:**
- Set storage to 775: `chmod -R 775 storage`
- Set bootstrap/cache to 775: `chmod -R 775 bootstrap/cache`

## 📞 Need Help?

Refer to `DEPLOYMENT_SUBDIRECTORY.md` for detailed instructions.


