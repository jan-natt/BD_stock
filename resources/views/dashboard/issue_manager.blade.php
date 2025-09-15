@extends('layouts.app')

@section('title', 'Issue Manager Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-3xl opacity-5"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Issue Manager Dashboard</h1>
                        <p class="text-lg text-gray-600">Manage IPOs and track investment opportunities.</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Last updated: {{ now()->format('M j, Y \a\t g:i A') }}
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                IPO System Active
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Active IPOs</div>
                            <div class="text-2xl font-bold text-purple-600">{{ $activeIPOs->count() }}</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
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
            <!-- Active IPOs -->
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-purple-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Active IPOs</p>
                        <p class="text-4xl font-bold leading-none">{{ $activeIPOs->count() }}</p>
                    </div>
                    <a href="{{ route('ipos.index') }}" class="inline-flex items-center text-purple-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Upcoming IPOs -->
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-yellow-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-yellow-100 text-sm font-medium uppercase tracking-wide">Upcoming IPOs</p>
                        <p class="text-4xl font-bold leading-none">{{ $upcomingIPOs->count() }}</p>
                    </div>
                    <a href="{{ route('ipos.index') }}" class="inline-flex items-center text-yellow-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-cyan-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-cyan-100 text-sm font-medium uppercase tracking-wide">Total Applications</p>
                        <p class="text-4xl font-bold leading-none">{{ \App\Models\IPOApplication::count() }}</p>
                    </div>
                    <a href="{{ route('ipo-applications.index') }}" class="inline-flex items-center text-cyan-100 hover:text-white text-sm font-medium transition-colors duration-200">
                        <span>View Details</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Total Funds Raised -->
            <div class="relative bg-gradient-to-br from-emerald-500 via-green-600 to-emerald-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400 bg-opacity-20 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-emerald-400 bg-opacity-30 rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <div class="text-emerald-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Funds Raised</p>
                        <p class="text-4xl font-bold leading-none">${{ number_format(\App\Models\IPOApplication::where('status', 'allocated')->sum('total_cost'), 2) }}</p>
                    </div>
                    <a href="{{ route('ipo-applications.index') }}" class="inline-flex items-center text-emerald-100 hover:text-white text-sm font-medium transition-colors duration-200">
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
                <div class="h-1 flex-1 ml-4 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="{{ route('ipos.create') }}" class="group relative bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 hover:border-purple-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Create IPO</h3>
                        <p class="text-sm text-gray-600">Launch new IPO offering</p>
                    </div>
                </a>

                <a href="{{ route('ipo-applications.index') }}" class="group relative bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-2xl border border-pink-200 hover:border-pink-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Review Applications</h3>
                        <p class="text-sm text-gray-600">Process investor applications</p>
                    </div>
                </a>

                <a href="{{ route('ipos.index') }}" class="group relative bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-2xl border border-indigo-200 hover:border-indigo-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">IPO Analytics</h3>
                        <p class="text-sm text-gray-600">View performance metrics</p>
                    </div>
                </a>

                <a href="{{ route('notifications.create') }}" class="group relative bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-2xl border border-emerald-200 hover:border-emerald-300 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Send Announcement</h3>
                        <p class="text-sm text-gray-600">Notify investors and stakeholders</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- IPO Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Active IPOs -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Active IPOs</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($activeIPOs as $ipo)
                        <div class="px-8 py-5 hover:bg-gradient-to-r hover:from-purple-50 hover:to-transparent transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="px-3 py-1 bg-green-100 rounded-full text-xs font-semibold text-green-800 mr-3">
                                            Active
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $ipo->company_name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                        {{ $ipo->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-gray-900">${{ number_format($ipo->price_per_share, 2) }}</div>
                                    <div class="text-sm text-gray-600">{{ number_format($ipo->total_shares) }} shares</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-8 py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Active IPOs</h3>
                            <p class="text-gray-500">There are currently no active IPO offerings.</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    <a href="{{ route('ipos.index') }}" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors duration-200">
                        <span>View all IPOs</span>
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Upcoming IPOs -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-pink-50 to-rose-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-pink-500 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Upcoming IPOs</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($upcomingIPOs as $ipo)
                        <div class="px-8 py-5 hover:bg-gradient-to-r hover:from-pink-50 hover:to-transparent transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="px-3 py-1 bg-blue-100 rounded-full text-xs font-semibold text-blue-800 mr-3">
                                            Upcoming
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $ipo->company_name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                        {{ $ipo->opening_date ? $ipo->opening_date->format('M j, Y') : 'TBD' }}
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-gray-900">${{ number_format($ipo->price_per_share, 2) }}</div>
                                    <div class="text-sm text-gray-600">{{ number_format($ipo->total_shares) }} shares</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-8 py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Upcoming IPOs</h3>
                            <p class="text-gray-500">There are no upcoming IPO offerings scheduled.</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                    <a href="{{ route('ipos.index') }}" class="inline-flex items-center text-sm font-semibold text-pink-600 hover:text-pink-700 transition-colors duration-200">
                        <span>View all IPOs</span>
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
