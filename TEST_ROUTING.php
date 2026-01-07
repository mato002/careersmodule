<?php
/**
 * Temporary test file to debug routing
 * Place this in: public_html/Careers/public/TEST_ROUTING.php
 * Visit: https://pradytec.com/careers/TEST_ROUTING.php
 * 
 * DELETE THIS FILE AFTER DEBUGGING!
 */

header('Content-Type: text/plain');

echo "=== ROUTING DEBUG INFO ===\n\n";

echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";

echo "\n=== TESTING ROUTES ===\n\n";

// Test if we can access Laravel
$laravelIndex = __DIR__ . '/index.php';
if (file_exists($laravelIndex)) {
    echo "✓ Laravel index.php found\n";
} else {
    echo "✗ Laravel index.php NOT found\n";
}

// Test .htaccess
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo "✓ .htaccess found\n";
} else {
    echo "✗ .htaccess NOT found\n";
}

echo "\n=== WHAT TO CHECK ===\n";
echo "1. REQUEST_URI should show the full path including /careers\n";
echo "2. If REQUEST_URI shows /senior-software-developer (without /careers),\n";
echo "   then the index.php fix needs to be applied.\n";
echo "3. If REQUEST_URI shows /careers/senior-software-developer,\n";
echo "   then the issue is elsewhere (maybe route definition or model binding).\n";





