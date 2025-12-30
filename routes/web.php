<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LogoSettingsController;
use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\Admin\JobPostController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TokenController as AdminTokenController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Public Website Routes - Careers is now the home page
Route::get('/', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('careers.apply.store');

// Application Status Routes (Public)
Route::get('/application/status/lookup', function() {
    return view('careers.application-status-lookup');
})->name('application.status.lookup');
Route::post('/application/lookup', [JobApplicationController::class, 'lookup'])->name('application.lookup');
Route::get('/application/{application}/status', [JobApplicationController::class, 'status'])->name('application.status');

// Aptitude Test Routes (Public and Candidate)
Route::get('/aptitude-test/{application}', [\App\Http\Controllers\AptitudeTestController::class, 'show'])->name('aptitude-test.show');
Route::post('/aptitude-test/{application}/submit', [\App\Http\Controllers\AptitudeTestController::class, 'submit'])->name('aptitude-test.submit');
Route::get('/aptitude-test/{application}/results', [\App\Http\Controllers\AptitudeTestController::class, 'results'])->name('aptitude-test.results');
Route::get('/aptitude-test/{application}/verify', [\App\Http\Controllers\AptitudeTestController::class, 'verify'])->name('aptitude-test.verify');

// Self Interview Routes (Public and Candidate)
Route::get('/self-interview/{application}', [\App\Http\Controllers\SelfInterviewController::class, 'show'])->name('self-interview.show');
Route::post('/self-interview/{application}/submit', [\App\Http\Controllers\SelfInterviewController::class, 'submit'])->name('self-interview.submit');
Route::get('/self-interview/{application}/results', [\App\Http\Controllers\SelfInterviewController::class, 'results'])->name('self-interview.results');

// Dashboard Routes (Protected)
Route::get('/dashboard', function () {
    // Check if candidate is logged in
    $candidate = auth()->guard('candidate')->user();
    if ($candidate) {
        return redirect()->route('candidate.dashboard');
    }
    
    // Check if employee/user is logged in
    $user = auth()->user();
    if ($user && (in_array($user->role, ['admin', 'hr_manager', 'editor']) || $user->isClient())) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('profile.edit');
})->middleware(['auth:web,candidate', 'verified'])->name('dashboard');

// Candidate Routes (separate guard)
Route::middleware(['auth:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CandidateDashboardController::class, 'index'])->name('dashboard');
    Route::get('/application/{application}', [\App\Http\Controllers\CandidateDashboardController::class, 'show'])->name('application.show');
});

// Profile routes (accessible by both candidates and employees)
Route::middleware(['auth:web,candidate'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Employee-only routes (web guard only)
Route::middleware(['auth:web'])->group(function () {
    Route::post('/profile/sessions/{sessionId}/revoke', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
    Route::post('/profile/sessions/revoke-others', [ProfileController::class, 'revokeOtherSessions'])->name('profile.sessions.revoke-others');
});

Route::middleware(['auth', 'verified', 'admin', 'not.candidate'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'search'])->name('search');
        Route::get('/profile', [ProfileController::class, 'editAdmin'])->name('profile');
        // Admin-only routes: Settings, Team
        Route::middleware('role:admin')->group(function () {
            // Settings
            Route::get('/logo', [LogoSettingsController::class, 'edit'])->name('logo.edit');
            Route::post('/logo', [LogoSettingsController::class, 'update'])->name('logo.update');
            Route::get('/api', [ApiSettingsController::class, 'edit'])->name('api.edit');
            Route::post('/api', [ApiSettingsController::class, 'update'])->name('api.update');
            Route::get('/general', [GeneralSettingsController::class, 'edit'])->name('general.edit');
            Route::post('/general', [GeneralSettingsController::class, 'update'])->name('general.update');
            
            // Team Members
            Route::resource('team-members', AdminTeamMemberController::class);
            
            // Activity Logs
            Route::resource('activity-logs', AdminActivityLogController::class)->only(['index', 'show']);
            Route::post('activity-logs/{activityLog}/block-ip', [AdminActivityLogController::class, 'blockIp'])->name('activity-logs.block-ip');
            Route::post('activity-logs/{activityLog}/ban-user', [AdminActivityLogController::class, 'banUser'])->name('activity-logs.ban-user');
            Route::post('activity-logs/{activityLog}/revoke-sessions', [AdminActivityLogController::class, 'revokeUserSessions'])->name('activity-logs.revoke-sessions');
            Route::post('blocked-ips/unblock', [AdminActivityLogController::class, 'unblockIp'])->name('blocked-ips.unblock');
            Route::post('users/{user}/unban', [AdminActivityLogController::class, 'unbanUser'])->name('users.unban');
        });
        
        Route::post('contact-messages/bulk-update-status', [AdminContactMessageController::class, 'bulkUpdateStatus'])->name('contact-messages.bulk-update-status');
        Route::post('contact-messages/bulk-delete', [AdminContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
        Route::get('contact-messages/export', [AdminContactMessageController::class, 'export'])->name('contact-messages.export');
        Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('contact-messages/{contactMessage}/reply', [AdminContactMessageController::class, 'sendReply'])->name('contact-messages.reply');
        
        // User Management - Only accessible by admins
        Route::middleware('role:admin')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::get('permissions', [\App\Http\Controllers\Admin\PermissionsController::class, 'index'])->name('permissions.index');
            Route::put('permissions', [\App\Http\Controllers\Admin\PermissionsController::class, 'update'])->name('permissions.update');
            
            // Company Management
            Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class);
            Route::post('companies/{company}/regenerate-api-key', [\App\Http\Controllers\Admin\CompanyController::class, 'regenerateApiKey'])->name('companies.regenerate-api-key');
            Route::post('companies/{company}/toggle-status', [\App\Http\Controllers\Admin\CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
        });
        
        // Careers Routes - Accessible by Admin, HR Manager, and Clients
        Route::middleware('role:admin,hr_manager,client')->group(function () {
            Route::resource('jobs', JobPostController::class)->except(['destroy']);
            Route::post('jobs/{job}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('jobs.toggle-status');
            Route::get('jobs/{job}/configure-sieving', [JobPostController::class, 'configureSieving'])->name('jobs.configure-sieving');
            Route::post('jobs/{job}/configure-sieving', [JobPostController::class, 'storeSieving'])->name('jobs.store-sieving');
            
            // Aptitude Test Management
            Route::resource('aptitude-test', \App\Http\Controllers\Admin\AptitudeTestController::class)->except(['show']);
            Route::post('aptitude-test/{question}/toggle-status', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'toggleStatus'])->name('aptitude-test.toggle-status');
            Route::post('aptitude-test/bulk-activate', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkActivate'])->name('aptitude-test.bulk-activate');
            Route::post('aptitude-test/bulk-deactivate', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkDeactivate'])->name('aptitude-test.bulk-deactivate');
            Route::delete('aptitude-test/bulk-delete', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkDelete'])->name('aptitude-test.bulk-delete');

            // Self Interview Question Management
            Route::resource('self-interview', \App\Http\Controllers\Admin\SelfInterviewQuestionController::class)->except(['show']);
            Route::post('self-interview/{selfInterview}/toggle-status', [\App\Http\Controllers\Admin\SelfInterviewQuestionController::class, 'toggleStatus'])->name('self-interview.toggle-status');
            
            // Job Applications Routes
            Route::prefix('job-applications')->name('job-applications.')->group(function () {
                // Bulk actions must come before resource routes to avoid route conflicts
                Route::post('bulk-send-confirmation', [AdminJobApplicationController::class, 'sendBulkConfirmationEmails'])->name('bulk-send-confirmation');
                Route::post('bulk-update-status', [AdminJobApplicationController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
                Route::post('bulk-delete', [AdminJobApplicationController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('export', [AdminJobApplicationController::class, 'export'])->name('export');
                Route::get('calendar', [AdminJobApplicationController::class, 'interviewCalendar'])->name('calendar');
            });
            Route::get('job-applications/{application}/view-cv', [AdminJobApplicationController::class, 'viewCv'])->name('job-applications.view-cv');
            Route::get('job-applications/{application}/download-cv', [AdminJobApplicationController::class, 'downloadCv'])->name('job-applications.download-cv');
            Route::resource('job-applications', AdminJobApplicationController::class)->only(['index', 'show', 'destroy']);
            Route::post('job-applications/{application}/review', [AdminJobApplicationController::class, 'review'])->name('job-applications.review');
            Route::post('job-applications/{application}/schedule-interview', [AdminJobApplicationController::class, 'scheduleInterview'])->name('job-applications.schedule-interview');
            Route::post('job-applications/{application}/update-status', [AdminJobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
            Route::post('job-applications/{application}/send-message', [AdminJobApplicationController::class, 'sendMessage'])->name('job-applications.send-message');
            Route::post('job-applications/{application}/send-confirmation', [AdminJobApplicationController::class, 'sendConfirmationEmail'])->name('job-applications.send-confirmation');
            Route::post('job-applications/{application}/create-candidate-account', [AdminJobApplicationController::class, 'createCandidateAccount'])->name('job-applications.create-candidate-account');
            Route::post('job-applications/{application}/resend-candidate-credentials', [AdminJobApplicationController::class, 'resendCandidateCredentials'])->name('job-applications.resend-candidate-credentials');
            Route::get('job-applications/{application}/view-candidate-dashboard', [AdminJobApplicationController::class, 'viewCandidateDashboard'])->name('job-applications.view-candidate-dashboard');
            Route::get('job-applications/{application}/preview-aptitude-test', [AdminJobApplicationController::class, 'previewAptitudeTest'])->name('job-applications.preview-aptitude-test');
            Route::get('job-applications/{application}/preview-candidate-status', [AdminJobApplicationController::class, 'previewCandidateStatus'])->name('job-applications.preview-candidate-status');
            Route::post('job-applications/bulk-create-candidate-accounts', [AdminJobApplicationController::class, 'bulkCreateCandidateAccounts'])->name('job-applications.bulk-create-candidate-accounts');
            Route::post('interviews/{interview}/update-result', [AdminJobApplicationController::class, 'updateInterviewResult'])->name('interviews.update-result');
            Route::post('job-applications/{application}/parse-cv', [AdminJobApplicationController::class, 'parseCv'])->name('job-applications.parse-cv');
            Route::post('job-applications/{application}/analyze-with-ai', [AdminJobApplicationController::class, 'analyzeWithAI'])->name('job-applications.analyze-with-ai');
            Route::post('job-applications/{application}/process-cv-and-ai', [AdminJobApplicationController::class, 'processCvAndAI'])->name('job-applications.process-cv-and-ai');
        });
        
        // Token Management Routes - Accessible by Admin
        Route::middleware('role:admin')->group(function () {
            Route::get('tokens', [AdminTokenController::class, 'index'])->name('tokens.index');
            Route::get('tokens/usage', [AdminTokenController::class, 'usage'])->name('tokens.usage');
            Route::get('tokens/purchases', [AdminTokenController::class, 'purchases'])->name('tokens.purchases');
            Route::post('tokens/purchases', [AdminTokenController::class, 'createPurchase'])->name('tokens.purchases.create');
            Route::post('tokens/allocate', [AdminTokenController::class, 'allocate'])->name('tokens.allocate');
            Route::get('tokens/balance', [AdminTokenController::class, 'balance'])->name('tokens.balance');
            Route::get('tokens/stats', [AdminTokenController::class, 'stats'])->name('tokens.stats');
        });

        // Temporary maintenance route to clear caches from browser (admin only).
        // Visit /admin/maintenance/clear-caches once in production, then remove this route.
        Route::get('/maintenance/clear-caches', function () {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');

            return response()->json([
                'status' => 'ok',
                'message' => 'Application caches cleared successfully.',
            ]);
        })->middleware('role:admin')->name('maintenance.clear-caches');
    });

require __DIR__.'/auth.php';
