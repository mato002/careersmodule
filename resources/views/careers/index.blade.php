<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="description" content="{{ $generalSettings->meta_description ?? ($generalSettings->company_name ?? 'Company') . ' - Leading recruitment and career opportunities platform. Connecting talent with opportunities.' }}">
    <meta name="keywords" content="{{ $generalSettings->meta_keywords ?? 'careers, jobs, recruitment, job opportunities, career opportunities, talent acquisition' }}">
    <title>Careers - {{ $generalSettings->company_name ?? 'Company' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 hidden sm:inline">{{ $generalSettings->company_name ?? 'Company' }}</span>
                        <span class="text-base font-bold text-gray-900 sm:hidden">{{ mb_substr($generalSettings->company_name ?? 'Company', 0, 10) }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.index') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Home</a>
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
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20 overflow-x-hidden">
        @php use Illuminate\Support\Str; @endphp

        <!-- Hero Section -->
        <section
            class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
            style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80'); background-size: cover; background-position: center;"
        >
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Join Our Team</h1>
                <p class="text-lg sm:text-xl text-teal-100">Explore exciting career opportunities with {{ $generalSettings->company_name ?? 'us' }}</p>
            </div>
        </section>

        <!-- Job Updates Section -->
        <section class="py-8 bg-teal-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="text-center mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Job Updates</h2>
                    <p class="text-gray-600">Stay updated with our latest job openings</p>
                </div>
            </div>
        </section>

        <!-- Jobs Listing Section -->
        <section class="py-12 sm:py-16 md:py-20 bg-white">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Job Openings</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Browse our current and past job postings. Closed positions are kept for reference.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @forelse($jobs as $job)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all transform hover:-translate-y-2">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $job->title }}</h3>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($job->location)
                                            <span class="text-sm text-gray-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $job->location }}
                                            </span>
                                        @endif
                                        @if($job->department)
                                            <span class="text-sm text-gray-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $job->department }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($job->description), 150) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-teal-100 text-teal-800">
                                        {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                                    </span>
                                    @php
                                        $status = $job->application_status;
                                        $statusClasses = $job->status_badge_classes;
                                        $statusLabel = $job->status_label;
                                    @endphp
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <a href="{{ route('careers.show', $job->slug) }}" class="px-4 py-2 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-600 bg-white rounded-2xl shadow-sm py-10">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-lg font-semibold mb-2">No job openings at the moment</p>
                            <p class="text-sm">Check back later for new opportunities.</p>
                        </div>
                    @endforelse
                </div>

                @if($jobs->hasPages())
                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Marketing CTA Section -->
        <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 px-4">Why Join {{ $generalSettings->company_name ?? 'Us' }}?</h2>
                <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto px-4">
                    Be part of a team that's making a difference through innovative recruitment and career development solutions.
                </p>
                <a href="{{ route('careers.index') }}" class="inline-block px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    View All Openings
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center">
                        <span class="text-amber-400 font-bold text-xl">F</span>
                    </div>
                    <span class="text-xl font-bold text-white">{{ $generalSettings->company_name ?? 'Company' }}</span>
                </div>
                <p class="text-sm mb-4">{{ $generalSettings->company_description ?? $generalSettings->footer_text ?? 'Empowering careers and connecting talent with opportunities.' }}</p>
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ $generalSettings->company_name ?? 'Company' }}. {{ $generalSettings->copyright_text ?? 'All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
            });
        }

        // Navbar scroll effect
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (navbar) {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 100) {
                    navbar.classList.add('shadow-lg');
                } else {
                    navbar.classList.remove('shadow-lg');
                }
                
                lastScroll = currentScroll;
            }
        });
    </script>
</body>
</html>
