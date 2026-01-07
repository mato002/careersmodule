# Complete OpenAI Setup Guide - Make It Work!

This guide will help you complete the setup so OpenAI can actually process CVs and analyze job applications.

## ✅ What's Already Done

1. ✅ OpenAI API key configured in `.env`
2. ✅ AI configuration file exists (`config/ai.php`)
3. ✅ AI services implemented (`AIAnalysisService`, `CvParserService`)
4. ✅ Queue job created (`ProcessCvJob`)

## 🔧 What You Need to Do Now

### Step 1: Install CV Parsing Dependencies

The system needs libraries to extract text from PDF and DOCX files. Run these commands:

```bash
composer require smalot/pdfparser
composer require phpoffice/phpword
```

**Or install both at once:**
```bash
composer require smalot/pdfparser phpoffice/phpword
```

This will add:
- `smalot/pdfparser` - For parsing PDF CVs
- `phpoffice/phpword` - For parsing DOCX (Word) CVs

### Step 2: Verify Environment Configuration

Your `.env` file should have these settings (already added):

```env
# AI Configuration
AI_PROVIDER=openai
OPENAI_API_KEY=sk-proj-...
AI_API_KEY=sk-proj-...
AI_MODEL=gpt-4o-mini

# AI Feature Toggles
AI_ENABLE_CV_PARSING=true
AI_ENABLE_AI_ANALYSIS=true
AI_ENABLE_AUTO_SIEVING=true

# Queue Configuration (for async CV processing)
QUEUE_CONNECTION=database
```

### Step 3: Run Database Migrations

Make sure the `cv_parsed_data` table exists:

```bash
php artisan migrate
```

This creates the table that stores parsed CV information.

### Step 4: Create Queue Tables (If Not Exists)

The queue system needs database tables. Run:

```bash
php artisan queue:table
php artisan migrate
```

### Step 5: Start Queue Worker

**IMPORTANT:** The queue worker processes CVs in the background. You need to run it:

```bash
php artisan queue:work
```

**Keep this running** in a separate terminal window. This processes:
- CV parsing jobs
- AI analysis jobs
- Application processing

**For Production:** Use a process manager like Supervisor or run as a Windows service.

### Step 6: Clear Config Cache

After all changes:

```bash
php artisan config:clear
php artisan cache:clear
```

## 🧪 Testing the Setup

### Test 1: Verify Dependencies

```bash
php artisan tinker
```

```php
// Check if PDF parser is available
class_exists('\Smalot\PdfParser\Parser'); // Should return true

// Check if PhpWord is available
class_exists('\PhpOffice\PhpWord\IOFactory'); // Should return true

exit
```

### Test 2: Verify Configuration

```bash
php artisan tinker
```

```php
config('ai.provider'); // Should return: "openai"
config('ai.api_key'); // Should return your API key
config('ai.enable_cv_parsing'); // Should return: true
config('ai.enable_ai_analysis'); // Should return: true
config('queue.default'); // Should return: "database"
exit
```

### Test 3: Test with Real Application

If you have a job application with a CV:

```bash
php artisan tinker
```

```php
use App\Models\JobApplication;
use App\Services\CvParserService;
use App\Services\AIAnalysisService;

// Get an application with a CV
$app = JobApplication::whereNotNull('cv_path')->first();

if ($app) {
    // Test CV parsing
    $parser = new CvParserService();
    $parsed = $parser->parse($app);
    echo "CV Parsed: " . ($parsed ? "Yes" : "No") . "\n";
    
    // Test AI analysis
    $ai = new AIAnalysisService();
    $analysis = $ai->analyzeCv($app);
    echo "AI Analysis: " . (!empty($analysis) ? "Success" : "Failed") . "\n";
} else {
    echo "No applications with CVs found. Submit one first.\n";
}

exit
```

## 🚀 How It Works

### Automatic Processing Flow

1. **User submits job application** with CV file
2. **System queues `ProcessCvJob`** for background processing
3. **Queue worker picks up job** (if `php artisan queue:work` is running)
4. **CV Parser extracts text** from PDF/DOCX file
5. **AI Analysis Service** sends CV to OpenAI
6. **OpenAI analyzes CV** and returns summary
7. **Results stored** in database (`ai_summary`, `ai_details`, `cv_parsed_data`)

### Manual Processing (Admin Panel)

Admins can manually trigger processing from the job application detail page:
- **Parse CV Only** - Extracts structured data
- **Analyze with AI** - Generates AI summary
- **Process CV and AI** - Full processing

## 📋 Quick Checklist

- [ ] Install `smalot/pdfparser` via Composer
- [ ] Install `phpoffice/phpword` via Composer
- [ ] Verify `.env` has all AI settings
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan queue:table` (if needed)
- [ ] Run `php artisan migrate` again
- [ ] Start queue worker: `php artisan queue:work`
- [ ] Clear config: `php artisan config:clear`
- [ ] Test with a real application

## ⚠️ Common Issues

### Issue: "Class 'Smalot\PdfParser\Parser' not found"
**Solution:** Run `composer require smalot/pdfparser`

### Issue: "Class 'PhpOffice\PhpWord\IOFactory' not found"
**Solution:** Run `composer require phpoffice/phpword`

### Issue: Jobs not processing
**Solution:** Make sure `php artisan queue:work` is running

### Issue: "Table 'jobs' doesn't exist"
**Solution:** Run `php artisan queue:table && php artisan migrate`

### Issue: CV parsing returns empty
**Solution:** 
- Check file format is supported (PDF, DOCX, DOC, TXT)
- Verify file permissions
- Check logs: `storage/logs/laravel.log`

### Issue: AI analysis fails
**Solution:**
- Verify API key is correct
- Check internet connection
- Review OpenAI account quota
- Check logs for specific error

## 🎯 Next Steps After Setup

1. **Submit a test application** with a CV
2. **Check queue worker** is processing jobs
3. **View results** in admin panel (job application details)
4. **Monitor logs** for any errors: `storage/logs/laravel.log`

## 📊 Monitoring

### Check Queue Status

```bash
# See pending jobs
php artisan queue:work --once

# See failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Check Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Or on Windows PowerShell
Get-Content storage/logs/laravel.log -Wait -Tail 50
```

## 🎉 You're Done!

Once all steps are complete:
- ✅ CVs will be automatically parsed when applications are submitted
- ✅ AI analysis will run automatically
- ✅ Results will be available in the admin panel
- ✅ The system will work end-to-end!

---

**Need Help?** Check:
- `AI_FEATURES_SETUP.md` - Detailed feature documentation
- `OPENAI_INTEGRATION_DIRECTIVES.md` - Complete integration guide
- Laravel logs: `storage/logs/laravel.log`

