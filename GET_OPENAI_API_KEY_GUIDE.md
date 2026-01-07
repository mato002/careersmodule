# Complete Guide: How to Get Your OpenAI API Key

This guide will walk you through getting your OpenAI API key step-by-step.

## Step 1: Create an OpenAI Account (If You Don't Have One)

1. **Go to OpenAI's website:**
   - Visit: https://platform.openai.com/
   - Click **"Sign up"** in the top right corner

2. **Sign up options:**
   - You can sign up with:
     - Google account
     - Microsoft account
     - Email address
   - Choose whichever is easiest for you

3. **Complete registration:**
   - Enter your email and create a password (if using email)
   - Verify your email address if required
   - Complete any verification steps

## Step 2: Add Payment Method (Required for API Access)

⚠️ **Important:** OpenAI requires a payment method to use the API, even for free tier usage.

1. **Go to Billing:**
   - After logging in, click on your profile icon (top right)
   - Select **"Billing"** or **"Settings"** → **"Billing"**

2. **Add payment method:**
   - Click **"Add payment method"**
   - Enter your credit/debit card details
   - OpenAI may charge a small amount (usually $5) to verify the card
   - Don't worry - you can set spending limits (see Step 5)

3. **Verify payment:**
   - Wait for payment verification (usually instant)
   - Your account is now ready for API usage

## Step 3: Get Your API Key

1. **Navigate to API Keys page:**
   - Go to: https://platform.openai.com/api-keys
   - Or click: **"API keys"** in the left sidebar

2. **Create a new secret key:**
   - Click the **"+ Create new secret key"** button
   - Give it a name (optional, e.g., "Carrier Module App")
   - Click **"Create secret key"**

3. **Copy your API key:**
   - ⚠️ **IMPORTANT:** Copy the key immediately!
   - The key will be shown only once
   - It starts with `sk-` followed by a long string
   - Example: `sk-proj-abc123xyz...`
   - **Save it somewhere safe** (password manager, secure note, etc.)

4. **If you missed copying it:**
   - You'll need to create a new key (old ones can't be viewed again)
   - Go back to API keys page and create another one

## Step 4: Add API Key to Your Project

1. **Open your `.env` file:**
   - Navigate to your project folder: `C:\xampp\htdocs\Carrier Module`
   - Open the `.env` file in a text editor (Notepad, VS Code, etc.)

2. **Find these lines:**
   ```env
   AI_PROVIDER=openai
   OPENAI_API_KEY=sk-your-actual-key-here
   AI_API_KEY=sk-your-actual-key-here
   ```

3. **Replace the placeholder:**
   - Replace `sk-your-actual-key-here` with your actual API key
   - Make sure to replace it in BOTH lines:
   ```env
   AI_PROVIDER=openai
   OPENAI_API_KEY=sk-proj-your-actual-key-here
   AI_API_KEY=sk-proj-your-actual-key-here
   ```

4. **Save the file:**
   - Save the `.env` file
   - Make sure there are no extra spaces or quotes around the key

5. **Clear Laravel config cache:**
   - Open PowerShell or Command Prompt
   - Navigate to your project folder
   - Run:
     ```bash
     php artisan config:clear
     ```

## Step 5: Set Up Spending Limits (Recommended)

To avoid unexpected charges, set spending limits:

1. **Go to Usage Limits:**
   - Visit: https://platform.openai.com/account/billing/limits
   - Or: Settings → Billing → Limits

2. **Set hard limit:**
   - Click **"Set hard limit"**
   - Enter your desired monthly limit (e.g., $10, $50, $100)
   - This prevents spending beyond your budget

3. **Set soft limit (optional):**
   - Set a lower soft limit to get notified before reaching hard limit
   - You'll receive email alerts

## Step 6: Test Your API Key

1. **Test via Laravel Tinker:**
   ```bash
   php artisan tinker
   ```

2. **Run these commands:**
   ```php
   config('ai.provider');  // Should return: "openai"
   config('ai.api_key');   // Should return your API key (starts with sk-)
   ```

3. **Test with a real API call:**
   ```php
   use App\Services\AIAnalysisService;
   use App\Models\JobApplication;
   
   $app = JobApplication::first();
   if ($app) {
       $ai = new AIAnalysisService();
       $result = $ai->analyzeCv($app);
       dd($result);
   } else {
       echo "No job applications found. Create one first.";
   }
   ```

4. **Exit tinker:**
   - Type `exit` and press Enter

## Troubleshooting

### Issue: "401 Unauthorized" Error
- **Cause:** Invalid or incorrect API key
- **Solution:**
  1. Double-check the API key in `.env` file
  2. Make sure there are no extra spaces or quotes
  3. Run `php artisan config:clear`
  4. Create a new API key if needed

### Issue: "429 Too Many Requests" Error
- **Cause:** Rate limit exceeded
- **Solution:**
  - Wait a few minutes
  - Check your usage in OpenAI dashboard
  - Consider upgrading your plan if needed

### Issue: "Insufficient quota" Error
- **Cause:** No payment method or insufficient credits
- **Solution:**
  1. Go to Billing settings
  2. Add/verify payment method
  3. Check account balance

### Issue: API Key Not Found
- **Cause:** `.env` file not updated or config cache
- **Solution:**
  1. Verify `.env` file has the correct key
  2. Run `php artisan config:clear`
  3. Make sure you're editing the correct `.env` file (not `.env.example`)

## Cost Information

OpenAI charges based on usage:
- **GPT-4o-mini** (recommended for this app): ~$0.15 per 1M input tokens, ~$0.60 per 1M output tokens
- **Typical CV analysis:** ~$0.001-0.01 per analysis (very cheap!)
- **Free tier:** $5 free credit when you first add payment method

## Security Best Practices

1. **Never commit `.env` to Git:**
   - Your `.env` file should already be in `.gitignore`
   - Never share your API key publicly

2. **Use different keys for different environments:**
   - Development key
   - Production key
   - Rotate keys periodically

3. **Monitor usage:**
   - Check OpenAI dashboard regularly
   - Set up usage alerts
   - Review billing statements

## Quick Reference Links

- **OpenAI Platform:** https://platform.openai.com/
- **API Keys Page:** https://platform.openai.com/api-keys
- **Usage Dashboard:** https://platform.openai.com/usage
- **Billing Settings:** https://platform.openai.com/account/billing
- **Documentation:** https://platform.openai.com/docs

## Summary Checklist

- [ ] Created OpenAI account
- [ ] Added payment method
- [ ] Generated API key
- [ ] Copied and saved API key securely
- [ ] Updated `.env` file with real API key
- [ ] Ran `php artisan config:clear`
- [ ] Tested API key with tinker
- [ ] Set spending limits
- [ ] Verified API works with test call

## Need Help?

If you encounter any issues:
1. Check the troubleshooting section above
2. Review OpenAI's documentation
3. Check your Laravel logs: `storage/logs/laravel.log`
4. Verify your `.env` file format is correct

---

**You're all set!** Your OpenAI integration should now be working. 🎉

