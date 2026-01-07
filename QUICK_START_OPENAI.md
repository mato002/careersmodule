# 🚀 Quick Start: Make OpenAI Work Now!

## ✅ Already Completed

1. ✅ OpenAI API key configured
2. ✅ Environment variables added
3. ✅ Dependencies added to `composer.json`

## 📝 What You Need to Do (5 Steps)

### Step 1: Install Dependencies (2 minutes)

Open PowerShell/Command Prompt in your project folder and run:

```bash
composer install
```

This will install:
- `smalot/pdfparser` (for PDF CVs)
- `phpoffice/phpword` (for DOCX CVs)

### Step 2: Run Database Migrations (1 minute)

```bash
php artisan migrate
```

This creates the `cv_parsed_data` table and queue tables.

### Step 3: Create Queue Tables (if needed)

```bash
php artisan queue:table
php artisan migrate
```

### Step 4: Clear Cache (30 seconds)

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Start Queue Worker ⚠️ IMPORTANT

**Open a NEW terminal window** and run:

```bash
php artisan queue:work
```

**Keep this running!** This processes CVs and AI analysis in the background.

## 🧪 Test It Works

### Quick Test:

```bash
php artisan tinker
```

Then type:
```php
// Check dependencies
class_exists('\Smalot\PdfParser\Parser'); // Should be true
class_exists('\PhpOffice\PhpWord\IOFactory'); // Should be true

// Check config
config('ai.provider'); // Should be "openai"
config('queue.default'); // Should be "database"

exit
```

## 🎯 How to Use

1. **Submit a job application** with a CV file (PDF or DOCX)
2. **Queue worker processes it** automatically (if running)
3. **View results** in admin panel → Job Applications → View Details
4. **See AI summary** and parsed CV data

## ⚠️ Important Notes

- **Queue worker must be running** for automatic processing
- **For production:** Set up queue worker as a service (Supervisor on Linux, Windows Service on Windows)
- **Check logs** if something fails: `storage/logs/laravel.log`

## 📚 Full Documentation

See `OPENAI_COMPLETE_SETUP_GUIDE.md` for detailed instructions and troubleshooting.

---

**That's it!** Once you complete these 5 steps, OpenAI will automatically process all CVs! 🎉

