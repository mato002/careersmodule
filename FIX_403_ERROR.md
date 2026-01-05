# Fixing 403 Forbidden Error for /careers

## Common Causes of 403 Error

1. **Directory Permissions** - The `Careers` or `Careers/public` directory doesn't have proper permissions
2. **Incorrect .htaccess Routing** - The rewrite rules aren't working correctly
3. **Missing index.php** - The Laravel entry point isn't accessible
4. **Directory Listing Disabled** - Options -Indexes might be blocking access

## Step-by-Step Fix

### Step 1: Check Directory Permissions

In cPanel File Manager, check permissions for:
- `public_html/Careers/` → Should be **755**
- `public_html/Careers/public/` → Should be **755**
- `public_html/Careers/public/index.php` → Should be **644**

To fix permissions:
1. Right-click on `Careers` folder → **Change Permissions**
2. Set to **755** (or **0755**)
3. Do the same for `Careers/public/`

### Step 2: Verify File Structure

Make sure the structure is:
```
public_html/
├── .htaccess (with routing rules)
├── Careers/
│   ├── public/
│   │   ├── .htaccess
│   │   ├── index.php
│   │   └── ...
│   └── ...
```

### Step 3: Update .htaccess in public_html

The routing might need adjustment. Use the corrected version from `public_html_htaccess_fixed.txt`.

**Key changes:**
- Simplified rewrite rule for better path resolution
- Added exclusion for /careers in trailing slash removal
- Better handling of static files

### Step 4: Alternative Approach - Direct Path

If the above doesn't work, try this alternative `.htaccess` rule:

```apache
# Alternative routing for /careers
RewriteCond %{REQUEST_URI} ^/careers
RewriteRule ^careers/?(.*)$ /Careers/public/$1 [L]
```

### Step 5: Check if Careers/public/index.php exists

Verify the file exists:
- Path: `public_html/Careers/public/index.php`
- Permissions: **644**
- File should contain Laravel bootstrap code

### Step 6: Test Direct Access

Try accessing directly:
- `https://pradytec.com/Careers/public/` (should work if permissions are correct)
- If this works, the issue is with `.htaccess` routing
- If this also gives 403, it's a permissions issue

### Step 7: Check .htaccess in Careers/public

Make sure `public_html/Careers/public/.htaccess` exists and has correct content (should already be there from Laravel).

### Step 8: Enable Directory Listing Temporarily (for testing)

Temporarily change in `public_html/.htaccess`:
```apache
Options +Indexes  # Change from -Indexes to +Indexes temporarily
```

Then visit `https://pradytec.com/Careers/` - you should see directory listing if permissions are correct.

**Remember to change back to `Options -Indexes` after testing!**

