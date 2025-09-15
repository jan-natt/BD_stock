@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create New User') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-label for="phone" value="{{ __('Phone') }}" />
                        <x-input id="phone" class="block mt-1 w-full" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- User Type -->
                    <div>
                        <x-label for="user_type" value="{{ __('User Type') }}" />
                        <select id="user_type" name="user_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">{{ __('Select User Type') }}</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type }}" {{ old('user_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
                    </div>

                    <!-- KYC Status -->
                    <div>
                        <x-label for="kyc_status" value="{{ __('KYC Status') }}" />
                        <select id="kyc_status" name="kyc_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">{{ __('Select KYC Status') }}</option>
                            @foreach($kycStatuses as $status)
                                <option value="{{ $status }}" {{ old('kyc_status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kyc_status')" class="mt-2" />
                    </div>

                    <!-- Referral Code -->
                    <div>
                        <x-label for="referral_code" value="{{ __('Referral Code') }}" />
                        <x-input id="referral_code" class="block mt-1 w-full" type="text" name="referral_code" value="{{ old('referral_code') }}" />
                        <x-input-error :messages="$errors->get('referral_code')" class="mt-2" />
                    </div>

                    <!-- Referred By -->
                    <div>
                        <x-label for="referred_by" value="{{ __('Referred By (User ID)') }}" />
                        <x-input id="referred_by" class="block mt-1 w-full" type="number" name="referred_by" value="{{ old('referred_by') }}" />
                        <x-input-error :messages="$errors->get('referred_by')" class="mt-2" />
                    </div>

                    <!-- Two Factor Authentication -->
                    <div class="flex items-center">
                        <input id="two_factor_enabled" name="two_factor_enabled" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" value="1" {{ old('two_factor_enabled') ? 'checked' : '' }}>
                        <label for="two_factor_enabled" class="ml-2 block text-sm text-gray-900">
                            {{ __('Enable Two-Factor Authentication') }}
                        </label>
                        <x-input-error :messages="$errors->get('two_factor_enabled')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-3">
                            {{ __('Create User') }}
                        </x-button>
                        <a href="{{ route('users.index') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
