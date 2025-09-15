@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create New Asset') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('assets.store') }}" class="space-y-6">
                    @csrf

                    <!-- Symbol -->
                    <div>
                        <x-label for="symbol" value="{{ __('Symbol') }}" />
                        <x-input id="symbol" class="block mt-1 w-full" type="text" name="symbol" value="{{ old('symbol') }}" required autofocus autocomplete="off" />
                        <x-input-error :messages="$errors->get('symbol')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Unique identifier for the asset (e.g., BTC, AAPL, EUR)</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name') }}" required autocomplete="off" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Full name of the asset (e.g., Bitcoin, Apple Inc., Euro)</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <x-label for="type" value="{{ __('Asset Type') }}" />
                        <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Asset Type') }}</option>
                            @foreach($assetTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Precision -->
                    <div>
                        <x-label for="precision" value="{{ __('Decimal Precision') }}" />
                        <x-input id="precision" class="block mt-1 w-full" type="number" name="precision" value="{{ old('precision', 8) }}" required min="0" max="18" />
                        <x-input-error :messages="$errors->get('precision')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Number of decimal places for price calculations (0-18)</p>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input id="status" name="status" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('status', true) ? 'checked' : '' }}>
                        <label for="status" class="ml-2 block text-sm text-gray-900">
                            {{ __('Active') }}
                        </label>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        <p class="ml-4 text-sm text-gray-500">Enable this asset for trading</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-3">
                            {{ __('Create Asset') }}
                        </x-button>
                        <a href="{{ route('assets.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
