@extends('layouts.app')

@section('title', 'Trades Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 rounded-3xl opacity-5"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Trades Management</h1>
                        <p class="text-lg text-gray-600">Monitor and manage all trading activities on the platform.</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                {{ $trades->total() }} trades found
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Total Volume</div>
                            <div class="text-2xl font-bold text-green-600">
                                ${{ number_format($trades->sum(function($trade) { return $trade->price * $trade->quantity; }), 2) }}
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('trades.export', ['format' => 'csv'] + request()->query()) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('trades.export', ['format' => 'json'] + request()->query()) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export JSON
                    </a>
                    <a href="{{ route('trades.statistics') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        View Statistics
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Filters & Search</h2>
                <div class="h-1 flex-1 ml-4 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full"></div>
            </div>
            <form method="GET" action="{{ route('trades.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label for="market_id" class="block text-sm font-semibold text-gray-700">Market</label>
                    <select name="market_id" id="market_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Markets</option>
                        @foreach($markets as $market)
                            <option value="{{ $market->id }}" {{ request('market_id') == $market->id ? 'selected' : '' }}>
                                {{ $market->base_asset }}/{{ $market->quote_asset }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700">User</label>
                    <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                           class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                </div>

                <div class="space-y-2">
                    <label for="end_date" class="block text-sm font-semibold text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                           class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                </div>

                <div class="md:col-span-2 lg:col-span-4 space-y-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700">Search</label>
                    <div class="flex space-x-3">
                        <div class="flex-1 relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search by user name, email, or market..."
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200 pl-10">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('trades.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Trades Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Recent Trades</h2>
                        <p class="text-sm text-gray-600">{{ $trades->total() }} trades found</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Market</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Buyer</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Seller</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($trades as $trade)
                            <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-transparent transition-all duration-200 group">
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-gray-900">
                                    #{{ $trade->id }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-800 inline-block">
                                        {{ $trade->market->base_asset }}/{{ $trade->market->quote_asset }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                                <span class="text-white font-bold text-sm">
                                                    {{ substr($trade->buyOrder->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $trade->buyOrder->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $trade->buyOrder->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                                <span class="text-white font-bold text-sm">
                                                    {{ substr($trade->sellOrder->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $trade->sellOrder->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $trade->sellOrder->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-green-600">
                                    ${{ number_format($trade->price, 8) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($trade->quantity, 8) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-blue-600">
                                    ${{ number_format($trade->price * $trade->quantity, 2) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-600">
                                    ${{ number_format($trade->fee, 8) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                        {{ $trade->trade_time->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $trade->trade_time->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('trades.show', $trade) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition-colors duration-200 text-xs">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-8 py-16 text-center">
                                    <div class="text-gray-500">
                                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">No trades found</h3>
                                        <p class="text-gray-600">There are no trades matching your current filters.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('trades.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Clear filters
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($trades->hasPages())
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    {{ $trades->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
