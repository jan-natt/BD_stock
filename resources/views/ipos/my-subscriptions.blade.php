@extends('layouts.app')

@section('title', 'My IPO Subscriptions')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">My IPO Subscriptions</h1>
            <a href="{{ route('ipos.public') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to IPOs
            </a>
        </div>

        @if($subscriptions->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPO</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shares Subscribed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shares Allocated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subscriptions as $subscription)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('ipos.show', $subscription->ipo) }}" class="text-blue-600 hover:underline">
                                        {{ $subscription->ipo->company_name }} ({{ $subscription->ipo->symbol }})
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->shares_subscribed }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->shares_allocated ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($subscription->amount_paid, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $subscription->status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->created_at->format('M j, Y g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($subscription->status === 'subscribed')
                                        <form action="{{ route('ipos.cancel-subscription', $subscription->ipo) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this subscription?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:underline">Cancel</button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Subscriptions Found</h3>
                <p class="text-gray-500">You have not subscribed to any IPOs yet.</p>
                <a href="{{ route('ipos.public') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Browse IPOs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
