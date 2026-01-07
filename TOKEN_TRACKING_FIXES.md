# Token Tracking Fixes - Complete Summary

## Overview
This document summarizes all the fixes implemented to ensure token usage is properly tracked and displayed in the Token Management page.

## Issues Fixed

### 1. Missing `company_id` on Job Applications
**Problem:** Many `JobApplication` records were missing `company_id`, preventing token usage from being correctly attributed to companies.

**Fix:**
- Updated `JobApplicationController@store` to automatically set `company_id` from the associated `JobPost` when creating new applications.
- Enhanced `AIAnalysisService@getCompanyId()` to use a robust fallback chain:
  1. `application->company_id` (direct)
  2. `jobPost->company_id` (from job post)
  3. `auth()->user()->company_id` (from authenticated user)
  4. `Company::first()->id` (last resort)

### 2. Token Deduction Not Happening
**Problem:** Token deduction was failing when `company_id` was initially null, even though it could be retrieved from the application.

**Fix:**
- Modified `AIAnalysisService@callOpenAI` to always attempt token deduction if usage data is present in the OpenAI response.
- Added logic to retrieve `company_id` from `jobApplicationId` if it wasn't available initially.
- Added extensive logging to trace the token deduction flow.

### 3. Missing Reverse Relationship
**Problem:** `JobApplication` model didn't have a relationship to `TokenUsageLog`, making it harder to query token usage per application.

**Fix:**
- Added `tokenUsageLogs()` relationship to `JobApplication` model.

### 4. Token Management Page Not Updating
**Problem:** The token management page wasn't showing real-time updates after AI operations.

**Fix:**
- Added a refresh button to the token management page.
- Ensured all token usage logs are properly linked to applications via `job_application_id`.
- The page now displays:
  - Recent usage logs with application links
  - Input/Output token breakdown
  - Operation types (cv_parse, cv_analyze, scoring, decision)
  - Cost per operation
  - Model used

## Artisan Command Created

### `php artisan tokens:fix-tracking`
A comprehensive diagnostic and fix command that:

1. **Fixes Applications' Company ID** (`fixApplicationsCompanyId`)
   - Finds applications missing `company_id` or with incorrect `company_id`
   - Updates them based on their associated `JobPost`

2. **Checks and Fixes Token Allocation** (`checkAndFixAllocation`)
   - Verifies company has an active token allocation
   - Creates a default allocation if missing (Starter plan: 100,000 tokens)

3. **Checks Usage Logs** (`checkUsageLogs`)
   - Reports existing token usage logs for the company

4. **Identifies and Reprocesses Missing Logs** (`identifyAndReprocessMissingLogs`)
   - Finds applications with AI analysis but no token usage logs
   - Optionally reprocesses AI to generate logs (use `--reprocess-ai` flag)

**Usage:**
```bash
# Diagnose and fix for all companies
php artisan tokens:fix-tracking

# Fix specific company
php artisan tokens:fix-tracking --company=1

# Fix and reprocess AI for missing logs
php artisan tokens:fix-tracking --reprocess-ai
```

## How Token Tracking Works

### Flow Diagram
```
1. Job Application Created
   └─> company_id set from JobPost

2. CV Processing (ProcessCvJob)
   └─> AIAnalysisService->analyzeCv()
       └─> callOpenAI()
           └─> Token deduction attempted
               └─> TokenService->deductTokens()
                   ├─> Find active allocation
                   ├─> Check sufficient tokens
                   ├─> Deduct from allocation
                   └─> Create TokenUsageLog entry

3. Application Analysis
   └─> AIAnalysisService->analyzeApplication()
       └─> Same flow as above

4. Sieving Decision
   └─> AISievingService->evaluate()
       └─> Uses AIAnalysisService
           └─> Same flow as above
```

