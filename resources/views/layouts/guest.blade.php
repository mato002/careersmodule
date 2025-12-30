<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Fortress Lenders Ltd - Access your account and manage your financial services.">
    
    <title>@yield('title', 'Login - Fortress Lenders Ltd')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md transition-all duration-300" id="navbar">
        <div class="w-full px-4 sm:px-6 lg:px-12">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('careers.index') }}" class="flex items-center space-x-2">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-amber-400 font-bold text-lg sm:text-xl">F</span>
                        </div>
                        <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 hidden sm:inline">Fortress Lenders</span>
                        <span class="text-base font-bold text-gray-900 sm:hidden">Fortress</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('careers.index') }}" class="nav-link text-gray-700 hover:text-teal-700 transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="nav-link text-gray-700 hover:text-teal-700 transition-colors">About Us</a>
                    <a href="{{ route('products') }}" class="nav-link text-gray-700 hover:text-teal-700 transition-colors">Products</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20 min-h-screen flex flex-col justify-center items-center py-12 sm:py-16">
        <div class="w-full max-w-md px-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-auto">
        <div class="w-full px-4 sm:px-6 lg:px-12 py-8">
            <div class="text-center text-sm">
                <p>&copy; {{ date('Y') }} Fortress Lenders Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
