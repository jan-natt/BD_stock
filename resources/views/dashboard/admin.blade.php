@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl opacity-5"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                        <p class="text-lg text-gray-600">Welcome back! Here's what's happening with your platform.</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                All systems operational
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Platform Health</div>
                            <div class="text-2xl font-bold text-green-600">98.5%</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-blue-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Users</p>
                        <p class="text-4xl font-bold leading-none">{{ number_format($stats['total_users']) }}</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center text-blue-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Total Trades -->
            <div class="relative bg-gradient-to-br from-emerald-500 via-green-600 to-emerald-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-emerald-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-emerald-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Total Trades</p>
                        <p class="text-4xl font-bold leading-none">{{ number_format($stats['total_trades']) }}</p>
                    </div>
                    <a href="{{ route('trades.index') }}" class="inline-flex items-center text-emerald-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="relative bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-amber-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <div class="text-amber-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-amber-100 text-sm font-medium uppercase tracking-wide">Total Revenue</p>
                        <p class="text-4xl font-bold leading-none">${{ number_format($stats['revenue'], 2) }}</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-amber-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Active Markets -->
            <div class="relative bg-gradient-to-br from-violet-500 via-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-violet-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-violet-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-violet-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-violet-100 text-sm font-medium uppercase tracking-wide">Active Markets</p>
                        <p class="text-4xl font-bold leading-none">{{ $stats['active_markets'] ?? 0 }}</p>
                    </div>
                    <a href="{{ route('markets.index') }}" class="inline-flex items-center text-violet-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
                <div class="h-1 flex-1 ml-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="{{ route('users.create') }}" class="group relative bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 hover:border-blue-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Add User</h3>
                        <p class="text-sm text-gray-600">Create new user account</p>
                    </div>
                </a>

                <a href="{{ route('assets.create') }}" class="group relative bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-2xl border border-emerald-200 hover:border-emerald-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Add Asset</h3>
                        <p class="text-sm text-gray-600">Create new trading asset</p>
                    </div>
                </a>

                <a href="{{ route('markets.create') }}" class="group relative bg-gradient-to-br from-violet-50 to-violet-100 p-6 rounded-2xl border border-violet-200 hover:border-violet-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-violet-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Add Market</h3>
                        <p class="text-sm text-gray-600">Create new market pair</p>
                    </div>
                </a>

                <a href="{{ route('system-settings.create') }}" class="group relative bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-2xl border border-amber-200 hover:border-amber-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Settings</h3>
                        <p class="text-sm text-gray-600">Configure system settings</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Users</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                        <div class="px-8 py-5 hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                            <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-semibold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">{{ $user->created_at->diffForHumans() }}</div>
                                    <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200">
                        <span>View all users</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Recent Trades -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-green-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Trades</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach(\App\Models\Trade::with(['buyOrder.user', 'sellOrder.user', 'market'])->latest()->take(5)->get() as $trade)
                        <div class="px-8 py-5 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-transparent transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-800 mr-3">
                                            {{ $trade->market->base_asset }}/{{ $trade->market->quote_asset }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $trade->buyOrder->user->name ?? 'Unknown' }}</span>
                                            <span class="mx-2 text-gray-400">→</span>
                                            <span class="font-medium">{{ $trade->sellOrder->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                        {{ $trade->trade_time->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-gray-900">${{ number_format($trade->price, 2) }}</div>
                                    <div class="text-sm text-gray-600">{{ number_format($trade->quantity, 8) }} units</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    <a href="{{ route('trades.index') }}" class="inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors duration-200">
                        <span>View all trades</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection