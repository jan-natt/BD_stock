@extends('layouts.buyer')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Portfolio</h1>
                <p class="text-gray-600">Manage and track your investment holdings</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('portfolio.manual-entry') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Manual Entry
                </a>
                <a href="{{ route('portfolio.export') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>

        <!-- Portfolio Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Value</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalValue, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Cost</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalCost, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-gray-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Gain/Loss</p>
                        <p class="text-2xl font-bold {{ $totalGain >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($totalGain, 2) }}
                        </p>
                        <p class="text-sm {{ $totalGain >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $totalCost > 0 ? number_format(($totalGain / $totalCost) * 100, 2) : 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 {{ $totalGain >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                        <i class="fas {{ $totalGain >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} {{ $totalGain >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>

            <div class="bg-blue-100 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Assets</p>
                        <p class="text-2xl font-bold text-dark-900">{{ $portfolio->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-coins text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <form method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by symbol or name..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="w-full lg:w-48">
                    <select name="asset_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Asset Types</option>
                        @foreach($assetTypes as $type)
                            <option value="{{ $type }}" {{ request('asset_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @foreach($sortOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('sort', 'value_desc') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('portfolio.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Portfolio Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($portfolio->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Buy Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gain/Loss</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($portfolio as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-coins text-primary-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->asset->symbol }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->asset->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($item->quantity, 6) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->avg_buy_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->asset->latest_price ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->total_cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->current_value, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm {{ $item->unrealized_gain >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <div class="font-medium">
                                                ${{ number_format($item->unrealized_gain, 2) }}
                                            </div>
                                            <div class="text-xs">
                                                {{ number_format($item->gain_percentage, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('portfolio.show', $item->asset) }}"
                                           class="text-primary-600 hover:text-primary-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('orders.create') }}?symbol={{ $item->asset->symbol }}"
                                           class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-plus"></i> Buy
                                        </a>
                                        <a href="{{ route('orders.create') }}?symbol={{ $item->asset->symbol }}&type=sell"
                                           class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-minus"></i> Sell
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-pie text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No portfolio items</h3>
                    <p class="text-gray-500 mb-6">You haven't added any assets to your portfolio yet.</p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('orders.create') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Start Trading
                        </a>
                        <a href="{{ route('portfolio.manual-entry') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Add Manual Entry
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Performance Link -->
        @if($portfolio->count() > 0)
            <div class="text-center">
                <a href="{{ route('portfolio.performance') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>View Performance Analytics
                </a>
            </div>
        @endif
    </div>
@endsection
