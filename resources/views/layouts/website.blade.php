<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">

    {{-- SEO: allow pages to override meta tags while keeping sensible defaults --}}
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @else
        <meta name="description" content="Fortress Lenders Ltd - Leading recruitment and career opportunities platform in Kenya. Connecting talent with opportunities.">
    @endif

    @hasSection('meta_keywords')
        <meta name="keywords" content="@yield('meta_keywords')">
    @else
        <meta name="keywords" content="careers, jobs, Kenya, recruitment, job opportunities, career opportunities, talent acquisition">
    @endif
    
    <title>
        @hasSection('title')
            @yield('title')
        @else
            Fortress Lenders Ltd - The Force Of Possibilities
        @endif
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md transition-all duration-300" id="navbar" role="navigation" aria-label="Main navigation">
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
                    <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.index') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">About Us</a>
                    <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.*') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Careers</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button
                        type="button"
                        id="mobile-menu-button"
                        class="text-gray-700 hover:text-teal-700 focus:outline-none p-2 -mr-2"
                        aria-label="Toggle navigation menu"
                        aria-expanded="false"
                        aria-controls="mobile-menu"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-lg" role="menu" aria-label="Mobile navigation menu">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('careers.index') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Home</a>
                <a href="{{ route('about') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">About Us</a>
                <a href="{{ route('careers.index') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Careers</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20 overflow-x-hidden">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center">
                            <span class="text-amber-400 font-bold text-xl">F</span>
                        </div>
                        <span class="text-xl font-bold text-white">{{ $generalSettings->company_name ?? 'Fortress Lenders' }}</span>
                    </div>
                    @if($generalSettings->company_description ?? null)
                        <p class="text-sm mb-4">{{ $generalSettings->company_description }}</p>
                    @else
                        <p class="text-sm mb-4">The Force Of Possibilities! Empowering careers and connecting talent with opportunities.</p>
                    @endif
                    @if($generalSettings->company_address ?? null)
                        <p class="text-sm mb-4">
                            <strong>Head Office:</strong><br>
                            {!! nl2br(e($generalSettings->company_address)) !!}
                        </p>
                    @else
                        <p class="text-sm mb-4">
                            <strong>Head Office:</strong><br>
                            Fortress Hse, Nakuru County<br>
                            Barnabas Muguga Opp. Epic ridge Academy
                        </p>
                    @endif
                    <!-- Social Media Icons -->
                    @if($generalSettings->facebook_url || $generalSettings->twitter_url || $generalSettings->linkedin_url || $generalSettings->instagram_url || $generalSettings->youtube_url)
                        <div class="flex items-center space-x-3">
                            <h4 class="text-white font-semibold text-sm mr-2">Follow Us:</h4>
                            @if($generalSettings->facebook_url)
                                <a href="{{ $generalSettings->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="Visit our Facebook page"
                                   class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($generalSettings->twitter_url)
                                <a href="{{ $generalSettings->twitter_url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="Visit our Twitter page"
                                   class="w-10 h-10 bg-gray-800 hover:bg-blue-400 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($generalSettings->linkedin_url)
                                <a href="{{ $generalSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="Visit our LinkedIn page"
                                   class="w-10 h-10 bg-gray-800 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($generalSettings->instagram_url)
                                <a href="{{ $generalSettings->instagram_url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="Visit our Instagram page"
                                   class="w-10 h-10 bg-gray-800 hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($generalSettings->youtube_url)
                                <a href="{{ $generalSettings->youtube_url }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="Visit our YouTube channel"
                                   class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('careers.index') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('careers.index') }}" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="{{ route('company.profile') }}" target="_blank" rel="noopener" class="hover:text-white transition-colors">Company Profile (PDF)</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-sm">
                        @if($generalSettings->company_phone ?? null)
                            <li>{{ $generalSettings->company_phone }}</li>
                        @else
                            <li>+254 743 838 312</li>
                            <li>+254 722 295 194</li>
                        @endif
                        @if($generalSettings->company_email ?? null)
                            <li><a href="mailto:{{ $generalSettings->company_email }}" class="hover:text-white transition-colors">{{ $generalSettings->company_email }}</a></li>
                        @else
                            <li><a href="mailto:info@fortresslenders.com" class="hover:text-white transition-colors">info@fortresslenders.com</a></li>
                        @endif
                        @if($generalSettings->company_address ?? null)
                            <li>{!! nl2br(e($generalSettings->company_address)) !!}</li>
                        @else
                            <li>P.O BOX: 7214- 20110<br>Nakuru Town, KENYA</li>
                        @endif
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Newsletter</h3>
                    <p class="text-sm mb-4">Subscribe to our newsletter to get the latest updates and news.</p>
                    @if (session('newsletter_status'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('newsletter_status') }}
                        </div>
                    @endif
                    @if (session('newsletter_error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ session('newsletter_error') }}
                        </div>
                    @endif
                    @if ($errors->has('email'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <input type="email" name="email" required 
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                @if($generalSettings->copyright_text ?? null)
                    <p>{{ $generalSettings->copyright_text }}</p>
                @else
                    <p>&copy; {{ date('Y') }} Fortress Lenders Ltd. All rights reserved.</p>
                @endif
                @if($generalSettings->privacy_policy_url || $generalSettings->terms_of_service_url)
                    <div class="mt-2 space-x-4">
                        @if($generalSettings->privacy_policy_url)
                            <a href="{{ $generalSettings->privacy_policy_url }}" class="hover:text-white transition-colors">Privacy Policy</a>
                        @endif
                        @if($generalSettings->terms_of_service_url)
                            <a href="{{ $generalSettings->terms_of_service_url }}" class="hover:text-white transition-colors">Terms of Service</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-40 flex flex-col gap-3 md:gap-4">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/254728883160" target="_blank" rel="noopener noreferrer"
           aria-label="Chat with us on WhatsApp"
           class="bg-green-500 text-white p-3 md:p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
        <!-- Chatbot Button -->
        <button
            type="button"
            id="chatbot-button"
            class="bg-gradient-to-r from-teal-700 to-teal-800 text-white p-3 md:p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110"
            aria-label="Open support chat"
            aria-haspopup="dialog"
            aria-expanded="false"
            aria-controls="chatbot-modal"
        >
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>
    </div>

    <!-- Chatbot Modal -->
    <div
        id="chatbot-modal"
        class="fixed inset-0 z-50 hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="chatbot-title"
    >
        <!-- Backdrop -->
        <div id="chatbot-backdrop" class="fixed inset-0 bg-black bg-opacity-30 transition-opacity duration-300"></div>
        <!-- Chatbot Popup - Right Side -->
        <div id="chatbot-popup" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out z-50">
            <!-- Chatbot Header -->
            <div class="bg-gradient-to-r from-teal-700 to-teal-800 text-white p-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center" aria-hidden="true">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="chatbot-title" class="font-semibold text-lg">Support Team</h3>
                        <p class="text-sm text-teal-100">Usually replies in minutes</p>
                    </div>
                </div>
                <button type="button" id="chatbot-close" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Chatbot Content -->
            <div class="flex-1 overflow-y-auto bg-white">
                <!-- Greeting Section -->
                <div class="p-6 text-center border-b border-gray-200">
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Hi there! 👋</h4>
                    <p class="text-gray-600 text-sm">How can we help you today?</p>
                </div>

                <!-- Send Message Section -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <h5 class="font-semibold text-gray-900">Send us a message</h5>
                    </div>
                    <p class="text-sm text-gray-500 ml-7">We typically reply in under 2 minutes</p>
                </div>

                <!-- Quick Actions -->
                <div class="p-4 border-b border-gray-200">
                    <div class="space-y-2">
                        <button type="button" class="quick-action-btn w-full text-left px-4 py-3 bg-gray-50 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-between group">
                            <span class="text-sm text-gray-700 group-hover:text-teal-700">Job Opportunities</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button type="button" class="quick-action-btn w-full text-left px-4 py-3 bg-gray-50 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-between group">
                            <span class="text-sm text-gray-700 group-hover:text-teal-700">Contact Details</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button type="button" class="quick-action-btn w-full text-left px-4 py-3 bg-gray-50 hover:bg-teal-50 rounded-lg transition-colors flex items-center justify-between group">
                            <span class="text-sm text-gray-700 group-hover:text-teal-700">Apply for Job</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Chatbot Messages -->
                <div id="chatbot-messages" class="p-4 space-y-4 bg-gray-50 min-h-[200px]">
                    <div class="flex items-start space-x-2">
                        <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-sm font-semibold">F</span>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm max-w-[80%]">
                            <p class="text-gray-800 text-sm">Hello! 👋 Welcome to Fortress Lenders. How can we assist you today?</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chatbot Input -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <form id="chatbot-form" class="flex space-x-2">
                    <input type="text" id="chatbot-input" placeholder="Type your message..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded-lg hover:bg-teal-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
                <div class="mt-2 text-center">
                    <a href="mailto:{{ $generalSettings->company_email ?? 'support@example.com' }}" class="text-xs text-teal-700 hover:text-teal-800 transition-colors">Or contact us via email</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            const isHidden = mobileMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });

        // Navbar scroll effect
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
            
            lastScroll = currentScroll;
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        // Close mobile menu if open
                        mobileMenu.classList.add('hidden');
                        mobileMenuButton.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });

        // Animate elements on scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.animate-fade-in-up, .animate-fade-in');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Run once on load

        // Chatbot functionality
        const chatbotButton = document.getElementById('chatbot-button');
        const chatbotModal = document.getElementById('chatbot-modal');
        const chatbotPopup = document.getElementById('chatbot-popup');
        const chatbotBackdrop = document.getElementById('chatbot-backdrop');
        const chatbotClose = document.getElementById('chatbot-close');
        const chatbotForm = document.getElementById('chatbot-form');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotMessages = document.getElementById('chatbot-messages');

        // Open chatbot modal
        chatbotButton.addEventListener('click', function() {
            chatbotModal.classList.remove('hidden');
            // Trigger slide-in animation
            setTimeout(() => {
                chatbotPopup.classList.remove('translate-x-full');
            }, 10);
            chatbotButton.setAttribute('aria-expanded', 'true');
            chatbotInput.focus();
        });

        // Close chatbot modal
        function closeChatbot() {
            chatbotPopup.classList.add('translate-x-full');
            setTimeout(() => {
                chatbotModal.classList.add('hidden');
                chatbotButton.setAttribute('aria-expanded', 'false');
            }, 300);
        }

        chatbotClose.addEventListener('click', closeChatbot);

        // Close modal when clicking backdrop
        chatbotBackdrop.addEventListener('click', closeChatbot);

        // Quick action buttons
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Get text from the span element, not the button
                const span = this.querySelector('span');
                const action = span ? span.textContent.trim() : this.textContent.trim();
                addUserMessage(action);
                
                // Simulate bot response
                setTimeout(() => {
                    let response = '';
                    if (action === 'Job Opportunities') {
                        response = 'We have various job opportunities across different industries. You can visit our Careers page to browse available positions or contact us at +254 743 838 312 for more details.';
                    } else if (action === 'Contact Details') {
                        response = 'You can reach us at:\n📞 +254 743 838 312\n📞 +254 722 295 194\n📧 info@fortresslenders.com\n📍 Fortress Hse, Nakuru County, Barnabas Muguga Opp. Epic ridge Academy';
                    } else if (action === 'Apply for Job') {
                        response = 'Great! You can apply for a job by visiting our Careers page. Browse available positions and submit your application online.';
                    } else {
                        response = 'Thank you for your interest! How can I help you further?';
                    }
                    addBotMessage(response);
                }, 500);
            });
        });

        // Handle form submission
        chatbotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatbotInput.value.trim();
            if (message) {
                addUserMessage(message);
                chatbotInput.value = '';
                
                // Simulate bot response
                setTimeout(() => {
                    addBotMessage('Thank you for your message! Our team will get back to you soon. For immediate assistance, please call us at +254 743 838 312 or email info@fortresslenders.com');
                }, 1000);
            }
        });

        // Add user message
        function addUserMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-2 justify-end';
            messageDiv.innerHTML = `
                <div class="bg-teal-700 text-white rounded-lg p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm">${escapeHtml(message)}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            `;
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Add bot message
        function addBotMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-2';
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-semibold">F</span>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-[80%]">
                    <p class="text-gray-800 text-sm whitespace-pre-line">${escapeHtml(message)}</p>
                </div>
            `;
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Cookie Consent functionality
        const cookieConsentBanner = document.getElementById('cookie-consent-banner');
        const cookieConsentAccept = document.getElementById('cookie-consent-accept');
        const cookieConsentReject = document.getElementById('cookie-consent-reject');
        const cookieConsentClose = document.getElementById('cookie-consent-close');

        // Check if user has already given consent
        function checkCookieConsent() {
            const consent = getCookie('cookie_consent');
            if (consent) {
                cookieConsentBanner.classList.add('hidden');
            } else {
                cookieConsentBanner.classList.remove('hidden');
            }
        }

        // Get cookie value
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        // Accept cookies
        if (cookieConsentAccept) {
            cookieConsentAccept.addEventListener('click', function() {
                fetch('{{ route("cookie.consent.accept") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cookieConsentBanner.classList.add('hidden');
                        // Set cookie manually as fallback
                        document.cookie = 'cookie_consent=accepted; max-age=31536000; path=/; SameSite=Lax';
                    }
                })
                .catch(error => {
                    console.error('Error accepting cookies:', error);
                    // Set cookie manually as fallback
                    document.cookie = 'cookie_consent=accepted; max-age=31536000; path=/; SameSite=Lax';
                    cookieConsentBanner.classList.add('hidden');
                });
            });
        }

        // Reject cookies
        if (cookieConsentReject) {
            cookieConsentReject.addEventListener('click', function() {
                fetch('{{ route("cookie.consent.reject") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cookieConsentBanner.classList.add('hidden');
                        // Set cookie manually as fallback
                        document.cookie = 'cookie_consent=rejected; max-age=31536000; path=/; SameSite=Lax';
                    }
                })
                .catch(error => {
                    console.error('Error rejecting cookies:', error);
                    // Set cookie manually as fallback
                    document.cookie = 'cookie_consent=rejected; max-age=31536000; path=/; SameSite=Lax';
                    cookieConsentBanner.classList.add('hidden');
                });
            });
        }

        // Close banner (temporary, will show again on next visit)
        if (cookieConsentClose) {
            cookieConsentClose.addEventListener('click', function() {
                cookieConsentBanner.classList.add('hidden');
            });
        }

        // Check consent on page load
        checkCookieConsent();
    </script>

    <!-- Cookie Consent Banner -->
    <div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 z-50 hidden bg-white border-t border-gray-200 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-6 h-6 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">We Value Your Privacy</h3>
                            <p class="text-sm text-gray-600">
                                We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. 
                                By clicking "Accept All", you consent to our use of cookies. 
                                <a href="mailto:{{ $generalSettings->company_email ?? 'support@example.com' }}" class="text-teal-700 hover:text-teal-800 underline">Contact us</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    <button id="cookie-consent-reject" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors whitespace-nowrap">
                        Reject All
                    </button>
                    <button id="cookie-consent-accept" 
                            class="px-4 py-2 text-sm font-medium text-white bg-teal-800 hover:bg-teal-900 rounded-lg transition-colors whitespace-nowrap">
                        Accept All
                    </button>
                    <button id="cookie-consent-close" 
                            class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors md:hidden">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

