@extends('layouts.buyer')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-900">My Transactions</h1>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach($transactionTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <select name="wallet_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Wallets</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->currency }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('transactions.my-transactions') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Clear
                    </a>
                </div>
            </form>

            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $transaction->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($transaction->amount, 8) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->fee ? number_format($transaction->fee, 8) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->wallet->currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="text-primary-600 hover:text-primary-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No transactions found.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
