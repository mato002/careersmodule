<?php
/**
 * Test file to verify subdirectory routing is working
 * Place this in: public_html/Careers/public/test_subdirectory.php
 * Then visit: https://pradytec.com/careers/test_subdirectory.php
 */

echo "<h1>Laravel Subdirectory Test</h1>";
echo "<p>If you can see this, the routing is working!</p>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Test if we can access Laravel
$laravelIndex = __DIR__ . '/index.php';
if (file_exists($laravelIndex)) {
    echo "<p style='color: green;'>✓ Laravel index.php found</p>";
} else {
    echo "<p style='color: red;'>✗ Laravel index.php NOT found</p>";
}

// Test .htaccess
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If you see this page, routing is working</li>";
echo "<li>Now test: <a href='/careers'>https://pradytec.com/careers</a></li>";
echo "<li>Delete this file after testing</li>";
echo "</ol>";


