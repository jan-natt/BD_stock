@extends('layouts.app')

@section('title', 'Edit IPO Application')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit IPO Application</h1>
                    <p class="mt-2 text-gray-600">Update your Initial Public Offering application</p>
                </div>
                <a href="{{ route('ipo-applications.show', $application) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Application
                </a>
            </div>
        </div>

        <!-- Status Warning -->
        @if($application->status !== 'pending')
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Application Status</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This application has a status of <strong>{{ ucfirst($application->status) }}</strong>. You may not be able to edit all fields.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('ipo-applications.update', $application) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- IPO Information (Read-only) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">IPO Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IPO Company</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $application->ipo->company_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IPO Symbol</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $application->ipo->symbol ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price per Share</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($application->ipo->price_per_share ?? 0, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IPO Status</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($application->ipo->status ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $application->company_name) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror"
                                   @if($application->status !== 'pending') disabled @endif>
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_symbol" class="block text-sm font-medium text-gray-700 mb-1">Company Symbol *</label>
                            <input type="text" name="company_symbol" id="company_symbol" value="{{ old('company_symbol', $application->company_symbol) }}" required maxlength="20"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_symbol') border-red-500 @enderror"
                                   @if($application->status !== 'pending') disabled @endif>
                            @error('company_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="company_description" class="block text-sm font-medium text-gray-700 mb-1">Company Description</label>
                            <textarea name="company_description" id="company_description" rows="4" maxlength="1000"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_description') border-red-500 @enderror"
                                      @if($application->status !== 'pending') disabled @endif>{{ old('company_description', $application->company_description) }}</textarea>
                            @error('company_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="shares_requested" class="block text-sm font-medium text-gray-700 mb-1">Shares Requested *</label>
                            <input type="number" name="shares_requested" id="shares_requested" value="{{ old('shares_requested', $application->shares_requested) }}" required min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('shares_requested') border-red-500 @enderror"
                                   @if($application->status !== 'pending') disabled @endif>
                            @error('shares_requested')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-600">Total Cost: $<span id="total-cost">{{ number_format(($application->shares_requested * ($application->ipo->price_per_share ?? 0)), 2) }}</span></p>
                        </div>

                        <div>
                            <label for="investment_amount" class="block text-sm font-medium text-gray-700 mb-1">Investment Amount</label>
                            <input type="number" name="investment_amount" id="investment_amount" value="{{ old('investment_amount', $application->investment_amount) }}" step="0.01" min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('investment_amount') border-red-500 @enderror">
                            @error('investment_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="application_reason" class="block text-sm font-medium text-gray-700 mb-1">Application Reason</label>
                            <textarea name="application_reason" id="application_reason" rows="3" maxlength="500"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('application_reason') border-red-500 @enderror">{{ old('application_reason', $application->application_reason) }}</textarea>
                            @error('application_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Application Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Status</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Current Status:</span>
                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                    @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($application->status == 'approved') bg-green-100 text-green-800
                                    @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($application->status == 'allocated') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Applied on {{ $application->created_at->format('M j, Y \a\t g:i A') }}
                            </div>
                        </div>
                        @if($application->updated_at != $application->created_at)
                            <div class="mt-2 text-sm text-gray-500">
                                Last updated: {{ $application->updated_at->format('M j, Y \a\t g:i A') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Buttons -->
                @if($application->status === 'pending')
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('ipo-applications.show', $application) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Update Application
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('ipo-applications.show', $application) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                            Back to Application
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
// Calculate total cost dynamically
document.getElementById('shares_requested').addEventListener('input', function() {
    const shares = parseInt(this.value) || 0;
    const pricePerShare = {{ $application->ipo->price_per_share ?? 0 }};
    const totalCost = shares * pricePerShare;
    document.getElementById('total-cost').textContent = totalCost.toFixed(2);
});
</script>
@endsection
