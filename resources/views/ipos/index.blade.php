@extends('layouts.app')

@section('title', 'IPOs Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">IPOs Management</h1>
                    <p class="mt-2 text-gray-600">Manage and monitor all IPO offerings</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('ipos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create IPO
                    </a>
                    <a href="{{ route('ipos.statistics') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-chart-bar mr-2"></i>Statistics
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('ipos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="issue_manager_id" class="block text-sm font-medium text-gray-700 mb-1">Issue Manager</label>
                    <select name="issue_manager_id" id="issue_manager_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Managers</option>
                        @foreach($issueManagers as $manager)
                            <option value="{{ $manager->id }}" {{ request('issue_manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Company name or symbol" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('ipos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- IPOs List -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">IPOs ({{ $ipos->total() }})</h2>
            </div>

            @if($ipos->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($ipos as $ipo)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-building text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $ipo->company_name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $ipo->symbol }} • {{ $ipo->issueManager->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Price per Share:</span>
                                            <span class="font-medium">${{ number_format($ipo->price_per_share, 2) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Total Shares:</span>
                                            <span class="font-medium">{{ number_format($ipo->total_shares) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Available:</span>
                                            <span class="font-medium">{{ number_format($ipo->available_shares) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Status:</span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($ipo->status == 'open') bg-green-100 text-green-800
                                                @elseif($ipo->status == 'closed') bg-blue-100 text-blue-800
                                                @elseif($ipo->status == 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($ipo->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span>Start: {{ $ipo->ipo_start->format('M j, Y H:i') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>End: {{ $ipo->ipo_end->format('M j, Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('ipos.show', $ipo) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-eye mr-2"></i>View
                                    </a>
                                    @can('update', $ipo)
                                        <a href="{{ route('ipos.edit', $ipo) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                    @endcan
                                    @can('export', $ipo)
                                        <a href="{{ route('ipos.export', $ipo) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>Export
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $ipos->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No IPOs Found</h3>
                    <p class="text-gray-500">There are no IPOs matching your criteria.</p>
                    <a href="{{ route('ipos.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create First IPO
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
