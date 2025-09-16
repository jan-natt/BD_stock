@extends('layouts.app')

@section('title', 'Create IPO Application')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create IPO Application</h1>
                    <p class="mt-2 text-gray-600">Apply for shares in an Initial Public Offering</p>
                </div>
                <a href="{{ route('ipo-applications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Applications
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('ipo-applications.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- IPO Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Select IPO</h3>
                    <div>
                        <label for="ipo_id" class="block text-sm font-medium text-gray-700 mb-1">Available IPOs *</label>
                        <select name="ipo_id" id="ipo_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('ipo_id') border-red-500 @enderror">
                            <option value="">Select an IPO</option>
                            @foreach($availableIpos ?? [] as $ipo)
                                <option value="{{ $ipo->id }}" {{ old('ipo_id') == $ipo->id ? 'selected' : '' }}>
                                    {{ $ipo->company_name }} ({{ $ipo->symbol }}) - ${{ number_format($ipo->price_per_share, 2) }} per share
                                </option>
                            @endforeach
                        </select>
                        @error('ipo_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Company Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_symbol" class="block text-sm font-medium text-gray-700 mb-1">Company Symbol *</label>
                            <input type="text" name="company_symbol" id="company_symbol" value="{{ old('company_symbol') }}" required maxlength="20"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_symbol') border-red-500 @enderror">
                            @error('company_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="company_description" class="block text-sm font-medium text-gray-700 mb-1">Company Description</label>
                            <textarea name="company_description" id="company_description" rows="4" maxlength="1000"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('company_description') border-red-500 @enderror"
                                      placeholder="Describe your company and its business...">{{ old('company_description') }}</textarea>
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
                            <input type="number" name="shares_requested" id="shares_requested" value="{{ old('shares_requested') }}" required min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('shares_requested') border-red-500 @enderror">
                            @error('shares_requested')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-600">Total Cost: $<span id="total-cost">0.00</span></p>
                        </div>

                        <div>
                            <label for="investment_amount" class="block text-sm font-medium text-gray-700 mb-1">Investment Amount</label>
                            <input type="number" name="investment_amount" id="investment_amount" value="{{ old('investment_amount') }}" step="0.01" min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('investment_amount') border-red-500 @enderror">
                            @error('investment_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="application_reason" class="block text-sm font-medium text-gray-700 mb-1">Application Reason</label>
                            <textarea name="application_reason" id="application_reason" rows="3" maxlength="500"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('application_reason') border-red-500 @enderror"
                                      placeholder="Why do you want to invest in this IPO?">{{ old('application_reason') }}</textarea>
                            @error('application_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms_accepted" name="terms_accepted" type="checkbox" required
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('terms_accepted') border-red-500 @enderror">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms_accepted" class="font-medium text-gray-700">I accept the terms and conditions *</label>
                            <p class="text-gray-500 mt-1">
                                I understand that IPO investments carry risks and I have read and understood the prospectus and risk disclosures.
                            </p>
                            @error('terms_accepted')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('ipo-applications.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Calculate total cost dynamically
document.getElementById('shares_requested').addEventListener('input', function() {
    const shares = parseInt(this.value) || 0;
    const pricePerShare = 0; // This will be set based on selected IPO
    const totalCost = shares * pricePerShare;
    document.getElementById('total-cost').textContent = totalCost.toFixed(2);
});

// Update price when IPO is selected
document.getElementById('ipo_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        // Extract price from option text (this is a simplified approach)
        const text = selectedOption.text;
        const priceMatch = text.match(/\$([0-9,.]+) per share/);
        if (priceMatch) {
            const pricePerShare = parseFloat(priceMatch[1].replace(',', ''));
            const shares = parseInt(document.getElementById('shares_requested').value) || 0;
            const totalCost = shares * pricePerShare;
            document.getElementById('total-cost').textContent = totalCost.toFixed(2);
        }
    }
});
</script>
@endsection
