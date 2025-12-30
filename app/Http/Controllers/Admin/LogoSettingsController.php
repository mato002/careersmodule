<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoSettingsController extends Controller
{
    public function edit()
    {
        $settings = GeneralSetting::query()->latest()->first() ?? new GeneralSetting();

        return view('admin.logo.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => ['nullable', 'image', 'max:4096', 'mimes:jpeg,jpg,png,svg,webp'],
        ]);

        $settings = GeneralSetting::query()->latest()->first() ?? new GeneralSetting();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            // Store new logo
            $path = $request->file('logo')->store('logo', 'public');
            $settings->logo_path = $path;
            $settings->save();
        }

        return redirect()
            ->route('admin.logo.edit')
            ->with('status', 'Logo updated successfully.');
    }
}



