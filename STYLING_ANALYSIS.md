# Styling System Analysis

## What Styling System is Being Used

### 1. **Tailwind CSS v3.1.0**
   - **Configuration**: `tailwind.config.js`
   - **PostCSS**: `postcss.config.js` processes Tailwind directives
   - **Source**: `resources/css/app.css` contains `@tailwind` directives
   - **Custom Colors**: Fortress brand colors (teal, amber, emerald)
   - **Custom Animations**: fade-in, blob animations defined in app.css

### 2. **Vite (v7.0.7)**
   - **Purpose**: Asset bundling and compilation
   - **Configuration**: `vite.config.js`
   - **Input Files**: 
     - `resources/css/app.css` → Compiled to Tailwind CSS
     - `resources/js/app.js` → Bundled JavaScript with Alpine.js

### 3. **Alpine.js (v3.4.2)**
   - JavaScript framework for interactive components

### 4. **Additional Libraries**
   - **SweetAlert2**: For alert dialogs (loaded via CDN)
   - **@tailwindcss/forms**: Tailwind plugin for form styling

## Why the Styling is Broken

### **Root Cause: Base Path Mismatch**

The main issue is in `vite.config.js` line 13:

```javascript
base: process.env.VITE_APP_BASE || '/careers/',
```

**Problem**: 
1. Vite is configured with a hardcoded base path `/careers/`
2. When assets are built, they reference paths like `/careers/build/assets/app-CpnK-H4j.css`
3. If your application is NOT running at `/careers/` subdirectory, the browser can't find these assets
4. This causes 404 errors for CSS/JS files, resulting in completely unstyled pages

### **Secondary Issues**

1. **AppServiceProvider URL Forcing**
   - `AppServiceProvider.php` forces `URL::forceRootUrl()` to include `/careers`
   - This affects route generation but may conflict with asset paths

2. **Environment Variable Not Set**
   - `VITE_APP_BASE` environment variable is likely not set
   - Falls back to hardcoded `/careers/` which may not match your deployment

3. **Development vs Production**
   - In development: Need to run `npm run dev` for Vite dev server
   - In production: Need to run `npm run build` to compile assets
   - If dev server isn't running OR assets aren't built, styling breaks

## How to Fix

### Option 1: If Running at Root Domain (not /careers/)
```javascript
// vite.config.js
base: process.env.VITE_APP_BASE || undefined, // Remove hardcoded /careers/
```

### Option 2: If Running at /careers/ Subdirectory
```javascript
// vite.config.js - Keep as is, but ensure APP_URL matches
base: process.env.VITE_APP_BASE || '/careers/',
```

Then set in `.env`:
```
APP_URL=http://localhost/careers
VITE_APP_BASE=/careers/
```

### Option 3: Dynamic Base Path Detection
```javascript
// vite.config.js
base: process.env.VITE_APP_BASE || (process.env.APP_URL?.includes('/careers') ? '/careers/' : '/'),
```

## Current Status

- ✅ Tailwind CSS is properly configured
- ✅ PostCSS is set up correctly
- ✅ Assets are being built (files exist in `public/build/assets/`)
- ❌ **Base path mismatch causing 404 errors on CSS/JS files**
- ❌ Assets may not be loading due to incorrect paths

## Verification Steps

1. Check browser console for 404 errors on CSS/JS files
2. Check Network tab to see what paths are being requested
3. Verify your actual deployment path (root `/` or `/careers/`)
4. Ensure `npm run build` was run after any vite.config.js changes
5. Clear browser cache and hard refresh (Ctrl+F5)




