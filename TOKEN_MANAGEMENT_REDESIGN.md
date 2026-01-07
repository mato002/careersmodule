# Token Management System Redesign - Multi-Tenant Solution

## Problem Statement
The previous token management system had critical flaws:
1. **Token deduction failed if no allocation existed** - Usage wasn't tracked
2. **Token checks blocked API calls** - Companies couldn't use AI if tokens weren't pre-allocated
3. **No visibility into usage** - Companies couldn't see how much they've used
4. **Not suitable for multi-tenant** - Each company needs independent tracking and billing

## New Solution: Always-Track System

### Key Principles
1. **Always Track Usage** - Every AI operation is logged, regardless of allocation status
2. **Never Block Operations** - AI calls proceed even without tokens (for postpaid billing)
3. **Auto-Create Allocations** - System automatically creates "tracking-only" allocations
4. **Multi-Tenant Ready** - Each company has independent usage tracking and billing

### How It Works

#### 1. Token Allocation Types

**Active Allocation (Prepaid)**
- Company has purchased tokens
- Tokens are deducted from allocation
- Operations blocked if insufficient tokens (optional - can be disabled)

**Tracking-Only Allocation (Postpaid)**
- Auto-created when no active allocation exists
- Unlimited tokens for tracking purposes
- Usage is logged but not deducted
- Perfect for postpaid billing models

#### 2. Usage Tracking Flow

```
AI Operation Requested
    ↓
Get or Create Allocation (auto-creates tracking-only if needed)
    ↓
Make AI API Call (never blocked)
    ↓
Extract Usage Data from Response
    ↓
ALWAYS Log Usage (TokenUsageLog)
    ↓
Try to Deduct from Allocation (if active, not tracking-only)
    ↓
Update Monthly Summary
    ↓
Return Success
```

#### 3. Database Changes

**Migration: `2025_01_08_000001_add_tracking_only_status_to_company_token_allocations.php`**
- Added `'tracking_only'` status to `company_token_allocations.status` enum
- Allows allocations that track usage without limiting operations

#### 4. Code Changes

**`app/Services/TokenService.php`**
- `deductTokens()` - Now ALWAYS logs usage, even if deduction fails
- `getOrCreateAllocation()` - Auto-creates tracking-only allocation if none exists
- Removed blocking behavior - operations proceed regardless of token balance

**`app/Services/AIAnalysisService.php`**
- Removed blocking token checks - calls proceed even without tokens
- Token checks now only log warnings, don't throw exceptions
- Usage is always tracked after successful API calls

**`app/Models/Company.php`**
- `activeTokenAllocation()` - Now includes 'tracking_only' status in query
- Prefers 'active' allocations over 'tracking_only'

### Benefits

1. **Complete Usage Visibility**
   - Every AI operation is logged in `token_usage_logs`
   - Companies can see exactly how much they've used
   - Perfect for billing and reporting

2. **Flexible Billing Models**
   - **Prepaid**: Allocate tokens, deduct as used
   - **Postpaid**: Track usage, bill monthly based on logs
   - **Hybrid**: Combine both approaches

3. **No Service Interruption**
   - AI operations never fail due to missing allocations
   - System automatically creates tracking allocations
   - Companies can use AI immediately after signup

4. **Multi-Tenant Ready**
   - Each company has independent tracking
   - Usage logs include `company_id` for filtering
   - Monthly summaries per company
   - Easy to generate invoices per company

### Usage Logs Structure

Every AI operation creates a `TokenUsageLog` entry with:
- `company_id` - Which company used the tokens
- `job_application_id` - Which application triggered the operation
- `operation_type` - cv_parse, cv_analyze, scoring, decision
- `tokens_used` - Total tokens consumed
- `input_tokens` - Prompt tokens
- `output_tokens` - Response tokens
- `model_used` - Which AI model (gpt-4o-mini, etc.)
- `cost_per_token` - Cost at time of use
- `total_cost` - Total cost for this operation
- `metadata` - Additional info (prompt length, response length, etc.)

### Billing Workflow

**For Postpaid Companies:**
1. Company uses AI features (tracking-only allocation)
2. All usage logged in `token_usage_logs`
3. Monthly summary generated in `company_token_usage_summaries`
4. Invoice generated from usage logs
5. Company pays based on actual usage

**For Prepaid Companies:**
1. Company purchases token package
2. Tokens allocated to company
3. Usage deducted from allocation
4. When low, company purchases more tokens
5. Optional: Auto-top-up when balance is low

### Migration Steps

1. **Run Migration**
   ```bash
   php artisan migrate
   ```

2. **Existing Companies**
   - Existing companies will get tracking-only allocations on first AI operation
   - Or manually allocate tokens via Token Management page

3. **Test Token Tracking**
   ```bash
   php artisan test:add-applications --count=3 --process
   ```

### Monitoring & Reporting

**Token Management Page** (`/admin/tokens`)
- Shows current balance (or "Tracking Only" for postpaid)
- Displays usage logs with application links
- Monthly usage statistics
- Cost breakdown by operation type

**Usage Reports**
- Total tokens used per company
- Cost per company
- Usage by operation type
- Usage by date range
- Application-level usage tracking

### Next Steps

1. **Run the migration** to add `tracking_only` status
2. **Test with applications** to verify tracking works
3. **Review usage logs** to ensure all operations are logged
4. **Set up billing** based on usage logs
5. **Configure alerts** for high usage or low balances (prepaid)

### Important Notes

- **Token checks are now warnings, not blockers** - AI operations proceed regardless
- **Usage is ALWAYS logged** - Even if deduction fails, usage is recorded
- **Tracking-only allocations are unlimited** - They exist only for logging purposes
- **Companies can switch between prepaid and postpaid** - Just change allocation status

This redesign ensures that in a multi-tenant system, every company's usage is tracked accurately, enabling proper billing and cost management.

