@extends('layouts.admin')

@section('title', 'Logo Settings')

@section('header-description', 'Upload and manage the website logo that appears in the header.')

@section('header-actions')
    <a href="{{ route('careers.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-100">
        Preview Website
    </a>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Website Logo</h2>
            <p class="text-sm text-gray-600">
                Upload a logo image that will appear in the website header. Recommended size: 200×60 pixels or similar aspect ratio.
                Supported formats: JPG, PNG, SVG, WebP (max 4MB).
            </p>

            <form action="{{ route('admin.logo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                    @if ($settings->logo_path)
                        <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50 p-4 inline-block">
                            <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Website logo" class="max-h-20 max-w-full object-contain">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Current logo is displayed above. Upload a new image to replace it.</p>
                    @else
                        <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No logo uploaded yet.</p>
                            <p class="text-xs text-gray-400 mt-1">The default text logo will be displayed until you upload one.</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Upload New Logo</label>
                    <input
                        id="logo"
                        name="logo"
                        type="file"
                        accept="image/jpeg,image/jpg,image/png,image/svg+xml,image/webp"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                    >
                    @error('logo')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Max size 4MB. JPG, PNG, SVG, or WebP format.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700">
                        Save Logo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection



