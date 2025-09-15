@extends('layouts.app')

@section('title', 'My Trade Statistics')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 rounded-3xl opacity-5"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">My Trade Statistics</h1>
                        <p class="text-lg text-gray-600">Comprehensive overview of your trading activity and performance.</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Total Portfolio Value</div>
                            <div class="text-2xl font-bold text-green-600">
                                ${{ number_format($stats['total_volume'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Trades -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Trades</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_trades'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Volume -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Volume</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_volume'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Buy Trades -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Buy Trades</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['buy_trades'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sell Trades -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sell Trades</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['sell_trades'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Fees Paid -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Fees Paid</h3>
                        <p class="text-sm text-gray-600">Trading fees incurred</p>
                    </div>
                </div>
                <div class="text-3xl font-bold text-yellow-600 mb-2">
                    ${{ number_format($stats['total_fees'] ?? 0, 2) }}
                </div>
                <div class="text-sm text-gray-500">
                    @if(($stats['total_volume'] ?? 0) > 0)
                        {{ number_format((($stats['total_fees'] ?? 0) / $stats['total_volume']) * 100, 3) }}% of total volume
                    @else
                        No trading activity yet
                    @endif
                </div>
            </div>

            <!-- Average Trade Size -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Average Trade Size</h3>
                        <p class="text-sm text-gray-600">Average value per trade</p>
                    </div>
                </div>
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    @if(($stats['total_trades'] ?? 0) > 0)
                        ${{ number_format(($stats['total_volume'] ?? 0) / $stats['total_trades'], 2) }}
                    @else
                        $0.00
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    @if(($stats['total_trades'] ?? 0) > 0)
                        Based on {{ $stats['total_trades'] }} trades
                    @else
                        No trading activity yet
                    @endif
                </div>
            </div>
        </div>

        <!-- Market Performance -->
        @if(isset($stats['by_market']) && count($stats['by_market']) > 0)
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Market Performance</h2>
                    <p class="text-gray-600">Your trading activity across different markets</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Market</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trades</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Trade Size</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['by_market'] as $market)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">
                                            {{ substr($market['base_asset'], 0, 1) }}{{ substr($market['quote_asset'], 0, 1) }}
                                        </span>
                                    </div>
                                    {{ $market['base_asset'] }}/{{ $market['quote_asset'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($market['count']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($market['volume'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($market['volume'] / $market['count'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- No Data State -->
        @if(($stats['total_trades'] ?? 0) == 0)
        <div class="bg-white rounded-2xl shadow-xl p-12 border border-gray-100 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Trading Activity Yet</h3>
            <p class="text-gray-600 mb-6">You haven't made any trades yet. Start trading to see your statistics here.</p>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Place Your First Order
            </a>
        </div>
        @endif

        <!-- Action Buttons -->
        @if(($stats['total_trades'] ?? 0) > 0)
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('trades.my-trades') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View My Trades
            </a>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Place New Order
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
