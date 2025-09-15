@extends('layouts.app')

@section('title', 'Trade Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Trade #{{ $trade->id }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Detailed information about this trade execution.</p>
                </div>
                <a href="{{ route('trades.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    ← Back to Trades
                </a>
            </div>
        </div>

        <!-- Trade Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Trade Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600 mb-1">Market</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ $trade->market->base_asset }}/{{ $trade->market->quote_asset }}
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600 mb-1">Price</div>
                    <div class="text-lg font-semibold text-gray-900">
                        ${{ number_format($trade->price, 8) }}
                    </div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-purple-600 mb-1">Quantity</div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ number_format($trade->quantity, 8) }}
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-600 mb-1">Total Value</div>
                    <div class="text-lg font-semibold text-gray-900">
                        ${{ number_format($trade->price * $trade->quantity, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Trade Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Buyer Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Buyer Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-green-700">
                                    {{ substr($trade->buyOrder->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $trade->buyOrder->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $trade->buyOrder->user->email }}</div>
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="text-sm text-gray-600">Order ID: {{ $trade->buyOrder->id }}</div>
                        <div class="text-sm text-gray-600">Order Type: {{ ucfirst($trade->buyOrder->order_type) }}</div>
                        <div class="text-sm text-gray-600">Order Kind: {{ ucfirst($trade->buyOrder->order_kind) }}</div>
                        @if($trade->buyOrder->order_kind === 'limit')
                            <div class="text-sm text-gray-600">Limit Price: ${{ number_format($trade->buyOrder->price, 8) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Seller Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Seller Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-red-700">
                                    {{ substr($trade->sellOrder->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $trade->sellOrder->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $trade->sellOrder->user->email }}</div>
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="text-sm text-gray-600">Order ID: {{ $trade->sellOrder->id }}</div>
                        <div class="text-sm text-gray-600">Order Type: {{ ucfirst($trade->sellOrder->order_type) }}</div>
                        <div class="text-sm text-gray-600">Order Kind: {{ ucfirst($trade->sellOrder->order_kind) }}</div>
                        @if($trade->sellOrder->order_kind === 'limit')
                            <div class="text-sm text-gray-600">Limit Price: ${{ number_format($trade->sellOrder->price, 8) }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Trade Metadata -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Trade ID</dt>
                            <dd class="text-sm text-gray-900">{{ $trade->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Market</dt>
                            <dd class="text-sm text-gray-900">{{ $trade->market->base_asset }}/{{ $trade->market->quote_asset }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Execution Price</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($trade->price, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($trade->quantity, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Value</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($trade->price * $trade->quantity, 2) }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Trading Fee</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($trade->fee, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Trade Time</dt>
                            <dd class="text-sm text-gray-900">{{ $trade->trade_time->format('F d, Y \a\t H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Time Ago</dt>
                            <dd class="text-sm text-gray-900">{{ $trade->trade_time->diffForHumans() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee Rate</dt>
                            <dd class="text-sm text-gray-900">{{ $trade->market->fee_rate }}%</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
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
