@extends('layouts.app')

@section('title', 'IPO Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ $ipo->company_name }} ({{ $ipo->symbol }})</h1>
            <a href="{{ route('ipos.public') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to IPOs
            </a>
        </div>

        <!-- IPO Details -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Company Information</h2>
                    <p class="text-gray-700 mb-4">{{ $ipo->description }}</p>
                    <p><strong>Issue Manager:</strong> {{ $ipo->issueManager->name }} ({{ $ipo->issueManager->email }})</p>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">IPO Details</h2>
                    <ul class="text-gray-700 space-y-1">
                        <li><strong>Price per Share:</strong> ${{ number_format($ipo->price_per_share, 2) }}</li>
                        <li><strong>Total Shares:</strong> {{ number_format($ipo->total_shares) }}</li>
                        <li><strong>IPO Start:</strong> {{ $ipo->ipo_start->format('M j, Y g:i A') }}</li>
                        <li><strong>IPO End:</strong> {{ $ipo->ipo_end->format('M j, Y g:i A') }}</li>
                        <li><strong>Status:</strong> {{ ucfirst($ipo->status) }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Subscription Form -->
        @auth
            @if($ipo->status === 'open' && now()->between($ipo->ipo_start, $ipo->ipo_end))
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Subscribe to this IPO</h2>
                    <form action="{{ route('ipos.subscribe', $ipo) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="shares_subscribed" class="block text-sm font-medium text-gray-700 mb-1">Number of Shares</label>
                            <input type="number" name="shares_subscribed" id="shares_subscribed" min="{{ $ipo->min_subscription ?? 1 }}" max="{{ $ipo->max_subscription ?? $ipo->total_shares }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('shares_subscribed') border-red-500 @enderror"
                                   value="{{ old('shares_subscribed') }}">
                            @error('shares_subscribed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Subscribe
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-600">
                    <p>Subscriptions are currently closed for this IPO.</p>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-600">
                <p>Please <a href="{{ route('login') }}" class="text-blue-600 underline">log in</a> to subscribe to this IPO.</p>
            </div>
        @endauth
    </div>
</div>
@endsection
