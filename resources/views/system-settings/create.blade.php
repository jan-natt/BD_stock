@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create New System Setting') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('system-settings.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Key -->
                        <div>
                            <x-label for="key" value="{{ __('Setting Key') }}" />
                            <x-input id="key" class="block mt-1 w-full" type="text" name="key" value="{{ old('key') }}" required autofocus autocomplete="off" />
                            <x-input-error :messages="$errors->get('key')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Unique identifier for the setting (e.g., app_name, maintenance_mode)</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <x-label for="category" value="{{ __('Category') }}" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <x-label for="type" value="{{ __('Data Type') }}" />
                        <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select Data Type') }}</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Value -->
                    <div>
                        <x-label for="value" value="{{ __('Value') }}" />
                        <textarea id="value" name="value" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('value') }}</textarea>
                        <x-input-error :messages="$errors->get('value')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">The value for this setting. Format depends on the selected type.</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-label for="description" value="{{ __('Description') }}" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Optional description explaining what this setting does.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Options (for select type) -->
                        <div id="options-container" style="display: none;">
                            <x-label for="options" value="{{ __('Select Options') }}" />
                            <x-input id="options" class="block mt-1 w-full" type="text" name="options" value="{{ old('options') }}" />
                            <x-input-error :messages="$errors->get('options')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Comma-separated list of options for select type (e.g., option1,option2,option3)</p>
                        </div>

                        <!-- Min Value -->
                        <div id="min-max-container" style="display: none;">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-label for="min_value" value="{{ __('Min Value') }}" />
                                    <x-input id="min_value" class="block mt-1 w-full" type="number" name="min_value" value="{{ old('min_value') }}" step="any" />
                                    <x-input-error :messages="$errors->get('min_value')" class="mt-2" />
                                </div>
                                <div>
                                    <x-label for="max_value" value="{{ __('Max Value') }}" />
                                    <x-input id="max_value" class="block mt-1 w-full" type="number" name="max_value" value="{{ old('max_value') }}" step="any" />
                                    <x-input-error :messages="$errors->get('max_value')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Rules -->
                    <div>
                        <x-label for="validation_rules" value="{{ __('Validation Rules') }}" />
                        <x-input id="validation_rules" class="block mt-1 w-full" type="text" name="validation_rules" value="{{ old('validation_rules') }}" />
                        <x-input-error :messages="$errors->get('validation_rules')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Laravel validation rules (e.g., required|min:3|max:255)</p>
                    </div>

                    <!-- Security Options -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="is_encrypted" name="is_encrypted" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('is_encrypted') ? 'checked' : '' }}>
                            <label for="is_encrypted" class="ml-2 block text-sm text-gray-900">
                                {{ __('Encrypt Value') }}
                            </label>
                            <p class="ml-4 text-sm text-gray-500">Store this setting encrypted in the database</p>
                        </div>

                        <div class="flex items-center">
                            <input id="is_public" name="is_public" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                {{ __('Public Access') }}
                            </label>
                            <p class="ml-4 text-sm text-gray-500">Allow public API access to this setting</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-3">
                            {{ __('Create Setting') }}
                        </x-button>
                        <a href="{{ route('system-settings.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
    const typeSelect = document.getElementById('type');
    const optionsContainer = document.getElementById('options-container');
    const minMaxContainer = document.getElementById('min-max-container');

    function toggleFields() {
        const selectedType = typeSelect.value;
        const showOptions = selectedType === 'select';
        const showMinMax = ['integer', 'decimal'].includes(selectedType);

        optionsContainer.style.display = showOptions ? 'block' : 'none';
        minMaxContainer.style.display = showMinMax ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields(); // Initial call
});
</script>
@endsection
