@extends('layouts.app')

@section('title', 'Markets Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl opacity-5"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Markets Management</h1>
                        <p class="text-lg text-gray-600">Manage trading markets and their configurations.</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
                            </div>
                            <div class="flex items-center text-sm text-blue-600">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                                {{ $markets->total() }} markets found
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Active Markets</div>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $markets->where('status', true)->count() }}
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('markets.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Market
                    </a>
                    <a href="{{ route('markets.export') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('markets.import.form') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Import CSV
                    </a>
                    <a href="{{ route('markets.statistics') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistics
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Filters & Search</h2>
                <div class="h-1 flex-1 ml-4 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
            </div>
            <form method="GET" action="{{ route('markets.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label for="market_type" class="block text-sm font-semibold text-gray-700">Market Type</label>
                    <select name="market_type" id="market_type" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Types</option>
                        @foreach($marketTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('market_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="base_asset" class="block text-sm font-semibold text-gray-700">Base Asset</label>
                    <select name="base_asset" id="base_asset" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Base Assets</option>
                        @foreach($baseAssets as $asset)
                            <option value="{{ $asset->symbol }}" {{ request('base_asset') == $asset->symbol ? 'selected' : '' }}>
                                {{ $asset->symbol }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="quote_asset" class="block text-sm font-semibold text-gray-700">Quote Asset</label>
                    <select name="quote_asset" id="quote_asset" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">All Quote Assets</option>
                        @foreach($quoteAssets as $asset)
                            <option value="{{ $asset->symbol }}" {{ request('quote_asset') == $asset->symbol ? 'selected' : '' }}>
                                {{ $asset->symbol }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 space-y-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700">Search</label>
                    <div class="flex space-x-3">
                        <div class="flex-1 relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search by base asset or quote asset..."
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors duration-200 pl-10">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('markets.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Markets Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Trading Markets</h2>
                        <p class="text-sm text-gray-600">{{ $markets->total() }} markets found</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Base Asset</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quote Asset</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Market Type</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Min Order Size</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Max Order Size</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee Rate</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($markets as $market)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200 group">
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-gray-900">
                                    #{{ $market->id }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg">
                                                <span class="text-white font-bold text-xs">
                                                    {{ $market->baseAsset ? substr($market->baseAsset->symbol, 0, 2) : '' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $market->baseAsset ? $market->baseAsset->symbol : '' }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $market->baseAsset ? $market->baseAsset->name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                                <span class="text-white font-bold text-xs">
                                                    {{ $market->quoteAsset ? substr($market->quoteAsset->symbol, 0, 2) : '' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $market->quoteAsset ? $market->quoteAsset->symbol : '' }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $market->quoteAsset ? $market->quoteAsset->name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    <div class="px-3 py-1 bg-purple-100 rounded-full text-xs font-semibold text-purple-800 inline-block">
                                        {{ $marketTypes[$market->market_type] ?? $market->market_type }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($market->min_order_size, 8) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($market->max_order_size, 8) }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($market->fee_rate, 2) }}%
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($market->status)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <form method="POST" action="{{ route('markets.toggle-status', $market) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-semibold rounded-lg transition-colors duration-200 text-xs">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                </svg>
                                                {{ $market->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('markets.edit', $market) }}" class="inline-flex items-center px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition-colors duration-200 text-xs">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('markets.destroy', $market) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this market?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded-lg transition-colors duration-200 text-xs">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-8 py-16 text-center">
                                    <div class="text-gray-500">
                                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">No markets found</h3>
                                        <p class="text-gray-600">There are no markets matching your current filters.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('markets.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition-colors duration-200 mr-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Clear filters
                                            </a>
                                            <a href="{{ route('markets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Create first market
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
            @if($markets->hasPages())
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    {{ $markets->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
