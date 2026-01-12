@extends('layouts.candidate')

@section('title', 'Profile Settings')
@section('header-description', 'Manage your account details and password')

@section('header-actions')
    <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-teal-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-white whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="hidden sm:inline">Back to Dashboard</span>
        <span class="sm:hidden">Back</span>
    </a>
@endsection

@section('content')
    <div class="w-full space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-teal-900 shadow-sm">
                {{ session('status') === 'profile-updated' ? 'Profile updated successfully!' : (session('status') === 'password-updated' ? 'Password updated successfully!' : session('status')) }}
            </div>
        @endif

        <!-- Profile Completeness Card -->
        @php
            $completeness = $candidate->profile_completeness ?? 0;
        @endphp
        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-2xl shadow-lg border border-teal-200 overflow-hidden">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold text-gray-900">Profile Completeness</h2>
                    <span class="text-2xl font-bold text-teal-600">{{ $completeness }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $completeness }}%"></div>
                </div>
                <p class="text-sm text-gray-600">
                    @if($completeness < 50)
                        Complete your profile to improve your job application success rate.
                    @elseif($completeness < 80)
                        Your profile is looking good! Add a few more details to make it complete.
                    @else
                        Great! Your profile is almost complete.
                    @endif
                </p>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Profile Photo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Photo</label>
                        <div class="flex items-center gap-4">
                            @if($candidate->profile_photo_path)
                                <img src="{{ $candidate->profile_photo_url }}" alt="{{ $candidate->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input 
                                    type="file" 
                                    name="profile_photo" 
                                    id="profile_photo"
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                                />
                                <p class="text-xs text-gray-500 mt-1">Upload JPG, PNG, or WEBP up to 4 MB.</p>
                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                                value="{{ old('name', $candidate->name ?? '') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                            />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                                value="{{ old('email', $candidate->email ?? '') }}" 
                                required 
                                autocomplete="username"
                            />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if ($candidate instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $candidate->hasVerifiedEmail())
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600">
                                        {{ __('Your email address is unverified.') }}
                                        <form method="post" action="{{ route('verification.send') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="underline text-sm text-teal-600 hover:text-teal-800">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </form>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-sm font-medium text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="phone_country_code" 
                                    id="phone_country_code"
                                    placeholder="+254"
                                    maxlength="5"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('phone_country_code') border-red-500 @enderror" 
                                    value="{{ old('phone_country_code', $candidate->phone_country_code ?? '') }}"
                                />
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('phone') border-red-500 @enderror" 
                                    value="{{ old('phone', $candidate->phone ?? '') }}"
                                    autocomplete="tel"
                                />
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('phone_country_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                            <input 
                                type="date" 
                                name="date_of_birth" 
                                id="date_of_birth"
                                max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror" 
                                value="{{ old('date_of_birth', $candidate->date_of_birth ? $candidate->date_of_birth->format('Y-m-d') : '') }}"
                            />
                            @error('date_of_birth')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Street Address</label>
                        <input 
                            type="text" 
                            name="address" 
                            id="address"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                            value="{{ old('address', $candidate->address ?? '') }}"
                            autocomplete="street-address"
                        />
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                            <input 
                                type="text" 
                                name="city" 
                                id="city"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('city') border-red-500 @enderror" 
                                value="{{ old('city', $candidate->city ?? '') }}"
                                autocomplete="address-level2"
                            />
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State/Province -->
                        <div>
                            <label for="state" class="block text-sm font-semibold text-gray-700 mb-2">State/Province</label>
                            <input 
                                type="text" 
                                name="state" 
                                id="state"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('state') border-red-500 @enderror" 
                                value="{{ old('state', $candidate->state ?? '') }}"
                                autocomplete="address-level1"
                            />
                            @error('state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <input 
                                type="text" 
                                name="country" 
                                id="country"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('country') border-red-500 @enderror" 
                                value="{{ old('country', $candidate->country ?? '') }}"
                                autocomplete="country-name"
                            />
                            @error('country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                            <input 
                                type="text" 
                                name="postal_code" 
                                id="postal_code"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('postal_code') border-red-500 @enderror" 
                                value="{{ old('postal_code', $candidate->postal_code ?? '') }}"
                                autocomplete="postal-code"
                            />
                            @error('postal_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preferred Language -->
                    <div>
                        <label for="preferred_language" class="block text-sm font-semibold text-gray-700 mb-2">Preferred Language</label>
                        <select 
                            name="preferred_language" 
                            id="preferred_language"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('preferred_language') border-red-500 @enderror"
                        >
                            <option value="en" {{ old('preferred_language', $candidate->preferred_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ old('preferred_language', $candidate->preferred_language ?? 'en') === 'sw' ? 'selected' : '' }}>Swahili</option>
                            <option value="fr" {{ old('preferred_language', $candidate->preferred_language ?? 'en') === 'fr' ? 'selected' : '' }}>French</option>
                        </select>
                        @error('preferred_language')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 font-medium"
                            >Saved successfully!</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Sessions Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Active Sessions</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your active login sessions across different devices.</p>
                    </div>
                    @if($sessions->count() > 1)
                        <form method="post" action="{{ route('profile.revoke-other-candidate-sessions') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700">
                                Revoke All Other Sessions
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="p-6">
                @if($sessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($sessions as $session)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg {{ $session->session_id === $currentSessionId ? 'bg-teal-50 border-teal-200' : '' }}">
                                <div class="flex items-center gap-4">
                                    <div class="text-2xl">{{ $session->device_icon }}</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $session->browser }} on {{ $session->platform }}
                                            @if($session->session_id === $currentSessionId)
                                                <span class="ml-2 px-2 py-1 text-xs font-semibold bg-teal-100 text-teal-800 rounded">Current Session</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($session->device_type) }} • {{ $session->ip_address }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Last active: {{ $session->last_activity->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                @if($session->session_id !== $currentSessionId)
                                    <form method="post" action="{{ route('profile.revoke-candidate-session', $session->session_id) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700">
                                            Revoke
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">No active sessions found.</p>
                @endif
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Update Password</h2>
                <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <input 
                            id="update_password_current_password" 
                            name="current_password" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('current_password', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="current-password"
                        />
                        @error('current_password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input 
                            id="update_password_password" 
                            name="password" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="new-password"
                        />
                        @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input 
                            id="update_password_password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="new-password"
                        />
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 font-medium"
                            >Password updated successfully!</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                <h2 class="text-xl font-bold text-red-900">Delete Account</h2>
                <p class="text-sm text-red-700 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
                <button
                    type="button"
                    onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold"
                >
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="{{ __('Password') }}"
                        required
                    />
                    @error('password', 'userDeletion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold"
                    >
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Close modal when clicking outside
        document.getElementById('delete-account-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
@endsection
