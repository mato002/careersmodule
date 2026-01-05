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

// Bootstrap Laravel first
$app = require_once __DIR__.'/../bootstrap/app.php';

// Capture the request
$request = Request::capture();

// Handle subdirectory deployment
// If app is deployed in /careers subdirectory, detect and strip the prefix
$path = $request->getPathInfo();
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';

// Detect if we're in a subdirectory deployment
// Check if script is in a Careers/careers directory
$isSubdirectory = (
    str_contains($scriptName, '/Careers/') || 
    str_contains($scriptName, '/careers/') ||
    str_contains($scriptFilename, '/Careers/') ||
    str_contains($scriptFilename, '/careers/') ||
    (str_starts_with($requestUri, '/careers/') && !str_starts_with($path, '/careers/'))
);

// If in subdirectory and path starts with /careers, strip it
if ($isSubdirectory && str_starts_with($path, '/careers')) {
    $newPath = substr($path, 8); // Remove '/careers' (8 chars)
    if ($newPath === '' || $newPath === '/') {
        $newPath = '/';
    }
    
    // Create new request with corrected path
    $server = $request->server->all();
    $queryString = $request->getQueryString();
    $server['REQUEST_URI'] = $newPath . ($queryString ? '?' . $queryString : '');
    
    $request = Request::create(
        $newPath,
        $request->getMethod(),
        $request->all(),
        $request->cookies->all(),
        $request->files->all(),
        $server,
        $request->getContent()
    );
}

// Handle the request
$app->handleRequest($request);
