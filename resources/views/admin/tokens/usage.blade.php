@extends('layouts.admin')

@section('title', 'Token Usage Details')

@section('header-description', 'Detailed view of AI token usage and consumption.')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.tokens.index') }}" class="inline-flex items-center gap-2 text-sm text-teal-600 hover:text-teal-700 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Token Management
        </a>
    </div>

    <!-- Period Selector -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Usage Statistics</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.tokens.usage', ['period' => 'week']) }}" 
                   class="px-4 py-2 text-sm rounded-lg {{ $period === 'week' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Week
                </a>
                <a href="{{ route('admin.tokens.usage', ['period' => 'month']) }}" 
                   class="px-4 py-2 text-sm rounded-lg {{ $period === 'month' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Month
                </a>
                <a href="{{ route('admin.tokens.usage', ['period' => 'year']) }}" 
                   class="px-4 py-2 text-sm rounded-lg {{ $period === 'year' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Year
                </a>
            </div>
        </div>
    </div>

    <!-- Usage Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-sm text-slate-600 mb-2">Total Tokens Used</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($usageStats['total_tokens']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-sm text-slate-600 mb-2">Total Cost</p>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($usageStats['total_cost'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-sm text-slate-600 mb-2">Operations</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($usageStats['operations_count']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-sm text-slate-600 mb-2">Avg Cost/Operation</p>
            <p class="text-3xl font-bold text-slate-900">
                ${{ $usageStats['operations_count'] > 0 ? number_format($usageStats['total_cost'] / $usageStats['operations_count'], 4) : '0.0000' }}
            </p>
        </div>
    </div>

    <!-- Usage Breakdown by Operation -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Usage Breakdown by Operation Type</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($usageStats['by_operation'] as $operation => $stats)
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-slate-600 mb-2">{{ ucfirst(str_replace('_', ' ', $operation)) }}</p>
                    <p class="text-2xl font-bold text-slate-900 mb-1">{{ number_format($stats['tokens']) }}</p>
                    <p class="text-xs text-slate-500 mb-2">Tokens</p>
                    <p class="text-sm font-semibold text-slate-700">${{ number_format($stats['cost'], 2) }}</p>
                    <p class="text-xs text-slate-500">{{ $stats['count'] }} operations</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Detailed Usage Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Detailed Usage Logs</h3>
            <p class="text-sm text-slate-500">Showing {{ $logs->total() }} total records</p>
        </div>
        
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Operation</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Application</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Input Tokens</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Output Tokens</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Total Tokens</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Model</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-900 whitespace-nowrap">
                                    {{ $log->created_at->format('M d, Y') }}<br>
                                    <span class="text-xs text-slate-500">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        {{ ucfirst(str_replace('_', ' ', $log->operation_type)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    @if($log->jobApplication)
                                        <a href="{{ route('admin.job-applications.show', $log->jobApplication) }}" class="text-teal-600 hover:text-teal-700">
                                            {{ $log->jobApplication->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($log->input_tokens) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($log->output_tokens) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ number_format($log->tokens_used) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-700">${{ number_format($log->total_cost, 4) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $log->model_used }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 text-lg font-medium">No usage logs found for this period</p>
                <p class="text-slate-400 text-sm mt-2">Token usage will appear here once AI operations are performed.</p>
            </div>
        @endif
    </div>
@endsection

