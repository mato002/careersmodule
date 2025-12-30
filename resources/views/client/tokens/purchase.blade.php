@extends('layouts.client')

@section('title', 'Purchase Tokens')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Purchase Tokens</h1>
        <p class="text-slate-600 mt-1">Choose a token package that fits your needs</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $package['name'] }}</h3>
                    <p class="text-sm text-slate-600 mb-4">{{ $package['description'] }}</p>
                    <div class="mb-4">
                        <p class="text-3xl font-bold text-teal-600">${{ number_format($package['price'], 2) }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ number_format($package['tokens']) }} tokens</p>
                        <p class="text-xs text-slate-400 mt-1">${{ number_format($package['cost_per_token'], 4) }} per token</p>
                    </div>
                    <form method="POST" action="{{ route('client.tokens.purchase.store') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="package" value="{{ strtolower($package['name']) }}">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                                <select name="payment_method" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold transition">
                                Purchase Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-900 mb-2">Payment Information</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Credit/Debit Card: Tokens will be allocated immediately after payment confirmation</li>
            <li>• Bank Transfer: Tokens will be allocated after payment verification (1-2 business days)</li>
            <li>• All tokens are valid for 12 months from purchase date</li>
            <li>• Unused tokens can be carried over to the next billing cycle</li>
        </ul>
    </div>
</div>
@endsection

