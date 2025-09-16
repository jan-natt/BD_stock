@extends('layouts.buyer')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Wallets</h1>
                <p class="text-gray-600">Manage your cryptocurrency and fiat currency wallets</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('wallets.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Wallet
                </a>
                <a href="{{ route('wallets.transfer') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-exchange-alt mr-2"></i>Transfer Funds
                </a>
            </div>
        </div>

        <!-- Wallet Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Wallets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $wallets->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Active Wallets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $wallets->where('is_locked', false)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-unlock text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Locked Wallets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $wallets->where('is_locked', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallets Grid -->
        @if($wallets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wallets as $wallet)
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-coins text-primary-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $wallet->currency }}</h3>
                                    <p class="text-sm text-gray-500">Wallet</p>
                                </div>
                            </div>
                            @if($wallet->is_locked)
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    Locked
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Active
                                </span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($wallet->balance, 8) }}</p>
                            <p class="text-sm text-gray-500">{{ $wallet->currency }}</p>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('wallets.show', $wallet) }}"
                               class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors text-center">
                                View Details
                            </a>
                            @if(!$wallet->is_locked)
                                <button class="flex-1 bg-primary-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                                    Deposit
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl p-12 shadow-sm text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No wallets found</h3>
                <p class="text-gray-500 mb-6">You haven't created any wallets yet. Create your first wallet to start trading.</p>
                <a href="{{ route('wallets.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Your First Wallet
                </a>
            </div>
        @endif

        <!-- Supported Currencies -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Supported Currencies</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach($supportedCurrencies as $currency)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <span class="text-sm font-bold text-gray-700">{{ $currency }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $currency }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
