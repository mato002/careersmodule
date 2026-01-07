# How to Allocate Tokens

## Quick Start (Direct Allocation)

**For testing and development, you can directly allocate tokens without creating a purchase:**

1. **Go to Token Management:**
   - Navigate to: **Admin Panel → Token Management**
   - URL: `/admin/tokens`

2. **Click "Allocate Tokens" button:**
   - Located in the "Quick Actions" card (top right)
   - Or scroll down to the "Token Allocations" section

3. **Fill in the form:**
   - **Company:** Your company (auto-selected)
   - **Token Amount:** Enter any amount (e.g., `100000` for 100,000 tokens)
   - **Token Purchase:** Leave empty (for manual allocation)
   - **Expires At:** Optional - leave empty for no expiration
   - **Notes:** Optional - e.g., "Initial allocation for testing"

4. **Click "Allocate Tokens"**

5. **Verify:**
   - Check the "Token Balance" card - should show your allocated tokens
   - Check "Token Allocations" table - should show your new allocation

## Full Process (With Purchase Record)

**For production and proper tracking, create a purchase first:**

### Step 1: Create Token Purchase

1. **Go to Purchases:**
   - Navigate to: **Admin Panel → Token Management → Manage Purchases**
   - URL: `/admin/tokens/purchases`
   - Or click "Manage Purchases" button

2. **Click "New Purchase" button**

3. **Fill in purchase details:**
   - **Purchase Date:** Today's date
   - **Total Tokens:** e.g., `1000000` (1 million tokens)
   - **Cost per Token:** e.g., `0.00003` (OpenAI's approximate cost)
   - **Provider:** Select "OpenAI"
   - **Expires At:** Optional
   - **Notes:** Optional - e.g., "OpenAI API credits"

4. **Click "Create Purchase"**

### Step 2: Allocate from Purchase

1. **Go back to Token Management:**
   - Navigate to: `/admin/tokens`

2. **Click "Allocate Tokens"**

3. **Fill in allocation form:**
   - **Company:** Your company
   - **Token Amount:** Amount to allocate (e.g., `100000`)
   - **Token Purchase:** Select the purchase you just created
   - **Expires At:** Optional
   - **Notes:** Optional

4. **Click "Allocate Tokens"**

## Recommended Token Amounts

### For Testing:
- **Small:** 10,000 tokens (enough for ~10-20 CV analyses)
- **Medium:** 100,000 tokens (enough for ~100-200 CV analyses)
- **Large:** 1,000,000 tokens (enough for ~1,000-2,000 CV analyses)

### Token Usage Estimates:
- **CV Analysis:** ~4,000-5,000 tokens per analysis
- **Application Scoring:** ~4,500-5,000 tokens per scoring
- **Re-sieving:** ~10,000 tokens per re-sieving (includes both analysis and scoring)

## Cost Reference (OpenAI)

- **GPT-4o-mini:** ~$0.00003 per token (input + output)
- **100,000 tokens:** ~$3.00
- **1,000,000 tokens:** ~$30.00

*Note: These are approximate costs. Check OpenAI's current pricing.*

## Troubleshooting

### "No allocations yet" message:
- You need to allocate tokens first
- Follow the "Quick Start" steps above

### "Insufficient tokens" error:
- Check your token balance on the Token Management page
- Allocate more tokens if needed

### Can't see "Allocate Tokens" button:
- Make sure you're logged in as an admin
- Check that you're on the Token Management page (`/admin/tokens`)

## After Allocation

Once tokens are allocated:
1. ✅ API calls will proceed to OpenAI
2. ✅ Token usage will be tracked
3. ✅ Balance will decrease as you use AI features
4. ✅ Usage logs will show all operations

## Next Steps

After allocating tokens:
1. Try re-sieving an application
2. Check OpenAI dashboard - "Last used" should update
3. Check Token Management - usage should be logged
4. Monitor balance to see tokens being used

---

**Quick Tip:** For development/testing, use direct allocation (Option 1). For production, use the full process with purchase records (Option 2) for better tracking and accounting.

