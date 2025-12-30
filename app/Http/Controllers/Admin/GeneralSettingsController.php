<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    public function edit()
    {
        $settings = GeneralSetting::query()->latest()->first() ?? new GeneralSetting();

        return view('admin.general.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Company Information
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_description' => ['nullable', 'string', 'max:1000'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            
            // Social Media
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            
            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'google_analytics_id' => ['nullable', 'string', 'max:100'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:100'],
            'favicon' => ['nullable', 'image', 'max:512'],
            
            // Footer
            'footer_text' => ['nullable', 'string', 'max:1000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'privacy_policy_url' => ['nullable', 'url', 'max:255'],
            'terms_of_service_url' => ['nullable', 'url', 'max:255'],
            
            // Notifications
            'contact_notification_recipients' => ['nullable', 'string', 'max:500'],
            'loan_notification_recipients' => ['nullable', 'string', 'max:500'],
            'job_notification_recipients' => ['nullable', 'string', 'max:500'],
        ]);

        $settings = GeneralSetting::query()->latest()->first() ?? new GeneralSetting();

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $validated['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        // Remove favicon from validated array if it's not being updated
        unset($validated['favicon']);

        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.general.edit')
            ->with('status', 'General settings updated successfully.');
    }
}
