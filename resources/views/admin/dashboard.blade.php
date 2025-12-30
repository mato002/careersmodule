@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header-description', "Welcome back! Here's a quick overview of the platform.")

@section('header-actions')
    @php
        // Ensure we always have a $user variable available, even if controller didn't pass it
        if (! isset($user)) {
            $user = auth()->user();
        }

        // Determine range from request to avoid relying on a controller variable
        $requestedRange = (int) request('range', 30);
        $allowedRanges = [7, 30, 90];
        $currentRange = in_array($requestedRange, $allowedRanges, true) ? $requestedRange : 30;

        // Safe defaults for analytics collections if controller doesn't provide them
        if (! isset($jobApplicationsTrend)) {
            $jobApplicationsTrend = collect();
        }
        if (! isset($contactMessagesTrend)) {
            $contactMessagesTrend = collect();
        }
    @endphp
    <form method="GET" action="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 mr-3">
        <span class="text-xs font-medium text-slate-500">Range:</span>
        @foreach ([7 => '7d', 30 => '30d', 90 => '90d'] as $value => $label)
            <button
                type="submit"
                name="range"
                value="{{ $value }}"
                class="px-3 py-1 rounded-full text-xs font-semibold border
                    {{ $currentRange === $value
                        ? 'bg-slate-900 text-white border-slate-900'
                        : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </form>
    <button class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50" onclick="window.location.reload()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        Refresh
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Analytics Overview -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">Activity (last {{ $currentRange }} days)</h2>
                <p class="text-xs text-slate-500">Job applications & contact messages over time</p>
            </div>
            <div class="h-72">
                <canvas id="activityChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Statistics Grid 1: Messages -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <a href="{{ route('admin.contact-messages.index') }}" class="group relative block bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Unread Messages</p>
                    <p class="text-4xl font-bold text-amber-600">{{ $stats['unread_messages'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.contact-messages.index') }}" class="group relative block bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Total Messages</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $stats['total_messages'] }}</p>
                </div>
            </a>
        </div>

        <!-- Statistics Grid 2: Job Applications -->
        @if($user && $user->canAccessCareers())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <a href="{{ route('admin.job-applications.index') }}" class="group relative block bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Pending Job Applications</p>
                    <p class="text-4xl font-bold text-amber-600">{{ $stats['pending_job_applications'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.job-applications.index') }}" class="group relative block bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Total Job Applications</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $stats['total_job_applications'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.job-applications.index') }}" class="group relative block bg-gradient-to-br from-white to-blue-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-blue-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Shortlisted Candidates</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $stats['shortlisted_job_applications'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.job-applications.index') }}" class="group relative block bg-gradient-to-br from-white to-green-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-green-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Hired Candidates</p>
                    <p class="text-4xl font-bold text-green-600">{{ $stats['hired_job_applications'] }}</p>
                </div>
            </a>
        </div>
        @endif

        <!-- Statistics Grid 3: Job Posts -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <a href="{{ route('admin.jobs.index') }}" class="group relative block bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-emerald-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Active Job Posts</p>
                    <p class="text-4xl font-bold text-emerald-700">{{ $stats['active_job_posts'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="group relative block bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Total Job Posts</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $stats['total_job_posts'] }}</p>
                </div>
            </a>
            <a href="{{ route('admin.team-members.index') }}" class="group relative block bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">Team Members</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $stats['team_members'] }}</p>
                </div>
            </a>
            @if($user && $user->isAdmin())
                <div class="group relative bg-gradient-to-br from-white to-indigo-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-indigo-200/60 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Admin Users</p>
                        <p class="text-4xl font-bold text-indigo-600">{{ $stats['admin_users'] }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Lists Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Contact Messages -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Contact Messages</h2>
                    </div>
                    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors">View all →</a>
                </div>
                <div class="divide-y divide-slate-100" id="messages-list">
                    @forelse ($recentMessages->take(5) as $message)
                        <a href="{{ route('admin.contact-messages.show', $message) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                            <p class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $message->name }}</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $message->subject ?? 'No subject' }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $message->created_at->format('M d, Y g:i A') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 py-6 text-center">No messages yet.</p>
                    @endforelse
                    @if($recentMessages->count() > 5)
                        <div class="hidden" id="messages-more">
                            @foreach($recentMessages->skip(5) as $message)
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                                    <p class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $message->name }}</p>
                                    <p class="text-sm text-slate-600 mt-1">{{ $message->subject ?? 'No subject' }}</p>
                                    <p class="text-xs text-slate-400 mt-2">{{ $message->created_at->format('M d, Y g:i A') }}</p>
                                </a>
                            @endforeach
                        </div>
                        <button onclick="toggleList('messages')" class="mt-4 text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors" id="messages-toggle">
                            Show More ({{ $recentMessages->count() - 5 }}) →
                        </button>
                    @endif
                </div>
            </div>

            <!-- Recent Job Applications -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Job Applications</h2>
                    </div>
                    <a href="{{ route('admin.job-applications.index') }}" class="text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors">View all →</a>
                </div>
                <div class="divide-y divide-slate-100" id="job-applications-list">
                    @forelse ($recentJobApplications->take(5) as $application)
                        <a href="{{ route('admin.job-applications.show', $application) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                            <p class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $application->name }}</p>
                            <p class="text-sm text-slate-600 mt-1">
                                {{ optional($application->jobPost)->title ?? 'Unknown Position' }}
                            </p>
                            <p class="text-xs text-slate-400 mt-2">{{ $application->created_at->format('M d, Y g:i A') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 py-6 text-center">No job applications yet.</p>
                    @endforelse
                    @if($recentJobApplications->count() > 5)
                        <div class="hidden" id="job-applications-more">
                            @foreach($recentJobApplications->skip(5) as $application)
                                <a href="{{ route('admin.job-applications.show', $application) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                                    <p class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $application->name }}</p>
                                    <p class="text-sm text-slate-600 mt-1">
                                        {{ optional($application->jobPost)->title ?? 'Unknown Position' }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-2">{{ $application->created_at->format('M d, Y g:i A') }}</p>
                                </a>
                            @endforeach
                        </div>
                        <button onclick="toggleList('job-applications')" class="mt-4 text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors" id="job-applications-toggle">
                            Show More ({{ $recentJobApplications->count() - 5 }}) →
                        </button>
                    @endif
                </div>
            </div>

            <!-- Latest Job Posts -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Latest Job Posts</h2>
                    </div>
                    <a href="{{ route('admin.jobs.index') }}" class="text-sm text-purple-600 font-semibold hover:text-purple-700 transition-colors">Manage →</a>
                </div>
                <div class="divide-y divide-slate-100" id="job-posts-list">
                    @forelse ($latestJobPosts->take(5) as $jobPost)
                        <div class="py-4 px-3 -mx-3 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                            <p class="font-semibold text-slate-900">{{ $jobPost->title }}</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $jobPost->department ?? 'General' }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $jobPost->created_at->format('M d, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-6 text-center">No job posts yet.</p>
                    @endforelse
                    @if($latestJobPosts->count() > 5)
                        <div class="hidden" id="job-posts-more">
                            @foreach($latestJobPosts->skip(5) as $jobPost)
                                <div class="py-4 px-3 -mx-3 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                                    <p class="font-semibold text-slate-900">{{ $jobPost->title }}</p>
                                    <p class="text-sm text-slate-600 mt-1">{{ $jobPost->department ?? 'General' }}</p>
                                    <p class="text-xs text-slate-400 mt-2">{{ $jobPost->created_at->format('M d, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                        <button onclick="toggleList('job-posts')" class="mt-4 text-sm text-purple-600 font-semibold hover:text-purple-700 transition-colors" id="job-posts-toggle">
                            Show More ({{ $latestJobPosts->count() - 5 }}) →
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Recent Activity Logs</h2>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-slate-600 font-semibold hover:text-slate-700 transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-slate-100" id="activity-logs-list">
                @forelse ($recentActivityLogs->take(5) as $log)
                    <a href="{{ route('admin.activity-logs.show', $log) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        @class([
                                            'bg-green-100 text-green-700 border border-green-200' => in_array($log->action, ['login', 'create']),
                                            'bg-blue-100 text-blue-700 border border-blue-200' => in_array($log->action, ['update', 'view']),
                                            'bg-red-100 text-red-700 border border-red-200' => in_array($log->action, ['delete', 'logout', 'login_failed']),
                                            'bg-amber-100 text-amber-700 border border-amber-200' => !in_array($log->action, ['login', 'create', 'update', 'view', 'delete', 'logout', 'login_failed']),
                                        ])">
                                        {{ \Illuminate\Support\Str::headline($log->action) }}
                                    </span>
                                    @if($log->user)
                                        <span class="text-sm font-semibold text-slate-900">{{ $log->user->name }}</span>
                                    @else
                                        <span class="text-sm text-slate-500 italic">System</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700 line-clamp-1 group-hover:text-slate-900 transition-colors">{{ $log->description }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $log->created_at->format('M d, Y g:i A') }} • {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500 py-6 text-center">No activity logs yet.</p>
                @endforelse
                @if($recentActivityLogs->count() > 5)
                    <div class="hidden" id="activity-logs-more">
                        @foreach($recentActivityLogs->skip(5) as $log)
                            <a href="{{ route('admin.activity-logs.show', $log) }}" class="py-4 block hover:bg-slate-50 rounded-lg px-3 -mx-3 transition-colors duration-200 group">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                @class([
                                                    'bg-green-100 text-green-700 border border-green-200' => in_array($log->action, ['login', 'create']),
                                                    'bg-blue-100 text-blue-700 border border-blue-200' => in_array($log->action, ['update', 'view']),
                                                    'bg-red-100 text-red-700 border border-red-200' => in_array($log->action, ['delete', 'logout', 'login_failed']),
                                                    'bg-amber-100 text-amber-700 border border-amber-200' => !in_array($log->action, ['login', 'create', 'update', 'view', 'delete', 'logout', 'login_failed']),
                                                ])">
                                                {{ \Illuminate\Support\Str::headline($log->action) }}
                                            </span>
                                            @if($log->user)
                                                <span class="text-sm font-semibold text-slate-900">{{ $log->user->name }}</span>
                                            @else
                                                <span class="text-sm text-slate-500 italic">System</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-700 line-clamp-1 group-hover:text-slate-900 transition-colors">{{ $log->description }}</p>
                                        <p class="text-xs text-slate-400 mt-2">{{ $log->created_at->format('M d, Y g:i A') }} • {{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <button onclick="toggleList('activity-logs')" class="mt-4 text-sm text-slate-600 font-semibold hover:text-slate-700 transition-colors" id="activity-logs-toggle">
                        Show More ({{ $recentActivityLogs->count() - 5 }}) →
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const activityCtx = document.getElementById('activityChart').getContext('2d');

        const dates = {!! json_encode(
            collect($jobApplicationsTrend)
                ->merge($contactMessagesTrend)
                ->pluck('date')
                ->unique()
                ->sort()
                ->values()
        ) !!};

        const jobData = dates.map(date => {
            const item = {!! $jobApplicationsTrend->keyBy('date')->toJson() !!}[date];
            return item ? item.count : 0;
        });

        const messageData = dates.map(date => {
            const item = {!! $contactMessagesTrend->keyBy('date')->toJson() !!}[date];
            return item ? item.count : 0;
        });

        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Job Applications',
                        data: jobData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Contact Messages',
                        data: messageData,
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.3,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            maxTicksLimit: 10,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: true,
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                },
            },
        });

        function toggleList(listName) {
            const moreDiv = document.getElementById(listName + '-more');
            const toggleBtn = document.getElementById(listName + '-toggle');
            
            if (moreDiv && toggleBtn) {
                if (moreDiv.classList.contains('hidden')) {
                    moreDiv.classList.remove('hidden');
                    toggleBtn.textContent = 'Show Less';
                } else {
                    moreDiv.classList.add('hidden');
                    const count = moreDiv.querySelectorAll('a, div').length;
                    toggleBtn.textContent = 'Show More (' + count + ')';
                }
            }
        }
    </script>
@endsection
