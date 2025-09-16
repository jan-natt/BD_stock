@extends('layouts.buyer')

@section('content')
    <!-- Welcome Banner -->
    <div class="gradient-bg rounded-2xl text-white p-6 md:p-8 mb-6 relative overflow-hidden animate-in">
        <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white opacity-10 rounded-full"></div>
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white opacity-5 rounded-full"></div>

        <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-primary-100 mb-6">Here's your investment portfolio overview and latest market updates.</p>
            <div class="flex items-center">
                <div class="bg-blue text-primary-600 px-4 py-2 rounded-lg font-semibold">
                    ${{ number_format($totalPortfolioValue, 2) }}
                </div>
                <div class="ml-4">
                    <p class="text-sm text-primary-100">Portfolio Value</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Portfolio Value</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-1">${{ number_format($totalPortfolioValue, 2) }}</h3>
                    <p class="text-xs text-green-500 mt-2 flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> Active
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Total Assets</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $totalAssets }}</h3>
                    <p class="text-xs text-gray-500 mt-2">In portfolio</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Wallet Balance</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-1">${{ number_format($walletBalance, 2) }}</h3>
                    <p class="text-xs text-green-500 mt-2 flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> Available
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Recent Trades</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $recentTrades->count() }}</h3>
                    <p class="text-xs text-gray-500 mt-2">Last activities</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Portfolio Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Portfolio Performance</h3>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>Last 90 Days</option>
                </select>
            </div>
            <div class="h-72">
                <!-- Chart container -->
                <div class="flex items-end h-56 gap-2 mt-8">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-100 rounded-t-lg" style="height: 40%"></div>
                        <p class="text-xs text-gray-500 mt-2">Mon</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-200 rounded-t-lg" style="height: 55%"></div>
                        <p class="text-xs text-gray-500 mt-2">Tue</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-300 rounded-t-lg" style="height: 70%"></div>
                        <p class="text-xs text-gray-500 mt-2">Wed</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-400 rounded-t-lg" style="height: 90%"></div>
                        <p class="text-xs text-gray-500 mt-2">Thu</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-500 rounded-t-lg" style="height: 75%"></div>
                        <p class="text-xs text-gray-500 mt-2">Fri</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-blue-600 rounded-t-lg" style="height: 65%"></div>
                        <p class="text-xs text-gray-500 mt-2">Sat</p>
                    </div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-primary-600 rounded-t-lg" style="height: 85%"></div>
                        <p class="text-xs text-gray-500 mt-2">Sun</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Allocation -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Asset Allocation</h3>
            <div class="space-y-5">
                @if($portfolioItems->count() > 0)
                    @foreach($portfolioItems->groupBy('asset.symbol') as $symbol => $items)
                        @php
                            $totalValue = $items->sum('current_value');
                            $percentage = $totalPortfolioValue > 0 ? ($totalValue / $totalPortfolioValue) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $symbol }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-pie text-3xl mb-2"></i>
                        <p>No assets in portfolio</p>
                    </div>
                @endif
            </div>
            <a href="{{ route('portfolio.index') }}" class="mt-6 w-full py-2 bg-primary-50 text-primary-600 rounded-lg font-medium text-sm hover:bg-primary-100 transition-colors inline-block text-center">
                View Portfolio
            </a>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="{{ route('trades.my-trades') }}" class="text-sm text-primary-600 font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @if($recentTrades->count() > 0)
                    @foreach($recentTrades as $trade)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $trade->asset->symbol }} Trade</p>
                                <p class="text-xs text-gray-500">{{ $trade->quantity }} shares at ${{ number_format($trade->price, 2) }}</p>
                            </div>
                            <div class="ml-auto text-right">
                                <p class="text-sm font-medium text-gray-900">${{ number_format($trade->total_amount, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $trade->trade_time->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-history text-3xl mb-2"></i>
                        <p>No recent trades</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('orders.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors card-hover">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Buy</span>
                </a>
                <a href="{{ route('orders.create') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition-colors card-hover">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-minus text-red-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Sell</span>
                </a>
                <a href="{{ route('wallets.transfer') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors card-hover">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-exchange-alt text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Transfer</span>
                </a>
                <a href="{{ route('portfolio.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors card-hover">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-chart-pie text-purple-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Portfolio</span>
                </a>
            </div>
        </div>
    </div>
@endsection
