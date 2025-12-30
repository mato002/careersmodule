@extends('layouts.client')

@section('title', 'Client Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Welcome back, {{ $user->name }}!</h1>
        <p class="text-slate-600">Company: <span class="font-semibold">{{ $company->name }}</span></p>
        <p class="text-sm text-slate-500 mt-1">Subscription: <span class="font-semibold capitalize">{{ $company->subscription_status }}</span></p>
    </div>

    <!-- Token Balance & Usage -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Token Balance -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Token Balance</h3>
                <a href="{{ route('client.tokens.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Manage →</a>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($balance['remaining']) }}</p>
                    <p class="text-sm text-slate-500">Remaining Tokens</p>
                </div>
                <div class="pt-3 border-t border-slate-200">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-600">Total Allocated</span>
                        <span class="font-semibold text-slate-900">{{ number_format($balance['total_allocated']) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-600">Used</span>
                        <span class="font-semibold text-slate-900">{{ number_format($balance['used']) }}</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3 mt-3">
                        <div class="bg-teal-600 h-3 rounded-full transition-all" style="width: {{ $balance['percentage_used'] }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">{{ $balance['percentage_used'] }}% used</p>
                </div>
                @if($balance['remaining'] < 1000)
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm text-amber-800">⚠️ Low token balance. <a href="{{ route('client.tokens.purchase') }}" class="font-semibold underline">Purchase more tokens</a></p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usage This Month -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">This Month</h3>
                <a href="{{ route('client.tokens.usage') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Details →</a>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($usageStats['total_tokens']) }}</p>
                    <p class="text-sm text-slate-500">Tokens Used</p>
                </div>
                <div class="pt-3 border-t border-slate-200">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-600">Operations</span>
                        <span class="font-semibold text-slate-900">{{ number_format($usageStats['operations_count']) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Estimated Cost</span>
                        <span class="font-semibold text-slate-900">${{ number_format($usageStats['total_cost'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Application Stats -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Job Applications</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-slate-50 rounded-lg">
                <p class="text-3xl font-bold text-slate-900">{{ $jobStats['total'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Total</p>
            </div>
            <div class="text-center p-4 bg-amber-50 rounded-lg">
                <p class="text-3xl font-bold text-amber-700">{{ $jobStats['pending'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Pending</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-700">{{ $jobStats['shortlisted'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Shortlisted</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-700">{{ $jobStats['hired'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Hired</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">Active Job Posts: <span class="font-semibold text-slate-900">{{ $activeJobPosts }}</span></p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Applications -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Recent Applications</h3>
            @if($recentApplications->count() > 0)
                <div class="space-y-3">
                    @foreach($recentApplications as $application)
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $application->full_name }}</p>
                                    <p class="text-sm text-slate-600">{{ $application->jobPost->title ?? 'N/A' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize
                                    {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $application->status === 'shortlisted' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $application->status === 'hired' ? 'bg-green-100 text-green-700' : '' }}
                                ">{{ $application->status }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">{{ $application->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 text-center py-4">No applications yet</p>
            @endif
        </div>

        <!-- Recent Token Usage -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Recent Token Usage</h3>
            @if($recentTokenUsage->count() > 0)
                <div class="space-y-3">
                    @foreach($recentTokenUsage as $usage)
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $usage->operation_type) }}</p>
                                    <p class="text-sm text-slate-600">{{ $usage->jobApplication->full_name ?? 'System Operation' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-teal-700">{{ number_format($usage->tokens_used) }} tokens</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">{{ $usage->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 text-center py-4">No token usage yet</p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('client.tokens.purchase') }}" class="p-4 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition text-center">
                <svg class="w-8 h-8 text-teal-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-slate-900">Purchase Tokens</p>
                <p class="text-xs text-slate-600 mt-1">Buy more AI tokens</p>
            </a>
            <a href="{{ route('client.tokens.usage') }}" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition text-center">
                <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="font-semibold text-slate-900">View Usage</p>
                <p class="text-xs text-slate-600 mt-1">Detailed statistics</p>
            </a>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg text-center">
                <svg class="w-8 h-8 text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907C12.653 13.104 12.2 13.54 12.2 14.093M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-slate-900">Need Help?</p>
                <p class="text-xs text-slate-600 mt-1">Contact support</p>
            </div>
        </div>
    </div>
</div>
@endsection

