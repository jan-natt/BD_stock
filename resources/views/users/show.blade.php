@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('User Details') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">{{ $user->name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                    <p><strong>{{ __('Phone:') }}</strong> {{ $user->phone ?? '-' }}</p>
                    <p><strong>{{ __('User Type:') }}</strong> {{ ucfirst($user->user_type) }}</p>
                    <p><strong>{{ __('KYC Status:') }}</strong> {{ ucfirst($user->kyc_status ?? 'N/A') }}</p>
                </div>
                <div>
                    <p><strong>{{ __('Referral Code:') }}</strong> {{ $user->referral_code ?? '-' }}</p>
                    <p><strong>{{ __('Referred By User ID:') }}</strong> {{ $user->referred_by ?? '-' }}</p>
                    <p><strong>{{ __('Two Factor Enabled:') }}</strong> {{ $user->two_factor_enabled ? __('Yes') : __('No') }}</p>
                    <p><strong>{{ __('Created At:') }}</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Users List') }}
                </a>
                <a href="{{ route('users.edit', $user) }}" class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit User') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
