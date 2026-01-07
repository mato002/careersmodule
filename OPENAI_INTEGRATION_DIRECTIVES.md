# OpenAI Integration Directives

This document provides complete directives for integrating and using OpenAI in your Carrier Module application.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Installation & Setup](#installation--setup)
3. [Configuration](#configuration)
4. [Usage Examples](#usage-examples)
5. [API Integration Details](#api-integration-details)
6. [Testing the Integration](#testing-the-integration)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)
9. [Cost Management](#cost-management)

---

## Prerequisites

### 1. OpenAI Account & API Key
- Create an account at [OpenAI Platform](https://platform.openai.com/)
- Navigate to [API Keys](https://platform.openai.com/api-keys)
- Create a new secret key
- **IMPORTANT**: Save the key immediately - it won't be shown again
- Copy the key (format: `sk-...`)

### 2. System Requirements
- PHP 8.2 or higher
- Laravel 12.0 or higher
- Composer installed
- Internet connectivity for API calls
- Guzzle HTTP client (included with Laravel)

---

## Installation & Setup

### Step 1: Verify Dependencies

The application already uses Laravel's HTTP client (which includes Guzzle). No additional packages are required for basic OpenAI integration.

**Verify installation:**
```bash
composer show guzzlehttp/guzzle
```

If not present, install it:
```bash
composer require guzzlehttp/guzzle
```

### Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
# OpenAI Configuration
AI_PROVIDER=openai
OPENAI_API_KEY=sk-your-actual-api-key-here
AI_API_KEY=sk-your-actual-api-key-here

# Model Configuration
AI_MODEL=gpt-4o-mini

# Optional: Custom API URL (for OpenAI-compatible APIs)
# AI_API_URL=https://api.openai.com/v1

# Feature Toggles
AI_ENABLE_CV_PARSING=true
AI_ENABLE_AI_ANALYSIS=true
AI_ENABLE_AUTO_SIEVING=true

# AI Analysis Settings
AI_TEMPERATURE=0.3
AI_MAX_TOKENS=2000

# Confidence Thresholds
AI_MIN_CONFIDENCE_AUTO_PASS=0.85
AI_MIN_CONFIDENCE_AUTO_REJECT=0.80
```

### Step 3: Clear Configuration Cache

After updating `.env`, clear Laravel's config cache:

```bash
php artisan config:clear
php artisan config:cache
```

---

## Configuration

### Available Models

OpenAI offers several models. Recommended models for this application:

| Model | Use Case | Cost | Speed |
|-------|----------|------|-------|
| `gpt-4o-mini` | **Recommended** - CV analysis, summaries | Low | Fast |
| `gpt-4o` | Advanced analysis, complex reasoning | Medium | Medium |
| `gpt-4-turbo` | High-quality analysis | High | Medium |
| `gpt-3.5-turbo` | Basic tasks (legacy) | Low | Very Fast |

**Recommended**: Start with `gpt-4o-mini` for cost-effectiveness.

### Configuration File

The configuration is managed in `config/ai.php`. Key settings:

```php
'provider' => env('AI_PROVIDER', 'openai'),
'api_key' => env('AI_API_KEY', env('OPENAI_API_KEY')),
'model' => env('AI_MODEL', 'gpt-4o-mini'),
'temperature' => env('AI_TEMPERATURE', 0.3),
'max_tokens' => env('AI_MAX_TOKENS', 2000),
```

---

## Usage Examples

### 1. Basic CV Analysis

```php
use App\Services\AIAnalysisService;
use App\Models\JobApplication;

$application = JobApplication::find($id);
$aiService = new AIAnalysisService();

// Analyze CV
$analysis = $aiService->analyzeCv($application);

// Access results
$summary = $analysis['summary'];
$strengths = $analysis['strengths'];
$education = $analysis['education_highlights'];
$skills = $analysis['skills'];
$assessment = $analysis['assessment'];
```

### 2. Generate Profile Summary

```php
$summary = $aiService->generateProfileSummary($application);
// Returns: "John Doe is a software engineer with 5 years of experience..."
```

### 3. Match Skills to Job

```php
$skillMatch = $aiService->matchSkillsToJob($application);

// Access results
$matchingSkills = $skillMatch['matching_skills'];
$missingSkills = $skillMatch['missing_skills'];
$bonusSkills = $skillMatch['bonus_skills'];
$matchPercentage = $skillMatch['match_percentage'];
```

### 4. Analyze Application

```php
$analysis = $aiService->analyzeApplication($application);

// Access results
$matchScore = $analysis['match_score']; // 0-100
$matchingPoints = $analysis['matching_points'];
$missingRequirements = $analysis['missing_requirements'];
$recommendation = $analysis['recommendation']; // pass/reject/manual_review
$confidence = $analysis['confidence']; // 0-1
```

### 5. Using in Controllers

```php
namespace App\Http\Controllers\Admin;

use App\Services\AIAnalysisService;
use App\Models\JobApplication;

class JobApplicationController extends Controller
{
    public function analyzeWithAI($id)
    {
        $application = JobApplication::findOrFail($id);
        $aiService = new AIAnalysisService();
        
        try {
            $analysis = $aiService->analyzeCv($application);
            
            // Update application with AI summary
            $application->update([
                'ai_summary' => $analysis['summary'],
                'ai_details' => json_encode($analysis),
            ]);
            
            return redirect()->back()->with('success', 'AI analysis completed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'AI analysis failed: ' . $e->getMessage());
        }
    }
}
```

### 6. Queue-Based Processing (Recommended)

For better performance, use queue jobs:

```php
use App\Jobs\ProcessCvJob;
use App\Models\JobApplication;

// Dispatch job
ProcessCvJob::dispatch($application);

// Or in a controller
public function processApplication(JobApplication $application)
{
    ProcessCvJob::dispatch($application);
    return response()->json(['message' => 'Processing started']);
}
```

---

## API Integration Details

### Current Implementation

The application uses Laravel's HTTP client to call OpenAI's API directly:

**Endpoint**: `https://api.openai.com/v1/chat/completions`

**Method**: POST

**Headers**:
- `Authorization: Bearer {API_KEY}`
- `Content-Type: application/json`

**Request Body**:
```json
{
  "model": "gpt-4o-mini",
  "messages": [
    {
      "role": "system",
      "content": "You are an expert HR analyst..."
    },
    {
      "role": "user",
      "content": "{prompt}"
    }
  ],
  "temperature": 0.3,
  "max_tokens": 2000
}
```

### Response Format

```json
{
  "choices": [
    {
      "message": {
        "content": "{AI response}"
      }
    }
  ],
  "usage": {
    "prompt_tokens": 150,
    "completion_tokens": 200,
    "total_tokens": 350
  }
}
```

### Token Tracking

The application tracks token usage through `TokenService`:
- Estimates tokens before API calls
- Deducts tokens after successful calls
- Stores usage in database for billing/reporting

---

## Testing the Integration

### 1. Test API Connection

Create a test route or use Tinker:

```bash
php artisan tinker
```

```php
use App\Services\AIAnalysisService;
use App\Models\JobApplication;

$application = JobApplication::first();
$aiService = new AIAnalysisService();

// Test basic call
try {
    $result = $aiService->analyzeCv($application);
    dd($result);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### 2. Test with Simple Prompt

Create a test controller method:

```php
public function testOpenAI()
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('ai.api_key'),
        'Content-Type' => 'application/json',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'user', 'content' => 'Say hello']
        ],
        'max_tokens' => 50,
    ]);

    if ($response->successful()) {
        return response()->json($response->json());
    }

    return response()->json(['error' => $response->body()], 500);
}
```

### 3. Verify Configuration

```bash
php artisan tinker
```

```php
config('ai.provider'); // Should return 'openai'
config('ai.api_key'); // Should return your API key (masked)
config('ai.model'); // Should return 'gpt-4o-mini'
```

---

## Best Practices

### 1. Error Handling

Always wrap AI calls in try-catch blocks:

```php
try {
    $result = $aiService->analyzeCv($application);
} catch (\Exception $e) {
    Log::error('OpenAI API error', [
        'application_id' => $application->id,
        'error' => $e->getMessage()
    ]);
    
    // Use fallback
    $result = $this->generateFallbackSummary($application);
}
```

### 2. Rate Limiting

OpenAI has rate limits. Implement retry logic:

```php
use Illuminate\Support\Facades\Http;

$response = Http::retry(3, 1000)->withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
])->post($url, $data);
```

### 3. Async Processing

Always use queues for CV processing:

```php
// Good: Async
ProcessCvJob::dispatch($application);

// Bad: Synchronous (may timeout)
$aiService->analyzeCv($application);
```

### 4. Token Management

Monitor token usage:

```php
// Check token availability before processing
if (!$tokenService->hasEnoughTokens($companyId, $estimatedTokens)) {
    throw new \Exception('Insufficient tokens');
}
```

### 5. Caching

Cache AI responses to avoid redundant API calls:

```php
use Illuminate\Support\Facades\Cache;

$cacheKey = "ai_analysis_{$application->id}";
$analysis = Cache::remember($cacheKey, 3600, function() use ($application, $aiService) {
    return $aiService->analyzeCv($application);
});
```

### 6. Prompt Engineering

- Be specific in prompts
- Include context (job requirements, company info)
- Request structured output (JSON format)
- Set appropriate temperature (0.3 for consistent results)

---

## Troubleshooting

### Issue: "AI API key not configured"

**Solution**:
1. Check `.env` file has `OPENAI_API_KEY` or `AI_API_KEY`
2. Run `php artisan config:clear`
3. Verify key starts with `sk-`

### Issue: "OpenAI API error: 401 Unauthorized"

**Solution**:
- API key is invalid or expired
- Regenerate key at OpenAI platform
- Update `.env` file

### Issue: "OpenAI API error: 429 Too Many Requests"

**Solution**:
- Rate limit exceeded
- Implement retry with backoff
- Reduce concurrent requests
- Upgrade OpenAI plan if needed

### Issue: "OpenAI API error: 500 Internal Server Error"

**Solution**:
- Temporary OpenAI service issue
- Retry after a few minutes
- Check [OpenAI Status](https://status.openai.com/)

### Issue: Timeout Errors

**Solution**:
- Use queue jobs instead of synchronous calls
- Increase HTTP timeout:
```php
Http::timeout(120)->post(...)
```

### Issue: High Costs

**Solution**:
- Use `gpt-4o-mini` instead of `gpt-4`
- Reduce `max_tokens`
- Cache responses
- Implement token budgeting

### Issue: Poor Quality Responses

**Solution**:
- Improve prompts (more specific, better context)
- Adjust temperature (lower = more consistent)
- Use better model (`gpt-4o` instead of `gpt-4o-mini`)
- Add examples in prompts

---

## Cost Management

### Token Pricing (as of 2024)

| Model | Input (per 1M tokens) | Output (per 1M tokens) |
|-------|----------------------|------------------------|
| gpt-4o-mini | $0.15 | $0.60 |
| gpt-4o | $2.50 | $10.00 |
| gpt-4-turbo | $10.00 | $30.00 |

### Cost Estimation

Average CV analysis:
- Input: ~500 tokens
- Output: ~300 tokens
- Total: ~800 tokens per analysis

**With gpt-4o-mini**: ~$0.0006 per analysis

### Cost Optimization Tips

1. **Use gpt-4o-mini** for most tasks
2. **Cache results** to avoid duplicate calls
3. **Set max_tokens** appropriately (don't request more than needed)
4. **Batch similar requests** when possible
5. **Monitor usage** via OpenAI dashboard
6. **Set spending limits** in OpenAI account settings

### Setting Spending Limits

1. Go to [OpenAI Usage Limits](https://platform.openai.com/account/billing/limits)
2. Set hard limit (e.g., $100/month)
3. Set soft limit with email alerts

---

## Advanced Configuration

### Custom API Endpoint

For OpenAI-compatible APIs (e.g., Azure OpenAI):

```env
AI_API_URL=https://your-resource.openai.azure.com/openai/deployments/your-deployment
```

Update `AIAnalysisService.php` to use custom URL if needed.

### Streaming Responses

For real-time responses (not currently implemented):

```php
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
])->withBody(
    json_encode([
        'model' => 'gpt-4o-mini',
        'messages' => [...],
        'stream' => true,
    ]),
    'application/json'
)->post($url);
```

### Function Calling

For structured data extraction (future enhancement):

```php
'messages' => [...],
'functions' => [
    [
        'name' => 'extract_cv_data',
        'description' => 'Extract structured CV data',
        'parameters' => [...]
    ]
],
'function_call' => ['name' => 'extract_cv_data']
```

---

## Security Considerations

1. **Never commit API keys** to version control
2. **Use environment variables** for all secrets
3. **Rotate API keys** periodically
4. **Monitor usage** for suspicious activity
5. **Set rate limits** to prevent abuse
6. **Validate inputs** before sending to API
7. **Sanitize outputs** before displaying

---

## Support & Resources

- **OpenAI Documentation**: https://platform.openai.com/docs
- **API Reference**: https://platform.openai.com/docs/api-reference
- **Status Page**: https://status.openai.com/
- **Community**: https://community.openai.com/

---

## Quick Start Checklist

- [ ] Create OpenAI account
- [ ] Generate API key
- [ ] Add `OPENAI_API_KEY` to `.env`
- [ ] Set `AI_PROVIDER=openai` in `.env`
- [ ] Set `AI_MODEL=gpt-4o-mini` in `.env`
- [ ] Run `php artisan config:clear`
- [ ] Test connection with tinker or test route
- [ ] Configure queue worker: `php artisan queue:work`
- [ ] Test CV analysis on sample application
- [ ] Monitor token usage
- [ ] Set spending limits in OpenAI dashboard

---

## Next Steps

After successful integration:

1. **Test with real CVs** - Process sample job applications
2. **Fine-tune prompts** - Adjust prompts based on results
3. **Monitor costs** - Track usage in OpenAI dashboard
4. **Optimize performance** - Cache results, use queues
5. **Add error handling** - Implement fallbacks for failures
6. **Create admin UI** - Add buttons to trigger AI analysis
7. **Generate reports** - Track AI accuracy and usage

---

**Last Updated**: 2024
**Version**: 1.0


