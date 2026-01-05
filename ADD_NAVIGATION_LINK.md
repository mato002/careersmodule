# Adding Careers Link to pradytec.com Navigation

## Overview
You need to add a "Careers" link to your existing website's navigation menu in `header.php`.

## Step-by-Step Instructions

### 1. Locate the Navigation Menu
- Open `public_html/header.php` in cPanel File Manager
- Find the navigation menu section in the `<ul>` inside `<td class="hlist">`

### 2. Add the Careers Link

**Find this section in your `header.php`:**
```php
<li><a href="/">Home</a></li>
<li><a href="about">About Us</a></li>
<li><a href="services">Services</a></li>
<li><a href="/#clients" class="intl1">Our Clients</a></li>
<li><a href="/#contact" class="intl2">Contact Us</a></li>
```

**Add the Careers link after "Services" and before "Our Clients":**
```php
<li><a href="/">Home</a></li>
<li><a href="about">About Us</a></li>
<li><a href="services">Services</a></li>
<li><a href="/careers">Careers</a></li>  <!-- ADD THIS LINE -->
<li><a href="/#clients" class="intl1">Our Clients</a></li>
<li><a href="/#contact" class="intl2">Contact Us</a></li>
```

**Complete navigation section should look like this:**
```php
<td class="hlist"><ul>
	<li class="smd"><span style='font-size:30px;color:#fff;' onclick="navbar('0')">X</span></li>
	<li><a href="/">Home</a></li>
	<li><a href="about">About Us</a></li>
	<li><a href="services">Services</a></li>
	<li><a href="/careers">Careers</a></li>  <!-- NEW LINE ADDED HERE -->
	<li><a href="/#clients" class="intl1">Our Clients</a></li>
	<li><a href="/#contact" class="intl2">Contact Us</a></li>
	<li class="smd"><a href="https://bulk.pradytec.com/">Bulk SMS Portal</a></li>
	<li  class="smd"><a href="https://bulk.pradytec.com/api/docs/mode/php/index.html">Bulk SMS API</a></li>
	<li  class="smd"><hr color="#fff"><a href="tel:+254722295194"><i class="fa fa-phone"></i> &nbsp; +254 722 295 194</a></li>
</ul></td>
```

#### Option B: If your navigation uses `<div>` with classes:
```php
<a href="/careers" class="nav-link">Careers</a>
```

#### Option C: If your navigation is in a Bootstrap navbar:
```php
<li class="nav-item">
    <a class="nav-link" href="/careers">Careers</a>
</li>
```

### 3. Example: Complete Navigation Section

Here's what a typical navigation might look like with the Careers link added:

```php
<nav class="navbar">
    <ul class="navbar-nav">
        <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
        <li class="nav-item"><a href="/careers" class="nav-link">Careers</a></li>  <!-- ADD THIS -->
        <li class="nav-item"><a href="#clients" class="nav-link">Our Clients</a></li>
        <li class="nav-item"><a href="#contact" class="nav-link">Contact Us</a></li>
    </ul>
</nav>
```

### 4. Styling (Optional)

If you want the Careers link to match your existing navigation style, you can add the same classes. For example, if your links have a hover effect:

```php
<li><a href="/careers" class="nav-link hover-effect">Careers</a></li>
```

### 5. Test the Link

After adding the link:
1. Save `header.php`
2. Visit `https://pradytec.com`
3. Click the "Careers" link
4. It should take you to `https://pradytec.com/careers` (the Laravel Career Module)

## Troubleshooting

### Link doesn't work (404 error)
- Make sure you added `/careers` (with leading slash)
- Verify the `.htaccess` file in `public_html/` has the routing rules (see `DEPLOYMENT_SUBDIRECTORY.md`)

### Link works but shows wrong page
- Check that the Laravel app is properly configured
- Verify `APP_URL` in `.env` is set to `https://pradytec.com/careers`

### Styling doesn't match
- Copy the same classes from other navigation links
- Check if there's a CSS file that needs updating

## Quick Reference

**Link URL:** `/careers`  
**Full URL:** `https://pradytec.com/careers`  
**Target:** Laravel Career Module homepage

