<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'role' => EnsureUserHasRole::class,
            'not.candidate' => \App\Http\Middleware\EnsureNotCandidate::class,
        ]);
        
        // Track user sessions for authenticated users, check blocked IPs, and check banned users
        $middleware->web(append: [
            \App\Http\Middleware\CheckBlockedIp::class,
            \App\Http\Middleware\CheckBannedUser::class,
            \App\Http\Middleware\TrackUserSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Helper function to determine layout based on request context
        $getLayout = function ($request) {
            $path = $request->path();
            
            // Admin routes
            if (str_starts_with($path, 'admin')) {
                return 'admin';
            }
            
            // Candidate routes
            if (str_starts_with($path, 'candidate')) {
                return 'candidate';
            }
            
            // Check if user is authenticated as admin/candidate
            if ($request->user() && !$request->user()->isClient()) {
                return 'admin';
            }
            
            if ($request->user('candidate')) {
                return 'candidate';
            }
            
            // Default to website layout
            return 'website';
        };

        // Custom error page rendering - only show custom 404 if not in debug mode
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) use ($getLayout) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }
            // In debug mode, let Laravel show the default error page with details
            if (config('app.debug')) {
                return null; // Let Laravel handle it with debug info
            }
            $layout = $getLayout($request);
            return response()->view('errors.404', ['layout' => $layout], 404);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) use ($getLayout) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            }
            
            $statusCode = $e->getStatusCode();
            $errorViews = [
                403 => 'errors.403',
                404 => 'errors.404',
                419 => 'errors.419',
                500 => 'errors.500',
                503 => 'errors.503',
            ];
            
            if (isset($errorViews[$statusCode]) && view()->exists($errorViews[$statusCode])) {
                $layout = $getLayout($request);
                return response()->view($errorViews[$statusCode], ['layout' => $layout], $statusCode);
            }
        });

        // Handle general exceptions
        $exceptions->render(function (\Throwable $e, $request) use ($getLayout) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => config('app.debug') ? $e->getMessage() : 'Server Error'
                ], 500);
            }
            
            // Only show detailed error in debug mode
            if (config('app.debug')) {
                return null; // Let Laravel handle it with debug info
            }
            
            $layout = $getLayout($request);
            return response()->view('errors.500', ['layout' => $layout], 500);
        });

        // Log all exceptions
        $exceptions->report(function (\Throwable $e) {
            \Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })->create();
