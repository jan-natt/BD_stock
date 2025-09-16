@extends('layouts.buyer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Transfer Funds</h1>
                <p class="text-gray-600 mt-1">Transfer funds between your wallets</p>
            </div>
            <a href="{{ route('wallets.my-wallets') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                Back to Wallets
            </a>
        </div>
    </div>

    <!-- Transfer Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('wallets.transfer.post') }}" class="space-y-6">
            @csrf

            <!-- From Wallet -->
            <div>
                <label for="from_wallet_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    From Wallet
                </label>
                <select name="from_wallet_id" id="from_wallet_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors">
                    <option value="">Select source wallet</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">
                            {{ $wallet->currency }} - Balance: {{ number_format($wallet->balance, 8) }}
                        </option>
                    @endforeach
                </select>
                @error('from_wallet_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- To Wallet -->
            <div>
                <label for="to_wallet_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    To Wallet
                </label>
                <select name="to_wallet_id" id="to_wallet_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors">
                    <option value="">Select destination wallet</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">
                            {{ $wallet->currency }} - Balance: {{ number_format($wallet->balance, 8) }}
                        </option>
                    @endforeach
                </select>
                @error('to_wallet_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                    Amount
                </label>
                <input type="number" name="amount" id="amount" step="0.00000001" min="0.00000001"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors"
                       placeholder="Enter amount to transfer">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description (Optional)
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 bg-gray-50 hover:bg-white transition-colors"
                          placeholder="Add a note for this transfer"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Transfer Funds
                </button>
            </div>
        </form>
    </div>

    <!-- Wallet Balances -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Wallet Balances</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($wallets as $wallet)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $wallet->currency }}</p>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($wallet->balance, 8) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
