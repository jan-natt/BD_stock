@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Place New Order') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('orders.store') }}" class="space-y-6" id="orderForm">
                    @csrf

                    <!-- Market Selection -->
                    <div>
                        <x-label for="market_id" value="{{ __('Market') }}" />
                        <select id="market_id" name="market_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Market') }}</option>
                            @foreach($markets as $marketOption)
                                <option value="{{ $marketOption->id }}" {{ ($market && $market->id == $marketOption->id) ? 'selected' : '' }}>
                                    {{ $marketOption->base_asset }}/{{ $marketOption->quote_asset }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('market_id')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Choose the trading pair for your order</p>
                    </div>

                    <!-- Order Type -->
                    <div>
                        <x-label for="order_type" value="{{ __('Order Type') }}" />
                        <select id="order_type" name="order_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Order Type') }}</option>
                            @foreach($orderTypes as $type)
                                <option value="{{ $type }}" {{ old('order_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('order_type')" class="mt-2" />
                    </div>

                    <!-- Order Kind -->
                    <div>
                        <x-label for="order_kind" value="{{ __('Order Kind') }}" />
                        <select id="order_kind" name="order_kind" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Order Kind') }}</option>
                            @foreach($orderKinds as $kind)
                                <option value="{{ $kind }}" {{ old('order_kind') == $kind ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $kind)) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('order_kind')" class="mt-2" />
                    </div>

                    <!-- Price (conditional) -->
                    <div id="priceField" style="display: none;">
                        <x-label for="price" value="{{ __('Price') }}" />
                        <x-input id="price" class="block mt-1 w-full" type="number" name="price" value="{{ old('price') }}" step="0.00000001" min="0.00000001" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Enter the price per unit (required for limit, stop-loss, and take-profit orders)</p>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <x-label for="quantity" value="{{ __('Quantity') }}" />
                        <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" value="{{ old('quantity') }}" step="0.00000001" min="0.00000001" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Enter the amount you want to buy/sell</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-3">
                            {{ __('Place Order') }}
                        </x-button>
                        <a href="{{ route('orders.my-orders') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderKindSelect = document.getElementById('order_kind');
    const priceField = document.getElementById('priceField');
    const priceInput = document.getElementById('price');

    function togglePriceField() {
        const selectedKind = orderKindSelect.value;
        if (selectedKind === 'market') {
            priceField.style.display = 'none';
            priceInput.required = false;
            priceInput.value = '';
        } else {
            priceField.style.display = 'block';
            priceInput.required = true;
        }
    }

    orderKindSelect.addEventListener('change', togglePriceField);

    // Initial check
    togglePriceField();
});
</script>
@endsection
