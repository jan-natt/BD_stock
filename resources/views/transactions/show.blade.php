@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Transaction #{{ $transaction->id }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Detailed information about this financial transaction.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('transactions.edit', $transaction) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Edit Transaction
                    </a>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        ← Back to Transactions
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600 mb-1">Transaction Type</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600 mb-1">Amount</div>
                    <div class="text-lg font-semibold text-gray-900">
                        ${{ number_format($transaction->amount, 8) }}
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-600 mb-1">Fee</div>
                    <div class="text-lg font-semibold text-gray-900">
                        ${{ $transaction->fee ? number_format($transaction->fee, 8) : '0.00' }}
                    </div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-purple-600 mb-1">Status</div>
                    <div class="text-lg font-semibold text-gray-900">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    User Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-700">
                                    {{ substr($transaction->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="text-sm text-gray-600">User ID: {{ $transaction->user->id }}</div>
                        <div class="text-sm text-gray-600">Joined: {{ $transaction->user->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Wallet Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Wallet Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-green-700">
                                    {{ substr($transaction->wallet->currency, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->wallet->currency }}</div>
                            <div class="text-sm text-gray-500">Wallet ID: {{ $transaction->wallet->id }}</div>
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="text-sm text-gray-600">Current Balance: ${{ number_format($transaction->wallet->balance, 8) }}</div>
                        <div class="text-sm text-gray-600">Status: {{ $transaction->wallet->is_locked ? 'Locked' : 'Active' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Metadata -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction Hash</dt>
                            <dd class="text-sm text-gray-900 font-mono">
                                @if($transaction->transaction_hash)
                                    {{ $transaction->transaction_hash }}
                                @else
                                    <span class="text-gray-400">Not available</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('F d, Y \a\t H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->updated_at->format('F d, Y \a\t H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction Type</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($transaction->amount, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee</dt>
                            <dd class="text-sm text-gray-900">${{ $transaction->fee ? number_format($transaction->fee, 8) : '0.00' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($transaction->amount + ($transaction->fee ?? 0), 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
