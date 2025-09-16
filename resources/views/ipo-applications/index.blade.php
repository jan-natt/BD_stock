@extends('layouts.app')

@section('title', 'IPO Applications')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">IPO Applications</h1>
                    <p class="mt-2 text-gray-600">Manage Initial Public Offering applications</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('ipo-applications.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>New Application
                    </a>
                    <a href="{{ route('ipos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to IPOs
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                    </select>
                </div>
                <div>
                    <label for="ipo_id" class="block text-sm font-medium text-gray-700 mb-1">IPO</label>
                    <select name="ipo_id" id="ipo_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All IPOs</option>
                        @foreach($ipos ?? [] as $ipo)
                            <option value="{{ $ipo->id }}" {{ request('ipo_id') == $ipo->id ? 'selected' : '' }}>
                                {{ $ipo->company_name }} ({{ $ipo->symbol }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Search by company or user..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('ipo-applications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Applications List -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shares Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->company_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->company_symbol }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->ipo->company_name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->ipo->symbol ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($application->shares_requested) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($application->status == 'approved') bg-green-100 text-green-800
                                            @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                            @elseif($application->status == 'allocated') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $application->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('ipo-applications.show', $application) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($application->status == 'pending')
                                                <a href="{{ route('ipo-applications.edit', $application) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('ipo-applications.allocate', $application) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Allocate Shares">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('ipo-applications.reject', $application) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Reject Application"
                                                            onclick="return confirm('Are you sure you want to reject this application?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No IPO Applications</h3>
                    <p class="text-gray-500 mb-6">There are no IPO applications at the moment.</p>
                    <a href="{{ route('ipo-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create First Application
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
