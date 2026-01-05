<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set URL root for subdirectory deployment
        // Only force root URL if APP_URL is explicitly set with /careers
        $appUrl = config('app.url', 'http://localhost');
        $appUrl = rtrim($appUrl, '/');
        
        // Check if we're in a subdirectory deployment by checking script path
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $isSubdirectory = (
            str_contains($scriptName, '/Careers/') || 
            str_contains($scriptName, '/careers/')
        );
        
        // Only force root URL if:
        // 1. We're in production AND APP_URL includes /careers, OR
        // 2. We detect subdirectory deployment AND APP_URL doesn't already have /careers
        if ($isSubdirectory && strpos($appUrl, '/careers') === false) {
            // Don't modify - let Laravel use APP_URL as-is
            // The .htaccess and index.php handle path rewriting
        } elseif (strpos($appUrl, '/careers') !== false) {
            // APP_URL already includes /careers, use it as-is
            URL::forceRootUrl($appUrl);
        }

        // Share logo globally
        View::composer('*', function ($view): void {
            $logoSetting = GeneralSetting::latest()->first();
            $logoPath = $logoSetting?->logo_path;
            $hasLogo = $logoPath && Storage::disk('public')->exists($logoPath);
            
            $view->with('logoPath', $hasLogo ? $logoPath : null);
        });

        View::composer('layouts.admin', function ($view): void {
            $unreadCount = ContactMessage::whereNull('handled_at')
                ->orWhere('status', 'new')
                ->count();

            $generalSettings = GeneralSetting::latest()->first();
            
            $view->with('adminUnreadMessagesCount', $unreadCount);
            $view->with('generalSettings', $generalSettings);
        });

        View::composer('layouts.candidate', function ($view): void {
            $generalSettings = GeneralSetting::latest()->first();
            $view->with('generalSettings', $generalSettings);
        });
    }
}
