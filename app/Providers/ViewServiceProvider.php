<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share general settings with all views
        View::composer('layouts.website', function ($view) {
            $generalSettings = GeneralSetting::query()->latest()->first() ?? new GeneralSetting();
            $view->with('generalSettings', $generalSettings);
        });
    }
}
