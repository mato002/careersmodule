<?php

// Alternative route file for subdirectory deployment
// This removes the /careers prefix from routes since we're already in /careers

use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;

// Public Website Routes - Adjusted for subdirectory
// Since we're in /careers, routes don't need /careers prefix
Route::get('/', [CareerController::class, 'index'])->name('careers.index');
Route::get('/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('careers.apply.store');

// Note: This is an alternative approach - you would need to:
// 1. Backup current routes/web.php
// 2. Replace the careers routes section with these
// 3. Update all route() calls in views to use the new route names
// 4. This is more invasive but might be more reliable



