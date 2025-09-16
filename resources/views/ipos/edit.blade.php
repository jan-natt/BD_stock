@extends('layouts.app')

@section('title', 'Edit IPO')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit IPO</h1>
                    <p class="mt-2 text-gray-600">Update the details for {{ $ipo->company_name }} ({{ $ipo->symbol }})</p>
                </div>
                <a href="{{ route('ipos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to IPOs
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('ipos.update', $ipo) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Company Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $ipo->company_name) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="symbol" class="block text-sm font-medium text-gray-700 mb-1">Stock Symbol *</label>
                            <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $ipo->symbol) }}" required maxlength="20"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('symbol') border-red-500 @enderror">
                            @error('symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="issue_manager_id" class="block text-sm font-medium text-gray-700 mb-1">Issue Manager *</label>
                            <select name="issue_manager_id" id="issue_manager_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('issue_manager_id') border-red-500 @enderror">
                                <option value="">Select Issue Manager</option>
                                @foreach($issueManagers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('issue_manager_id', $ipo->issue_manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('issue_manager_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" maxlength="1000"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe the company and the IPO offering...">{{ old('description', $ipo->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- IPO Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">IPO Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price_per_share" class="block text-sm font-medium text-gray-700 mb-1">Price per Share ($) *</label>
                            <input type="number" name="price_per_share" id="price_per_share" value="{{ old('price_per_share', $ipo->price_per_share) }}" required step="0.01" min="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('price_per_share') border-red-500 @enderror">
                            @error('price_per_share')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="total_shares" class="block text-sm font-medium text-gray-700 mb-1">Total Shares *</label>
                            <input type="number" name="total_shares" id="total_shares" value="{{ old('total_shares', $ipo->total_shares) }}" required min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('total_shares') border-red-500 @enderror">
                            @error('total_shares')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ipo_start" class="block text-sm font-medium text-gray-700 mb-1">IPO Start Date & Time *</label>
                            <input type="datetime-local" name="ipo_start" id="ipo_start" value="{{ old('ipo_start', $ipo->ipo_start->format('Y-m-d\TH:i')) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('ipo_start') border-red-500 @enderror">
                            @error('ipo_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ipo_end" class="block text-sm font-medium text-gray-700 mb-1">IPO End Date & Time *</label>
                            <input type="datetime-local" name="ipo_end" id="ipo_end" value="{{ old('ipo_end', $ipo->ipo_end->format('Y-m-d\TH:i')) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('ipo_end') border-red-500 @enderror">
                            @error('ipo_end')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Subscription Limits -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription Limits</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="min_subscription" class="block text-sm font-medium text-gray-700 mb-1">Minimum Subscription (shares)</label>
                            <input type="number" name="min_subscription" id="min_subscription" value="{{ old('min_subscription', $ipo->min_subscription ?? 1) }}" min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('min_subscription') border-red-500 @enderror">
                            @error('min_subscription')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_subscription" class="block text-sm font-medium text-gray-700 mb-1">Maximum Subscription (shares)</label>
                            <input type="number" name="max_subscription" id="max_subscription" value="{{ old('max_subscription', $ipo->max_subscription) }}" min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('max_subscription') border-red-500 @enderror">
                            @error('max_subscription')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('ipos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Update IPO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-fill max_subscription with total_shares if not set
document.getElementById('total_shares').addEventListener('input', function() {
    const maxSub = document.getElementById('max_subscription');
    if (!maxSub.value) {
        maxSub.value = this.value;
    }
});

// Ensure ipo_end is after ipo_start
document.getElementById('ipo_start').addEventListener('change', function() {
    document.getElementById('ipo_end').min = this.value;
});
</script>
@endsection
