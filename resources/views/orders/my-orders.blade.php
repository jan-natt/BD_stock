@extends('layouts.buyer')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <select name="order_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach($orderTypes as $type)
                            <option value="{{ $type }}" {{ request('order_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <select name="order_kind" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Kinds</option>
                        @foreach($orderKinds as $kind)
                            <option value="{{ $kind }}" {{ request('order_kind') == $kind ? 'selected' : '' }}>
                                {{ ucfirst($kind) }}
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
                    <select name="market_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">All Markets</option>
                        @foreach($markets as $market)
                            <option value="{{ $market->id }}" {{ request('market_id') == $market->id ? 'selected' : '' }}>
                                {{ $market->base_asset }}/{{ $market->quote_asset }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('orders.my-orders') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Clear
                    </a>
                </div>
            </form>

            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Market</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kind</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filled</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->market->base_asset }}/{{ $order->market->quote_asset }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($order->order_type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($order->order_kind) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->price ? '$' . number_format($order->price, 2) : 'Market' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($order->quantity, 6) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($order->filled_quantity, 6) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status === 'open') bg-green-100 text-green-800
                                            @elseif($order->status === 'filled') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'partial') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('orders.show', $order) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                            View
                                        </a>
                                        @if($order->status === 'open')
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No orders found.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
