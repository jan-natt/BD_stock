@extends('layouts.buyer')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-900">My Trades</h1>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <select name="market_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Markets</option>
                        @foreach($markets as $market)
                            <option value="{{ $market->id }}" {{ request('market_id') == $market->id ? 'selected' : '' }}>
                                {{ $market->base_asset }}/{{ $market->quote_asset }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <select name="side" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Sides</option>
                        <option value="buy" {{ request('side') == 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="sell" {{ request('side') == 'sell' ? 'selected' : '' }}>Sell</option>
                    </select>
                </div>

                <div class="flex-1">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="flex-1">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('trades.my-trades') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Clear
                    </a>
                </div>
            </form>

            @if($trades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Market</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trade Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($trades as $trade)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $trade->market->base_asset }}/{{ $trade->market->quote_asset }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($trade->buyOrder->user_id == auth()->id())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Buy
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Sell
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($trade->price, 8) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($trade->quantity, 8) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($trade->price * $trade->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($trade->fee, 8) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $trade->trade_time->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('trades.show', $trade) }}" class="text-primary-600 hover:text-primary-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $trades->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No trades found.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
