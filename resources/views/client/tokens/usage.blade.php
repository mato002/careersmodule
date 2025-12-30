@extends('layouts.client')

@section('title', 'Token Usage')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Token Usage</h1>
            <p class="text-slate-600 mt-1">Detailed usage statistics and logs</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('client.tokens.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium text-slate-700">
                Back to Tokens
            </a>
            <a href="{{ route('client.tokens.purchase') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold">
                Purchase Tokens
            </a>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('client.tokens.usage') }}" class="flex items-center gap-4">
            <label class="text-sm font-medium text-slate-700">Period:</label>
            <select name="period" onchange="this.form.submit()" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
            </select>
        </form>
    </div>

    <!-- Usage Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-600 mb-1">Total Tokens</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($usageStats['total_tokens']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-600 mb-1">Operations</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($usageStats['operations_count']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-600 mb-1">Total Cost</p>
            <p class="text-3xl font-bold text-slate-900">${{ number_format($usageStats['total_cost'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-600 mb-1">Avg per Operation</p>
            <p class="text-3xl font-bold text-slate-900">{{ $usageStats['operations_count'] > 0 ? number_format($usageStats['total_tokens'] / $usageStats['operations_count']) : 0 }}</p>
        </div>
    </div>

    <!-- Usage Breakdown -->
    @if(isset($usageStats['breakdown']) && count($usageStats['breakdown']) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Usage by Operation Type</h3>
            <div class="space-y-3">
                @foreach($usageStats['breakdown'] as $type => $data)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $type) }}</p>
                            <p class="text-sm text-slate-600">{{ number_format($data['count']) }} operations</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-teal-700">{{ number_format($data['tokens']) }} tokens</p>
                            <p class="text-sm text-slate-600">${{ number_format($data['cost'], 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Detailed Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Usage Logs</h3>
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Date & Time</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Operation</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Application</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Tokens</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-slate-700">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                <td class="py-3 px-4 text-sm text-slate-900 capitalize">{{ str_replace('_', ' ', $log->operation_type) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ $log->jobApplication->full_name ?? 'System Operation' }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-teal-700 text-right">{{ number_format($log->tokens_used) }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600 text-right">${{ number_format($log->total_cost ?? 0, 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500 text-center py-8">No usage logs found for this period</p>
        @endif
    </div>
</div>
@endsection

