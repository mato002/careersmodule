<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// DEBUG: Log what we receive (remove after fixing)
if (isset($_SERVER['REQUEST_URI'])) {
    error_log('=== DEBUG INFO ===');
    error_log('REQUEST_URI (before fix): ' . $_SERVER['REQUEST_URI']);
    error_log('SCRIPT_NAME: ' . ($_SERVER['SCRIPT_NAME'] ?? 'not set'));
    error_log('PATH_INFO: ' . ($_SERVER['PATH_INFO'] ?? 'not set'));
}

// Fix REQUEST_URI for subdirectory deployment
// When deployed in /careers subdirectory, ensure REQUEST_URI includes /careers prefix
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // If the URI doesn't start with /careers, prepend it
    // This handles the case where .htaccess routes /careers/* to this directory
    // and the REQUEST_URI loses the /careers prefix
    if (!str_starts_with($requestUri, '/careers')) {
        // Prepend /careers to the URI (unless it's already /)
        if ($requestUri === '/' || $requestUri === '') {
            $_SERVER['REQUEST_URI'] = '/careers';
        } else {
            $_SERVER['REQUEST_URI'] = '/careers' . $requestUri;
        }
    }
    
    // DEBUG: Log what we're sending to Laravel
    error_log('REQUEST_URI (after fix): ' . $_SERVER['REQUEST_URI']);
    error_log('==================');
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());







