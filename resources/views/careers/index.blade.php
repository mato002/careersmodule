<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="description" content="{{ ($generalSettings && $generalSettings->meta_description) ? $generalSettings->meta_description : (($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name . ' - Leading recruitment and career opportunities platform. Connecting talent with opportunities.' : 'Company - Leading recruitment and career opportunities platform. Connecting talent with opportunities.') }}">
    <meta name="keywords" content="{{ ($generalSettings && $generalSettings->meta_keywords) ? $generalSettings->meta_keywords : 'careers, jobs, recruitment, job opportunities, career opportunities, talent acquisition' }}">
    <title>Careers - {{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company' }}</title>

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
                        @if($generalSettings && $generalSettings->logo_path)
                            <img src="{{ asset('storage/' . $generalSettings->logo_path) }}" alt="{{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company' }}" class="h-8 w-auto">
                        @else
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-amber-400 font-bold text-lg sm:text-xl">{{ mb_substr(($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company', 0, 1) }}</span>
                        </div>
                        @endif
                        <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 hidden sm:inline">{{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company' }}</span>
                        <span class="text-base font-bold text-gray-900 sm:hidden">{{ mb_substr(($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company', 0, 10) }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.index') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Home</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-teal-700 focus:outline-none p-2 -mr-2" aria-label="Toggle navigation menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('careers.index') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Home</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20 overflow-x-hidden">
        @php use Illuminate\Support\Str; @endphp

        <!-- Hero Section -->
        <section class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden" style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Join Our Team</h1>
                <p class="text-lg sm:text-xl text-teal-100">Explore exciting career opportunities with {{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'us' }}</p>
            </div>
        </section>

        <!-- Quick Stats Section -->
        <section class="py-8 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $jobs->total() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Positions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $jobs->where('application_status', 'open')->count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Open Now</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $jobs->pluck('department')->unique()->count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Departments</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $jobs->pluck('location')->unique()->count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Locations</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Jobs Listing Section -->
        <section class="py-12 sm:py-16 md:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Section Header with Filter Options -->
                <div class="mb-8 md:mb-12">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                        <div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Job Openings</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Browse our current and past job postings. Closed positions are kept for reference.</p>
                        </div>
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-semibold">{{ $jobs->firstItem() ?? 0 }}-{{ $jobs->lastItem() ?? 0 }}</span> of <span class="font-semibold">{{ $jobs->total() }}</span> positions
                        </div>
                    </div>
                </div>

                <!-- Jobs Grid -->
                @if($jobs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($jobs as $job)
                            <article class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all transform hover:-translate-y-2 flex flex-col">
                                <!-- Job Header -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $job->title }}</h3>
                                    
                                    <!-- Meta Information -->
                                    <div class="flex flex-wrap gap-3 mb-4 text-sm text-gray-600">
                                        @if($job->location)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $job->location }}
                                            </span>
                                        @endif
                                        @if($job->department)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $job->department }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Description Preview -->
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                        {{ Str::limit(strip_tags($job->description), 150) }}
                                    </p>
                                </div>
                                
                                <!-- Job Footer -->
                                <div class="pt-4 border-t border-gray-200 mt-auto">
                                    <!-- Tags -->
                                    <div class="flex items-center gap-2 flex-wrap mb-4">
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
                                    
                                    <!-- Action Button -->
                                    <a href="{{ route('careers.show', $job->slug) }}" class="block w-full px-4 py-2.5 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-sm text-center">
                                    View Details
                                </a>
                            </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($jobs->hasPages())
                        <div class="mt-12 flex justify-center">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="col-span-full text-center text-gray-600 bg-white rounded-2xl shadow-sm py-16">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-lg font-semibold mb-2">No job openings at the moment</p>
                            <p class="text-sm">Check back later for new opportunities.</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Company Information Section -->
        @if($generalSettings && $generalSettings->company_description)
        <section class="py-12 md:py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">About {{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Us' }}</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $generalSettings && $generalSettings->company_description ? $generalSettings->company_description : '' }}</p>
                </div>
            </div>
        </section>
        @endif

        <!-- Call to Action Section -->
        <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">Why Join {{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Us' }}?</h2>
                <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        @if($generalSettings && $generalSettings->logo_path)
                            <img src="{{ asset('storage/' . $generalSettings->logo_path) }}" alt="{{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company' }}" class="h-8 w-auto">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center">
                                <span class="text-amber-400 font-bold text-xl">{{ mb_substr(($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company', 0, 1) }}</span>
                            </div>
                        @endif
                        <span class="text-xl font-bold text-white">{{ ($generalSettings && $generalSettings->company_name) ? $generalSettings->company_name : 'Company' }}</span>
                    </div>
                    @if($generalSettings && $generalSettings->company_description)
                        <p class="text-sm text-gray-400 mb-4">{{ Str::limit($generalSettings->company_description, 120) }}</p>
                    @endif
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('careers.index') }}" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('careers.index') }}#jobs" class="text-gray-400 hover:text-white transition-colors">Job Openings</a></li>
                        @if(($generalSettings->privacy_policy_url ?? null))
                            <li><a href="{{ $generalSettings->privacy_policy_url }}" class="text-gray-400 hover:text-white transition-colors" target="_blank">Privacy Policy</a></li>
                        @endif
                        @if(($generalSettings->terms_of_service_url ?? null))
                            <li><a href="{{ $generalSettings->terms_of_service_url }}" class="text-gray-400 hover:text-white transition-colors" target="_blank">Terms of Service</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        @if($generalSettings && $generalSettings->company_email)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $generalSettings->company_email }}" class="hover:text-white transition-colors">{{ $generalSettings->company_email }}</a>
                            </li>
                        @endif
                        @if($generalSettings && $generalSettings->company_phone)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $generalSettings->company_phone }}" class="hover:text-white transition-colors">{{ $generalSettings->company_phone }}</a>
                            </li>
                        @endif
                        @if($generalSettings && $generalSettings->company_address)
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="hover:text-white transition-colors">{{ $generalSettings->company_address }}</span>
                            </li>
                        @endif
                    </ul>
                    
                    <!-- Social Links -->
                    @if($generalSettings && ($generalSettings->linkedin_url || $generalSettings->facebook_url || $generalSettings->twitter_url))
                        <div class="flex items-center space-x-4 mt-4">
                            @if($generalSettings->linkedin_url)
                                <a href="{{ $generalSettings->linkedin_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" aria-label="LinkedIn">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                            @endif
                            @if($generalSettings->facebook_url)
                                <a href="{{ $generalSettings->facebook_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if($generalSettings->twitter_url)
                                <a href="{{ $generalSettings->twitter_url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} {{ ($generalSettings->company_name ?? null) ?? 'Company' }}. {{ ($generalSettings->copyright_text ?? null) ?? 'All rights reserved.' }}
                </p>
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
