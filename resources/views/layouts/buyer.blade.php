<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Buyer Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    @livewireStyles

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .animate-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <x-banner />

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg z-10 hidden md:block">
            <div class="p-6">
                <h1 class="text-xl font-bold text-primary-700 flex items-center">
                    <i class="fas fa-building-columns mr-2"></i> {{ config('app.name', 'BD Stock') }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Trading Platform</p>
            </div>
            <nav class="mt-6 px-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-primary-600 bg-primary-50 rounded-lg font-medium">
                    <i class="fas fa-chart-pie mr-3"></i> Dashboard
                </a>
                <a href="{{ route('portfolio.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-wallet mr-3"></i> Portfolio
                </a>
                <a href="{{ route('orders.my-orders') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-file-invoice-dollar mr-3"></i> Orders
                </a>
                <a href="{{ route('trades.my-trades') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-exchange-alt mr-3"></i> Trades
                </a>
                <a href="{{ route('wallets.my-wallets') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-money-bill-wave mr-3"></i> Wallets
                </a>
                <a href="{{ route('transactions.my-transactions') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-history mr-3"></i> Transactions
                </a>
                <a href="{{ route('ipos.public') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-chart-line mr-3"></i> IPOs
                </a>
                <a href="{{ route('system-settings.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg mt-2">
                    <i class="fas fa-cog mr-3"></i> Settings
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-100">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <i class="fas fa-user text-primary-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Buyer Account</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ml-4">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4 md:px-6">
                    <div class="flex items-center">
                        <button class="md:hidden text-gray-500 mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">{{ Auth::user()->unreadNotifications()->count() }}</span>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center md:hidden">
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>
