@extends('layouts.admin')

@section('title', $company->name)
@section('header-description', 'View company details and manage settings.')

@section('header-actions')
    <div class="flex items-center gap-2 sm:gap-3">
        <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
            Edit Company
        </a>
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
            ← Back to Companies
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Company Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Company Name</dt>
                        <dd class="text-base text-slate-900">{{ $company->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Slug</dt>
                        <dd class="text-base text-slate-900 font-mono">{{ $company->slug }}</dd>
                    </div>
                    @if($company->domain)
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Domain</dt>
                        <dd class="text-base text-slate-900">{{ $company->domain }}</dd>
                    </div>
                    @endif
                    @if($company->email)
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Email</dt>
                        <dd class="text-base text-slate-900">{{ $company->email }}</dd>
                    </div>
                    @endif
                    @if($company->phone)
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Phone</dt>
                        <dd class="text-base text-slate-900">{{ $company->phone }}</dd>
                    </div>
                    @endif
                    @if($company->address)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Address</dt>
                        <dd class="text-base text-slate-900">{{ $company->address }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Subscription Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Subscription Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Subscription Plan</dt>
                        <dd>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold 
                                @if($company->subscription_plan === 'enterprise') bg-purple-100 text-purple-700 border border-purple-200
                                @elseif($company->subscription_plan === 'professional') bg-blue-100 text-blue-700 border border-blue-200
                                @else bg-slate-100 text-slate-700 border border-slate-200
                                @endif">
                                {{ ucfirst($company->subscription_plan) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Subscription Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold
                                @if($company->subscription_status === 'active') bg-emerald-100 text-emerald-700
                                @elseif($company->subscription_status === 'trial') bg-amber-100 text-amber-700
                                @elseif($company->subscription_status === 'suspended') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ ucfirst($company->subscription_status) }}
                            </span>
                        </dd>
                    </div>
                    @if($company->subscription_expires_at)
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Expires At</dt>
                        <dd class="text-base text-slate-900">{{ $company->subscription_expires_at->format('M d, Y') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-semibold text-slate-600 mb-1">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold {{ $company->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Users -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Users ({{ $company->users->count() }})</h3>
                </div>
                @if($company->users->count() > 0)
                    <div class="space-y-3">
                        @foreach($company->users as $user)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-600">{{ $user->email }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No users assigned to this company.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Token Balance -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Token Balance</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Total Allocated</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($tokenBalance['total_allocated'] ?? 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Used</p>
                        <p class="text-xl font-semibold text-amber-600">{{ number_format($tokenBalance['used'] ?? 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Remaining</p>
                        <p class="text-xl font-semibold text-emerald-600">{{ number_format($tokenBalance['remaining'] ?? 0) }}</p>
                    </div>
                    @if(isset($tokenBalance['percentage_used']))
                    <div class="pt-3 border-t border-slate-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-slate-600">Usage</span>
                            <span class="text-sm font-semibold text-slate-900">{{ round($tokenBalance['percentage_used'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $tokenBalance['percentage_used'] }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.companies.regenerate-api-key', $company) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            Regenerate API Key
                        </button>
                    </form>
                    <form action="{{ route('admin.companies.toggle-status', $company) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm font-semibold {{ $company->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors">
                            {{ $company->is_active ? 'Deactivate' : 'Activate' }} Company
                        </button>
                    </form>
                    <a href="{{ route('admin.tokens.index') }}" class="block px-4 py-2 text-sm font-semibold text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                        Manage Tokens
                    </a>
                </div>
            </div>

            <!-- API Key -->
            @if($company->api_key)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">API Key</h3>
                <div class="bg-slate-50 rounded-lg p-3">
                    <code class="text-xs font-mono text-slate-700 break-all">{{ $company->api_key }}</code>
                </div>
                <p class="text-xs text-slate-500 mt-2">Use this API key for widget integration</p>
            </div>
            @endif
        </div>
    </div>
@endsection

