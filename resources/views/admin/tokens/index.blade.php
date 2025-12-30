@extends('layouts.admin')

@section('title', 'Token Management')

@section('header-description', 'Monitor and manage AI token usage and allocations.')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Token Balance Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Token Balance</h3>
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($balance['remaining']) }}</p>
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
                    <div class="w-full bg-slate-200 rounded-full h-2 mt-3">
                        <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $balance['percentage_used'] }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">{{ $balance['percentage_used'] }}% used, {{ $balance['percentage_remaining'] }}% remaining</p>
                </div>
            </div>
        </div>

        <!-- Usage This Month -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">This Month</h3>
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($usageStats['total_tokens']) }}</p>
                    <p class="text-sm text-slate-500">Tokens Used</p>
                </div>
                <div class="pt-3 border-t border-slate-200">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-600">Operations</span>
                        <span class="font-semibold text-slate-900">{{ number_format($usageStats['operations_count']) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Total Cost</span>
                        <span class="font-semibold text-slate-900">${{ number_format($usageStats['total_cost'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Quick Actions</h3>
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.tokens.usage') }}" class="block w-full px-4 py-2 text-sm font-medium text-teal-700 bg-teal-50 rounded-lg hover:bg-teal-100 transition">
                    View Usage Details
                </a>
                <a href="{{ route('admin.tokens.purchases') }}" class="block w-full px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                    Manage Purchases
                </a>
                <button onclick="openAllocateModal()" class="block w-full px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    Allocate Tokens
                </button>
            </div>
        </div>
    </div>

    <!-- Usage Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Usage Breakdown by Operation</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($usageStats['by_operation'] as $operation => $stats)
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-slate-600 mb-2">{{ ucfirst(str_replace('_', ' ', $operation)) }}</p>
                    <p class="text-2xl font-bold text-slate-900 mb-1">{{ number_format($stats['tokens']) }}</p>
                    <p class="text-xs text-slate-500">Tokens</p>
                    <p class="text-sm font-semibold text-slate-700 mt-2">${{ number_format($stats['cost'], 2) }}</p>
                    <p class="text-xs text-slate-500">{{ $stats['count'] }} operations</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Usage Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Recent Usage</h3>
            <a href="{{ route('admin.tokens.usage') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                View All →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Operation</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Tokens</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Model</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($recentLogs as $log)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-900">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $log->operation_type)) }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ number_format($log->tokens_used) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">${{ number_format($log->total_cost, 4) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $log->model_used }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">No usage logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Allocations -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Token Allocations</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Allocated</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Used</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Expires</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($allocations as $allocation)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ number_format($allocation->allocated_tokens) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($allocation->used_tokens) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-teal-600">{{ number_format($allocation->remaining_tokens) }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $allocation->status === 'active' ? 'bg-green-100 text-green-800' : ($allocation->status === 'exhausted' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $allocation->expires_at ? $allocation->expires_at->format('M d, Y') : 'Never' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">No allocations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Allocate Tokens Modal -->
    <div id="allocate-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAllocateModal()"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.tokens.allocate') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Allocate Tokens</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                                <select name="company_id" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Token Amount</label>
                                <input type="number" name="token_amount" class="w-full rounded-lg border border-slate-300 px-3 py-2" min="1" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Token Purchase (Optional)</label>
                                <select name="token_purchase_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                                    <option value="">Select purchase...</option>
                                    @foreach(\App\Models\TokenPurchase::where('status', 'active')->get() as $purchase)
                                        <option value="{{ $purchase->id }}">{{ number_format($purchase->remaining_tokens) }} tokens available</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Expires At (Optional)</label>
                                <input type="date" name="expires_at" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes (Optional)</label>
                                <textarea name="notes" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full sm:w-auto sm:ml-3 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                            Allocate Tokens
                        </button>
                        <button type="button" onclick="closeAllocateModal()" class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAllocateModal() {
            document.getElementById('allocate-modal').classList.remove('hidden');
        }

        function closeAllocateModal() {
            document.getElementById('allocate-modal').classList.add('hidden');
        }
    </script>
@endsection

