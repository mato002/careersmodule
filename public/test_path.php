<?php
/**
 * Diagnostic file - upload to public_html/Careers/public/test_path.php
 * Visit: https://pradytec.com/careers/test_path.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== PATH DIAGNOSTICS ===\n\n";

echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";

echo "\n=== TESTING LARAVEL REQUEST ===\n\n";

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$request = \Illuminate\Http\Request::capture();

echo "Laravel Path Info: " . $request->getPathInfo() . "\n";
echo "Laravel Full URL: " . $request->fullUrl() . "\n";
echo "Laravel URL: " . $request->url() . "\n";

echo "\n=== TESTING ROUTE MATCH ===\n\n";

try {
    $route = \Illuminate\Support\Facades\Route::getRoutes()->match($request);
    echo "Route Matched: " . $route->getName() . "\n";
    echo "Route URI: " . $route->uri() . "\n";
} catch (\Exception $e) {
    echo "Route NOT Matched: " . $e->getMessage() . "\n";
    echo "\nAvailable routes:\n";
    foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
        if (str_contains($route->uri(), 'careers')) {
            echo "  - " . $route->uri() . " (" . $route->getName() . ")\n";
        }
    }
}





