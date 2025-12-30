@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('header-description', 'Monitor admin activities and track user actions in the system.')

@section('header-actions')
    <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="hidden sm:inline">Refresh</span>
    </a>
@endsection

@section('content')
    @php
        $hasActiveFilters = request()->hasAny(['search', 'action', 'user_id', 'date_from', 'date_to']);
        $activeFilterCount = 0;
        if (request('search')) $activeFilterCount++;
        if (request('action')) $activeFilterCount++;
        if (request('user_id')) $activeFilterCount++;
        if (request('date_from')) $activeFilterCount++;
        if (request('date_to')) $activeFilterCount++;
    @endphp

    <!-- Filters Section -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 mb-4 sm:mb-6 overflow-hidden">
        <!-- Filter Header -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    @if($hasActiveFilters)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">
                            {{ $activeFilterCount }} Active
                        </span>
                    @endif
                </div>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset All Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Search
                        @if(request('search'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search description or action..." 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Action
                        @if(request('action'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <select name="action" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ Str::headline($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        User
                        @if(request('user_id'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <select name="user_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Date From
                        @if(request('date_from'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Date To
                        @if(request('date_to'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                </div>
            </div>

            <!-- Filter Action Buttons -->
            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply Filters
                </button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear All
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Results Count Banner -->
    @if($hasActiveFilters)
    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-4 sm:p-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-1">
                    {{ $logs->total() }} Log{{ $logs->total() !== 1 ? 's' : '' }} Found
                </h2>
                <p class="text-teal-100 text-sm">Matching your filter criteria</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" 
               class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition whitespace-nowrap">
                View All Logs
            </a>
        </div>
    </div>
    @endif

    <!-- Active Filters Display -->
    @if($hasActiveFilters)
    <div class="bg-teal-50 border border-teal-200 rounded-xl sm:rounded-2xl p-4 mb-4 sm:mb-6">
        <div class="flex items-center flex-wrap gap-2">
            <span class="text-sm font-semibold text-teal-900">Active Filters:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">Search:</span>
                    <span class="font-semibold">"{{ request('search') }}"</span>
                    <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
            @if(request('action'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">Action:</span>
                    <span class="font-semibold">{{ Str::headline(request('action')) }}</span>
                    <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('action'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
            @if(request('user_id'))
                @php
                    $selectedUser = $users->firstWhere('id', request('user_id'));
                @endphp
                @if($selectedUser)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                        <span class="font-medium">User:</span>
                        <span class="font-semibold">{{ $selectedUser->name }}</span>
                        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('user_id'), ['page' => 1])) }}" 
                           class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                @endif
            @endif
            @if(request('date_from'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">From:</span>
                    <span class="font-semibold">{{ date('M d, Y', strtotime(request('date_from'))) }}</span>
                    <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('date_from'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
            @if(request('date_to'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">To:</span>
                    <span class="font-semibold">{{ date('M d, Y', strtotime(request('date_to'))) }}</span>
                    <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('date_to'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[800px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3 text-left">User</th>
                    <th class="px-3 sm:px-6 py-3 text-left">Action</th>
                    <th class="px-3 sm:px-6 py-3 text-left hidden md:table-cell">Description</th>
                    <th class="px-3 sm:px-6 py-3 text-left hidden lg:table-cell">IP Address</th>
                    <th class="px-3 sm:px-6 py-3 text-left">Date & Time</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 sm:px-6 py-4">
                            @if($log->user)
                                <div>
                                    <div class="font-semibold text-gray-900 text-sm sm:text-base">{{ $log->user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[150px]">{{ $log->user->email }}</div>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs sm:text-sm">System / Guest</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-green-100 text-green-800' => in_array($log->action, ['login', 'create']),
                                    'bg-blue-100 text-blue-800' => in_array($log->action, ['update', 'view']),
                                    'bg-red-100 text-red-800' => in_array($log->action, ['delete', 'logout', 'login_failed']),
                                    'bg-amber-100 text-amber-800' => !in_array($log->action, ['login', 'create', 'update', 'view', 'delete', 'logout', 'login_failed']),
                                ])">
                                {{ Str::headline($log->action) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-700 max-w-md hidden md:table-cell">
                            <div class="truncate text-xs sm:text-sm" title="{{ $log->description }}">
                                {{ $log->description }}
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs font-mono hidden lg:table-cell">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600">
                            <div class="text-xs sm:text-sm">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at->format('g:i A') }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.activity-logs.show', $log) }}" 
                                   class="text-teal-700 font-semibold hover:text-teal-800 text-xs sm:text-sm">View</a>
                                @if($log->ip_address && !in_array($log->ip_address, $blockedIps))
                                    <form action="{{ route('admin.activity-logs.block-ip', $log) }}" method="POST" class="inline activity-log-block-ip-form" data-ip="{{ $log->ip_address }}">
                                        @csrf
                                        <button type="submit" class="text-red-600 font-semibold hover:text-red-700 text-xs sm:text-sm" title="Block IP Address">
                                            Block IP
                                        </button>
                                    </form>
                                @elseif($log->ip_address && in_array($log->ip_address, $blockedIps))
                                    <span class="text-xs text-gray-400">IP Blocked</span>
                                @endif
                                @if($log->user_id && !($log->user->is_banned ?? false))
                                    <form action="{{ route('admin.activity-logs.ban-user', $log) }}" method="POST" class="inline activity-log-ban-user-form" data-email="{{ $log->user->email }}">
                                        @csrf
                                        <button type="submit" class="text-red-600 font-semibold hover:text-red-700 text-xs sm:text-sm" title="Ban User">
                                            Ban User
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>No activity logs found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 sm:mt-6">
        {{ $logs->appends(request()->query())->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert confirmation for blocking IPs from index table
        document.querySelectorAll('.activity-log-block-ip-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const ip = this.getAttribute('data-ip') || 'this IP address';

                Swal.fire({
                    title: 'Block IP address?',
                    text: `You are about to block IP ${ip}. This may prevent this user from accessing the admin area.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, block IP',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Blocking IP...',
                            text: 'Please wait while we apply this restriction.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        // SweetAlert confirmation for banning users from index table
        document.querySelectorAll('.activity-log-ban-user-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const email = this.getAttribute('data-email') || 'this user';

                Swal.fire({
                    title: 'Ban this user?',
                    text: `You are about to ban ${email}. All of their active sessions will be revoked.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, ban user',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Banning user...',
                            text: 'Please wait while we revoke all sessions.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

