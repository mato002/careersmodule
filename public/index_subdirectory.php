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

// Fix REQUEST_URI for subdirectory deployment
// When deployed in /careers subdirectory, preserve the /careers prefix
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // If the request doesn't start with /careers but should (we're in subdirectory)
    // and the original request was to /careers/*
    if (!str_starts_with($requestUri, '/careers')) {
        // Check if this is a request that should have /careers prefix
        // by checking the original request
        $originalUri = $_SERVER['REQUEST_URI'] ?? '';
        
        // If we're accessing via /careers path, add it back
        // This handles the case where .htaccess routes /careers/* to /Careers/public/*
        // and Laravel sees it as /* instead of /careers/*
        if (isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], '/careers')) {
            $_SERVER['REQUEST_URI'] = '/careers' . $requestUri;
        } elseif (isset($_SERVER['SCRIPT_NAME']) && str_contains($_SERVER['SCRIPT_NAME'], 'Careers')) {
            // If we're in the Careers directory, prepend /careers
            $_SERVER['REQUEST_URI'] = '/careers' . ($requestUri === '/' ? '' : $requestUri);
        }
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());







