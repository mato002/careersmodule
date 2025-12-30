@extends('layouts.admin')

@section('title', 'Token Purchases')

@section('header-description', 'Manage token purchases and allocations.')

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

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.tokens.index') }}" class="inline-flex items-center gap-2 text-sm text-teal-600 hover:text-teal-700 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Token Management
        </a>
        <button onclick="openPurchaseModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Purchase
        </button>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Token Purchases</h3>
        
        @if($purchases->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Purchase Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Total Tokens</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Cost per Token</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Total Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Allocated</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Remaining</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Provider</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($purchases as $purchase)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-900">
                                    {{ $purchase->purchase_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                    {{ number_format($purchase->total_tokens) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    ${{ number_format($purchase->cost_per_token, 8) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-900">
                                    ${{ number_format($purchase->total_cost, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ number_format($purchase->total_allocated_tokens) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold {{ $purchase->remaining_tokens > 0 ? 'text-teal-600' : 'text-slate-600' }}">
                                    {{ number_format($purchase->remaining_tokens) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ ucfirst($purchase->provider) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $purchase->status === 'active' ? 'bg-green-100 text-green-800' : ($purchase->status === 'exhausted' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $purchases->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-500 text-lg font-medium">No token purchases yet</p>
                <p class="text-slate-400 text-sm mt-2">Create your first token purchase to start allocating tokens to companies.</p>
                <button onclick="openPurchaseModal()" class="mt-4 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium">
                    Create Purchase
                </button>
            </div>
        @endif
    </div>

    <!-- Create Purchase Modal -->
    <div id="purchase-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePurchaseModal()"></div>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.tokens.purchases.create') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Create Token Purchase</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Purchase Date</label>
                                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Total Tokens</label>
                                <input type="number" name="total_tokens" class="w-full rounded-lg border border-slate-300 px-3 py-2" min="1" step="1" required>
                                <p class="text-xs text-slate-500 mt-1">Enter the total number of tokens purchased</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Cost per Token</label>
                                <input type="number" name="cost_per_token" class="w-full rounded-lg border border-slate-300 px-3 py-2" min="0" step="0.00000001" required>
                                <p class="text-xs text-slate-500 mt-1">e.g., 0.00003 for $0.00003 per token</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Provider</label>
                                <select name="provider" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                                    <option value="openai">OpenAI</option>
                                    <option value="anthropic">Anthropic</option>
                                    <option value="custom">Custom</option>
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
                            Create Purchase
                        </button>
                        <button type="button" onclick="closePurchaseModal()" class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPurchaseModal() {
            document.getElementById('purchase-modal').classList.remove('hidden');
        }

        function closePurchaseModal() {
            document.getElementById('purchase-modal').classList.add('hidden');
        }
    </script>
@endsection

