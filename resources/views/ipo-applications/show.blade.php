@extends('layouts.app')

@section('title', 'IPO Application Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">IPO Application Details</h1>
                    <p class="mt-2 text-gray-600">View and manage your Initial Public Offering application</p>
                </div>
                <div class="flex space-x-3">
                    @if($application->status === 'pending')
                        <a href="{{ route('ipo-applications.edit', $application) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit Application
                        </a>
                    @endif
                    <a href="{{ route('ipo-applications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Applications
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="mb-6">
            <div class="px-4 py-3 rounded-lg
                @if($application->status == 'pending') bg-yellow-50 border border-yellow-200
                @elseif($application->status == 'approved') bg-green-50 border border-green-200
                @elseif($application->status == 'rejected') bg-red-50 border border-red-200
                @elseif($application->status == 'allocated') bg-blue-50 border border-blue-200
                @else bg-gray-50 border border-gray-200 @endif">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($application->status == 'pending')
                            <i class="fas fa-clock text-yellow-400"></i>
                        @elseif($application->status == 'approved')
                            <i class="fas fa-check-circle text-green-400"></i>
                        @elseif($application->status == 'rejected')
                            <i class="fas fa-times-circle text-red-400"></i>
                        @elseif($application->status == 'allocated')
                            <i class="fas fa-check-double text-blue-400"></i>
                        @else
                            <i class="fas fa-question-circle text-gray-400"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium
                            @if($application->status == 'pending') text-yellow-800
                            @elseif($application->status == 'approved') text-green-800
                            @elseif($application->status == 'rejected') text-red-800
                            @elseif($application->status == 'allocated') text-blue-800
                            @else text-gray-800 @endif">
                            Application Status: {{ ucfirst($application->status) }}
                        </h3>
                        <div class="mt-2 text-sm
                            @if($application->status == 'pending') text-yellow-700
                            @elseif($application->status == 'approved') text-green-700
                            @elseif($application->status == 'rejected') text-red-700
                            @elseif($application->status == 'allocated') text-blue-700
                            @else text-gray-700 @endif">
                            @if($application->status == 'pending')
                                Your application is being reviewed. You can still edit it if needed.
                            @elseif($application->status == 'approved')
                                Your application has been approved. Shares will be allocated soon.
                            @elseif($application->status == 'rejected')
                                Your application has been rejected. Please contact support for more information.
                            @elseif($application->status == 'allocated')
                                Your shares have been successfully allocated to your portfolio.
                            @else
                                Application status is unknown.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- IPO Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">IPO Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IPO Company</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $application->ipo->company_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IPO Symbol</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $application->ipo->symbol ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price per Share</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">${{ number_format($application->ipo->price_per_share ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IPO Status</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ ucfirst($application->ipo->status ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IPO Period</label>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $application->ipo ? $application->ipo->ipo_start->format('M j, Y') : 'N/A' }} -
                                {{ $application->ipo ? $application->ipo->ipo_end->format('M j, Y') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Available Shares</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ number_format($application->ipo->available_shares ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Company Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company Name</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $application->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company Symbol</label>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $application->company_symbol }}</p>
                        </div>
                        @if($application->company_description)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Company Description</label>
                                <p class="mt-1 text-gray-600">{{ $application->company_description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Application Details -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Application Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shares Requested</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($application->shares_requested) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Investment</label>
                            <p class="mt-1 text-2xl font-bold text-green-600">
                                ${{ number_format(($application->shares_requested * ($application->ipo->price_per_share ?? 0)), 2) }}
                            </p>
                        </div>
                        @if($application->investment_amount)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Additional Investment</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">${{ number_format($application->investment_amount, 2) }}</p>
                            </div>
                        @endif
                        @if($application->shares_allocated)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Shares Allocated</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($application->shares_allocated) }}</p>
                            </div>
                        @endif
                        @if($application->application_reason)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Application Reason</label>
                                <p class="mt-1 text-gray-600">{{ $application->application_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Application Timeline -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-paper-plane text-blue-600 text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                <p class="text-sm text-gray-500">{{ $application->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($application->status !== 'pending')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8
                                    @if($application->status == 'approved') bg-green-100
                                    @elseif($application->status == 'rejected') bg-red-100
                                    @elseif($application->status == 'allocated') bg-blue-100
                                    @else bg-gray-100 @endif rounded-full flex items-center justify-center">
                                    @if($application->status == 'approved')
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    @elseif($application->status == 'rejected')
                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                    @elseif($application->status == 'allocated')
                                        <i class="fas fa-check-double text-blue-600 text-sm"></i>
                                    @else
                                        <i class="fas fa-question text-gray-600 text-sm"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($application->status == 'approved') Application Approved
                                        @elseif($application->status == 'rejected') Application Rejected
                                        @elseif($application->status == 'allocated') Shares Allocated
                                        @else Status Updated @endif
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $application->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($application->status === 'pending')
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('ipo-applications.edit', $application) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                                <i class="fas fa-edit mr-2"></i>Edit Application
                            </a>
                            <form action="{{ route('ipo-applications.cancel', $application) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to cancel this application? This action cannot be undone.')">
                                    <i class="fas fa-times mr-2"></i>Cancel Application
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Application ID -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Application ID</h4>
                    <p class="text-xs font-mono text-gray-600">{{ $application->id }}</p>
                    <h4 class="text-sm font-medium text-gray-700 mt-4 mb-2">Applied By</h4>
                    <p class="text-sm text-gray-900">{{ $application->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $application->user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
