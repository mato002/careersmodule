@extends('layouts.admin')

@section('title', 'Onboard Company')
@section('header-description', 'Add a new company to the platform and set up their subscription.')

@section('header-actions')
    <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Companies
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.companies.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Company Information -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Company Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $company->slug) }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Auto-generated if left empty">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Domain</label>
                            <input type="text" name="domain" value="{{ old('domain', $company->domain) }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="example.com">
                            @error('domain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $company->email) }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $company->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Subscription Information -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Subscription Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Subscription Plan <span class="text-red-500">*</span></label>
                            <select name="subscription_plan" required
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="starter" {{ old('subscription_plan', $company->subscription_plan) === 'starter' ? 'selected' : '' }}>Starter</option>
                                <option value="professional" {{ old('subscription_plan', $company->subscription_plan) === 'professional' ? 'selected' : '' }}>Professional</option>
                                <option value="enterprise" {{ old('subscription_plan', $company->subscription_plan) === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                            @error('subscription_plan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Subscription Status <span class="text-red-500">*</span></label>
                            <select name="subscription_status" required
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="trial" {{ old('subscription_status', $company->subscription_status) === 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="active" {{ old('subscription_status', $company->subscription_status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ old('subscription_status', $company->subscription_status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="cancelled" {{ old('subscription_status', $company->subscription_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('subscription_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Expires At</label>
                            <input type="date" name="subscription_expires_at" value="{{ old('subscription_expires_at', $company->subscription_expires_at?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('subscription_expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- AI Settings -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">AI Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="ai_enabled" value="1" {{ old('ai_enabled', $company->ai_enabled) ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <label class="text-sm font-semibold text-slate-700">Enable AI Features</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="ai_auto_sieve" value="1" {{ old('ai_auto_sieve', $company->ai_auto_sieve) ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <label class="text-sm font-semibold text-slate-700">Auto Sieve Applications</label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">AI Threshold (%)</label>
                            <input type="number" name="ai_threshold" value="{{ old('ai_threshold', $company->ai_threshold) }}" min="0" max="100" step="0.1"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('ai_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Admin User Creation -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Create Admin User</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <input type="checkbox" name="create_admin_user" value="1" id="create_admin_user" {{ old('create_admin_user') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <label for="create_admin_user" class="text-sm font-semibold text-slate-700">Create a client admin user for this company</label>
                    </div>
                    <div id="admin_user_fields" class="grid grid-cols-1 md:grid-cols-3 gap-4" style="display: {{ old('create_admin_user') ? 'grid' : 'none' }};">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Admin Name</label>
                            <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('admin_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Admin Email</label>
                            <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('admin_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Admin Password</label>
                            <input type="password" name="admin_password" value="{{ old('admin_password') }}"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('admin_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <label class="text-sm font-semibold text-slate-700">Company is active</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-sm">
                        Create Company
                    </button>
                    <a href="{{ route('admin.companies.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('create_admin_user').addEventListener('change', function() {
            document.getElementById('admin_user_fields').style.display = this.checked ? 'grid' : 'none';
        });
    </script>
@endsection

