@extends('layouts.buyer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Available IPOs</h1>
                <p class="text-gray-600 mt-1">Browse and subscribe to initial public offerings</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Total IPOs</div>
                <div class="text-2xl font-bold text-blue-600">{{ $ipos->total() }}</div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('ipos.public') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by company name or symbol..."
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('ipos.public') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- IPOs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ipos as $ipo)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $ipo->company_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $ipo->symbol }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Price per Share</div>
                            <div class="text-lg font-bold text-green-600">${{ number_format($ipo->price_per_share, 2) }}</div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Shares:</span>
                            <span class="font-medium">{{ number_format($ipo->total_shares) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Available:</span>
                            <span class="font-medium">{{ number_format($ipo->available_shares) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Start Date:</span>
                            <span class="font-medium">{{ $ipo->ipo_start->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">End Date:</span>
                            <span class="font-medium">{{ $ipo->ipo_end->format('M j, Y') }}</span>
                        </div>
                    </div>

                    @if($ipo->description)
                        <p class="text-sm text-gray-700 mb-4 line-clamp-2">{{ $ipo->description }}</p>
                    @endif

                    <div class="flex gap-2">
                        <a href="{{ route('ipos.show', $ipo) }}"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium text-sm transition-colors">
                            View Details
                        </a>
                        <a href="{{ route('ipos.show', $ipo) }}#subscribe"
                           class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg font-medium text-sm transition-colors">
                            Subscribe
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No IPOs Available</h3>
                <p class="text-gray-600">There are currently no active IPOs for subscription.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($ipos->hasPages())
        <div class="bg-white rounded-lg shadow-sm p-6">
            {{ $ipos->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
