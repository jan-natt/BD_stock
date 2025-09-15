@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create New Market') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('markets.store') }}" class="space-y-6">
                    @csrf

                    <!-- Base Asset -->
                    <div>
                        <x-label for="base_asset" value="{{ __('Base Asset') }}" />
                        <select id="base_asset" name="base_asset" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Base Asset') }}</option>
                            @foreach($baseAssets as $asset)
                                <option value="{{ $asset->symbol }}" {{ old('base_asset') == $asset->symbol ? 'selected' : '' }}>
                                    {{ $asset->symbol }} - {{ $asset->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('base_asset')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">The asset being traded (e.g., BTC in BTC/USD)</p>
                    </div>

                    <!-- Quote Asset -->
                    <div>
                        <x-label for="quote_asset" value="{{ __('Quote Asset') }}" />
                        <select id="quote_asset" name="quote_asset" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Quote Asset') }}</option>
                            @foreach($quoteAssets as $asset)
                                <option value="{{ $asset->symbol }}" {{ old('quote_asset') == $asset->symbol ? 'selected' : '' }}>
                                    {{ $asset->symbol }} - {{ $asset->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('quote_asset')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">The asset used to price the base asset (e.g., USD in BTC/USD)</p>
                    </div>

                    <!-- Market Type -->
                    <div>
                        <x-label for="market_type" value="{{ __('Market Type') }}" />
                        <select id="market_type" name="market_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Market Type') }}</option>
                            @foreach($marketTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('market_type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('market_type')" class="mt-2" />
                    </div>

                    <!-- Min Order Size -->
                    <div>
                        <x-label for="min_order_size" value="{{ __('Minimum Order Size') }}" />
                        <x-input id="min_order_size" class="block mt-1 w-full" type="number" name="min_order_size" value="{{ old('min_order_size', 0) }}" required min="0" step="0.00000001" />
                        <x-input-error :messages="$errors->get('min_order_size')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Minimum quantity allowed for orders in base asset units</p>
                    </div>

                    <!-- Max Order Size -->
                    <div>
                        <x-label for="max_order_size" value="{{ __('Maximum Order Size') }}" />
                        <x-input id="max_order_size" class="block mt-1 w-full" type="number" name="max_order_size" value="{{ old('max_order_size', 0) }}" required min="0" step="0.00000001" />
                        <x-input-error :messages="$errors->get('max_order_size')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Maximum quantity allowed for orders in base asset units</p>
                    </div>

                    <!-- Fee Rate -->
                    <div>
                        <x-label for="fee_rate" value="{{ __('Fee Rate (%)') }}" />
                        <x-input id="fee_rate" class="block mt-1 w-full" type="number" name="fee_rate" value="{{ old('fee_rate', 0) }}" required min="0" max="100" step="0.01" />
                        <x-input-error :messages="$errors->get('fee_rate')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Trading fee percentage (0-100)</p>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input id="status" name="status" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('status', true) ? 'checked' : '' }}>
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            {{ __('Active') }}
                        </label>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        <p class="ml-4 text-sm text-gray-500">Enable trading on this market</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-3">
                            {{ __('Create Market') }}
                        </x-button>
                        <a href="{{ route('markets.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
