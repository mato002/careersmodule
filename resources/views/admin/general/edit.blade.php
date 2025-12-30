@extends('layouts.admin')

@section('title', 'General Settings')

@section('header-description', 'Manage company information, social media, SEO, footer, and notification settings.')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        @if (session('status'))
            <div class="bg-teal-50 border border-teal-200 text-teal-900 px-4 py-3 rounded-xl">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.general.update') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Company Information Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-teal-100 rounded-lg text-teal-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Company Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            value="{{ old('company_name', $settings->company_name) }}"
                            placeholder="Fortress Lenders Limited"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('company_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="company_email" class="block text-sm font-medium text-gray-700">Company Email</label>
                        <input
                            type="email"
                            id="company_email"
                            name="company_email"
                            value="{{ old('company_email', $settings->company_email) }}"
                            placeholder="info@fortresslenders.com"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('company_email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="company_phone" class="block text-sm font-medium text-gray-700">Company Phone</label>
                        <input
                            type="text"
                            id="company_phone"
                            name="company_phone"
                            value="{{ old('company_phone', $settings->company_phone) }}"
                            placeholder="+254 XXX XXX XXX"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('company_phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="company_registration_number" class="block text-sm font-medium text-gray-700">Registration Number</label>
                        <input
                            type="text"
                            id="company_registration_number"
                            name="company_registration_number"
                            value="{{ old('company_registration_number', $settings->company_registration_number) }}"
                            placeholder="C.123456"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('company_registration_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="company_address" class="block text-sm font-medium text-gray-700">Company Address</label>
                        <textarea
                            id="company_address"
                            name="company_address"
                            rows="3"
                            placeholder="Fortress Hse, Nakuru County, Barnabas Muguga Opp. Epic ridge Academy"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >{{ old('company_address', $settings->company_address) }}</textarea>
                        @error('company_address')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="company_description" class="block text-sm font-medium text-gray-700">Company Description</label>
                        <textarea
                            id="company_description"
                            name="company_description"
                            rows="3"
                            placeholder="The Force Of Possibilities! Empowering communities through accessible financial solutions."
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >{{ old('company_description', $settings->company_description) }}</textarea>
                        @error('company_description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">This description appears in the footer and meta tags.</p>
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Social Media Links</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                        <input
                            type="url"
                            id="facebook_url"
                            name="facebook_url"
                            value="{{ old('facebook_url', $settings->facebook_url) }}"
                            placeholder="https://www.facebook.com/fortresslenders"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('facebook_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter/X URL</label>
                        <input
                            type="url"
                            id="twitter_url"
                            name="twitter_url"
                            value="{{ old('twitter_url', $settings->twitter_url) }}"
                            placeholder="https://twitter.com/fortresslenders"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('twitter_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                        <input
                            type="url"
                            id="linkedin_url"
                            name="linkedin_url"
                            value="{{ old('linkedin_url', $settings->linkedin_url) }}"
                            placeholder="https://www.linkedin.com/company/fortresslenders"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('linkedin_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                        <input
                            type="url"
                            id="instagram_url"
                            name="instagram_url"
                            value="{{ old('instagram_url', $settings->instagram_url) }}"
                            placeholder="https://www.instagram.com/fortresslenders"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('instagram_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="youtube_url" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                        <input
                            type="url"
                            id="youtube_url"
                            name="youtube_url"
                            value="{{ old('youtube_url', $settings->youtube_url) }}"
                            placeholder="https://www.youtube.com/@fortresslenders"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('youtube_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Settings Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-100 rounded-lg text-purple-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">SEO Settings</h2>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <input
                            type="text"
                            id="meta_title"
                            name="meta_title"
                            value="{{ old('meta_title', $settings->meta_title) }}"
                            placeholder="Fortress Lenders - The Force Of Possibilities"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('meta_title')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Recommended: 50-60 characters</p>
                    </div>

                    <div class="space-y-2">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea
                            id="meta_description"
                            name="meta_description"
                            rows="3"
                            placeholder="Empowering communities through accessible financial solutions..."
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >{{ old('meta_description', $settings->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Recommended: 150-160 characters</p>
                    </div>

                    <div class="space-y-2">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                        <input
                            type="text"
                            id="meta_keywords"
                            name="meta_keywords"
                            value="{{ old('meta_keywords', $settings->meta_keywords) }}"
                            placeholder="loans, financial services, lending, kenya"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('meta_keywords')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Comma-separated keywords</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="google_analytics_id" class="block text-sm font-medium text-gray-700">Google Analytics ID</label>
                            <input
                                type="text"
                                id="google_analytics_id"
                                name="google_analytics_id"
                                value="{{ old('google_analytics_id', $settings->google_analytics_id) }}"
                                placeholder="G-XXXXXXXXXX"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                            @error('google_analytics_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="google_tag_manager_id" class="block text-sm font-medium text-gray-700">Google Tag Manager ID</label>
                            <input
                                type="text"
                                id="google_tag_manager_id"
                                name="google_tag_manager_id"
                                value="{{ old('google_tag_manager_id', $settings->google_tag_manager_id) }}"
                                placeholder="GTM-XXXXXXX"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                            @error('google_tag_manager_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                        <input
                            type="file"
                            id="favicon"
                            name="favicon"
                            accept="image/*"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('favicon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @if($settings->favicon_path)
                            <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/'.$settings->favicon_path) }}" target="_blank" class="text-teal-600 hover:underline">View favicon</a></p>
                        @endif
                        <p class="text-xs text-gray-500">Recommended: 32x32 or 16x16 pixels, .ico or .png format</p>
                    </div>
                </div>
            </div>

            <!-- Footer Settings Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-amber-100 rounded-lg text-amber-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Footer Settings</h2>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Text</label>
                        <textarea
                            id="footer_text"
                            name="footer_text"
                            rows="3"
                            placeholder="The Force Of Possibilities! Empowering communities through accessible financial solutions."
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >{{ old('footer_text', $settings->footer_text) }}</textarea>
                        @error('footer_text')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="copyright_text" class="block text-sm font-medium text-gray-700">Copyright Text</label>
                        <input
                            type="text"
                            id="copyright_text"
                            name="copyright_text"
                            value="{{ old('copyright_text', $settings->copyright_text) }}"
                            placeholder="© {{ date('Y') }} Fortress Lenders Limited. All rights reserved."
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('copyright_text')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="privacy_policy_url" class="block text-sm font-medium text-gray-700">Privacy Policy URL</label>
                            <input
                                type="url"
                                id="privacy_policy_url"
                                name="privacy_policy_url"
                                value="{{ old('privacy_policy_url', $settings->privacy_policy_url) }}"
                                placeholder="https://fortresslenders.com/privacy-policy"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                            @error('privacy_policy_url')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="terms_of_service_url" class="block text-sm font-medium text-gray-700">Terms of Service URL</label>
                            <input
                                type="url"
                                id="terms_of_service_url"
                                name="terms_of_service_url"
                                value="{{ old('terms_of_service_url', $settings->terms_of_service_url) }}"
                                placeholder="https://fortresslenders.com/terms"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                            @error('terms_of_service_url')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-green-100 rounded-lg text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Email Notification Recipients</h2>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="contact_notification_recipients" class="block text-sm font-medium text-gray-700">Contact Form Notifications</label>
                        <input
                            type="text"
                            id="contact_notification_recipients"
                            name="contact_notification_recipients"
                            value="{{ old('contact_notification_recipients', $settings->contact_notification_recipients) }}"
                            placeholder="admin@fortresslenders.com, support@fortresslenders.com"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('contact_notification_recipients')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Comma-separated email addresses that receive contact form submissions</p>
                    </div>

                    <div class="space-y-2">
                        <label for="loan_notification_recipients" class="block text-sm font-medium text-gray-700">Loan Application Notifications</label>
                        <input
                            type="text"
                            id="loan_notification_recipients"
                            name="loan_notification_recipients"
                            value="{{ old('loan_notification_recipients', $settings->loan_notification_recipients) }}"
                            placeholder="loans@fortresslenders.com"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('loan_notification_recipients')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Comma-separated email addresses that receive loan application notifications</p>
                    </div>

                    <div class="space-y-2">
                        <label for="job_notification_recipients" class="block text-sm font-medium text-gray-700">Job Application Notifications</label>
                        <input
                            type="text"
                            id="job_notification_recipients"
                            name="job_notification_recipients"
                            value="{{ old('job_notification_recipients', $settings->job_notification_recipients) }}"
                            placeholder="hr@fortresslenders.com"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        >
                        @error('job_notification_recipients')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Comma-separated email addresses that receive job application notifications</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save All Settings
                </button>
            </div>
        </form>
    </div>
@endsection

