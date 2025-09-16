@extends('layouts.app')

@section('title', 'IPO Statistics')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">IPO Statistics</h1>
            <a href="{{ route('ipos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to IPOs
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                <div class="p-4 bg-gray-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Total IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['total_ipos'] }}</p>
                </div>
                <div class="p-4 bg-green-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Open IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['open_ipos'] }}</p>
                </div>
                <div class="p-4 bg-red-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Closed IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['closed_ipos'] }}</p>
                </div>
                <div class="p-4 bg-yellow-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Cancelled IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['cancelled_ipos'] }}</p>
                </div>
                <div class="p-4 bg-blue-100 rounded-lg text-center md:col-span-2">
                    <h2 class="text-xl font-semibold mb-2">Total Capital Raised</h2>
                    <p class="text-3xl font-bold">${{ number_format($stats['total_capital_raised'], 2) }}</p>
                </div>
                <div class="p-4 bg-purple-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Upcoming IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['upcoming_ipos'] }}</p>
                </div>
                <div class="p-4 bg-indigo-100 rounded-lg text-center">
                    <h2 class="text-xl font-semibold mb-2">Active IPOs</h2>
                    <p class="text-3xl font-bold">{{ $stats['active_ipos'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
