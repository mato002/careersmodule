@extends('layouts.client')

@section('title', 'Token Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Token Management</h1>
            <p class="text-slate-600 mt-1">Monitor and manage your AI token usage</p>
        </div>
        <a href="{{ route('client.tokens.purchase') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold">
            Purchase Tokens
        </a>
    </div>

    <!-- Token Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Remaining</h3>
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-slate-900">{{ number_format($balance['remaining']) }}</p>
            <p class="text-sm text-slate-500 mt-1">Available tokens</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Total Allocated</h3>
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-slate-900">{{ number_format($balance['total_allocated']) }}</p>
            <p class="text-sm text-slate-500 mt-1">All tokens received</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Used</h3>
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold text-slate-900">{{ number_format($balance['used']) }}</p>
            <p class="text-sm text-slate-500 mt-1">Tokens consumed</p>
        </div>
    </div>

    <!-- Usage Progress -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Usage Overview</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-slate-600">Usage Progress</span>
                    <span class="font-semibold text-slate-900">{{ $balance['percentage_used'] }}% used</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-4">
                    <div class="bg-teal-600 h-4 rounded-full transition-all" style="width: {{ $balance['percentage_used'] }}%"></div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                <div>
                    <p class="text-sm text-slate-600">This Month Usage</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($usageStats['total_tokens']) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Estimated Cost</p>
                    <p class="text-2xl font-bold text-slate-900">${{ number_format($usageStats['total_cost'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Usage Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Recent Usage</h3>
            <a href="{{ route('client.tokens.usage') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All →</a>
        </div>
        @if($recentLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Date</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Operation</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Application</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Tokens</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-3 px-4 text-sm text-slate-900 capitalize">{{ str_replace('_', ' ', $log->operation_type) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $log->jobApplication->full_name ?? 'System' }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-teal-700 text-right">{{ number_format($log->tokens_used) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $recentLogs->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500 text-center py-8">No token usage recorded yet</p>
        @endif
    </div>

    <!-- Allocations -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Token Allocations</h3>
        @if($allocations->count() > 0)
            <div class="space-y-3">
                @foreach($allocations as $allocation)
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-slate-900">{{ number_format($allocation->allocated_tokens) }} tokens</p>
                                <p class="text-sm text-slate-600 mt-1">Allocated: {{ $allocation->allocated_at->format('M d, Y') }}</p>
                                @if($allocation->expires_at)
                                    <p class="text-xs text-slate-500 mt-1">Expires: {{ $allocation->expires_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-900">{{ number_format($allocation->remaining_tokens) }} remaining</p>
                                <p class="text-xs text-slate-500 mt-1 capitalize">{{ $allocation->status }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500 text-center py-8">No token allocations yet</p>
        @endif
    </div>
</div>
@endsection

