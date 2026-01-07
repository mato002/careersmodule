# OpenAI Quick Setup Guide

## 5-Minute Setup

### Step 1: Get Your API Key
1. Go to https://platform.openai.com/api-keys
2. Click "Create new secret key"
3. Copy the key (starts with `sk-`)

### Step 2: Add to .env File

Open your `.env` file and add:

```env
AI_PROVIDER=openai
OPENAI_API_KEY=sk-your-key-here
AI_API_KEY=sk-your-key-here
AI_MODEL=gpt-4o-mini
```

### Step 3: Clear Config Cache

```bash
php artisan config:clear
```

### Step 4: Test It

```bash
php artisan tinker
```

Then run:
```php
config('ai.provider'); // Should return 'openai'
config('ai.api_key'); // Should return your key
```

### Step 5: Test with Real Application

```php
use App\Services\AIAnalysisService;
use App\Models\JobApplication;

$app = JobApplication::first();
$ai = new AIAnalysisService();
$result = $ai->analyzeCv($app);
dd($result);
```

## That's It! 🎉

Your OpenAI integration is ready to use.

## Common Issues

**"API key not configured"**
- Check `.env` file has `OPENAI_API_KEY`
- Run `php artisan config:clear`

**"401 Unauthorized"**
- API key is wrong
- Get a new key from OpenAI platform

**"429 Too Many Requests"**
- You've hit rate limits
- Wait a few minutes or upgrade plan

## Need More Help?

See `OPENAI_INTEGRATION_DIRECTIVES.md` for complete documentation.