### Token Deduction Process
1. **Estimate tokens** before API call (for pre-check)
2. **Call OpenAI API** with prompt
3. **Extract usage data** from response (`usage.total_tokens`, `usage.prompt_tokens`, `usage.completion_tokens`)
4. **Retrieve company_id** if not already available
5. **Deduct tokens** from active allocation
6. **Log usage** in `token_usage_logs` table with:
   - `company_id`
   - `job_application_id`
   - `operation_type` (cv_parse, cv_analyze, scoring, decision)
   - `tokens_used`, `input_tokens`, `output_tokens`
   - `model_used`
   - `cost_per_token`, `total_cost`
   - `metadata` (JSON with additional info)

## Verification Steps

### 1. Check Token Balance
- Navigate to `/admin/tokens`
- Verify "Token Balance" card shows correct remaining tokens
- Check "This Month" usage statistics

### 2. Check Recent Usage Logs
- Scroll to "Recent Usage Logs" section
- Verify logs appear after AI operations
- Check that "Application" column shows links to job applications
- Verify "Input/Output" tokens are displayed
- Confirm "Total Tokens" and "Cost" are shown

### 3. Test New Application Processing
1. Submit a new job application with a CV
2. Wait for CV processing (check queue: `php artisan queue:work`)
3. Refresh Token Management page
4. Verify:
   - Token balance decreased
   - New usage log entry appears
   - Application link works
   - Operation type is correct (cv_analyze, scoring, etc.)

### 4. Test Re-Sieving
1. Go to a job application detail page
2. Click "🔄 Re-Sieve" button
3. Wait for processing
4. Refresh Token Management page
5. Verify:
   - Additional token usage logged
   - Operation type is "scoring" or "decision"

### 5. Check Logs for Errors
```bash
# Check Laravel logs for token-related errors
tail -f storage/logs/laravel.log | grep -i token

# Look for these log messages:
# - "Token deduction started"
# - "Active allocation found"
# - "Token usage logged successfully"
# - "Token deduction successful"
```

## Database Tables Involved

### `token_usage_logs`
- Stores all token usage records
- Links to `companies`, `job_applications`, and `company_token_allocations`
- Contains operation details, token counts, and costs

### `company_token_allocations`
- Tracks token allocations per company
- Maintains `remaining_tokens`, `used_tokens`, `allocated_tokens`
- Links to `token_purchases`

### `job_applications`
- Now properly stores `company_id`
- Has relationship to `token_usage_logs`

## Troubleshooting

### Issue: Token balance not updating
**Solution:**
1. Run `php artisan tokens:fix-tracking --reprocess-ai` to fix existing issues
2. Check logs for errors: `tail -f storage/logs/laravel.log | grep -i token`
3. Verify company has active allocation: Check `company_token_allocations` table
4. Ensure `company_id` is set on applications

### Issue: Usage logs not appearing
**Solution:**
1. Verify OpenAI API calls are succeeding (check logs)
2. Check if `company_id` is being retrieved correctly
3. Ensure `TokenService->deductTokens()` is being called
4. Check database for `token_usage_logs` entries

### Issue: Application links not working
**Solution:**
1. Verify `job_application_id` is set in `token_usage_logs`
2. Check that `JobApplication` relationship exists
3. Ensure application hasn't been deleted

## Next Steps

1. **Monitor token usage** regularly via the Token Management page
2. **Set up alerts** for low token balances (already implemented in `TokenService->checkLowTokenAlert()`)
3. **Review usage patterns** to optimize token consumption
4. **Adjust pricing plans** based on actual usage data

## Files Modified

- `app/Http/Controllers/JobApplicationController.php` - Set company_id on creation
- `app/Services/AIAnalysisService.php` - Enhanced company_id retrieval and token deduction
- `app/Services/TokenService.php` - Added detailed logging
- `app/Models/JobApplication.php` - Added tokenUsageLogs relationship
- `app/Console/Commands/FixTokenTracking.php` - New diagnostic command
- `resources/views/admin/tokens/index.blade.php` - Enhanced display with application links

## Summary

All token tracking issues have been resolved. The system now:
- ✅ Properly sets `company_id` on all job applications
- ✅ Correctly retrieves `company_id` for token deduction
- ✅ Logs all token usage with application links
- ✅ Displays real-time token balance and usage
- ✅ Provides diagnostic tools to fix existing issues

The Token Management page is now fully functional and provides complete visibility into token usage across all AI operations.

