<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="description" content="{{ Str::limit(strip_tags($job->description), 160) }}">
    <meta name="keywords" content="careers, jobs, Kenya, recruitment, {{ $job->title }}, {{ $job->department }}">
    <title>{{ $job->title }} - Careers - {{ $generalSettings->company_name ?? 'Company' }}</title>

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
                    <a href="{{ route('careers.index') }}" class="nav-link text-gray-700 hover:text-teal-700 transition-colors">← Back to Jobs</a>
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
                <a href="{{ route('careers.index') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">← Back to Jobs</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20 overflow-x-hidden">
        @php 
        use Illuminate\Support\Str;
        @endphp
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold flex-1">{{ $job->title }}</h1>
                    @php
                        $status = $job->application_status;
                        $statusClasses = $job->status_badge_classes;
                        $statusLabel = $job->status_label;
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusClasses }} whitespace-nowrap">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-4 text-teal-100">
                    @if($job->location)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $job->location }}
                        </span>
                    @endif
                    @if($job->department)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $job->department }}
                        </span>
                    @endif
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Details Section -->
    <section class="py-8 sm:py-12 bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-7xl mx-auto">
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif

                @php
                    $status = $job->application_status;
                    $deadlinePassed = $job->application_deadline && $job->application_deadline->isPast();
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Status Alert -->
                        @if($status === 'closed' || $deadlinePassed)
                            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-red-900 mb-2">Position Closed</h3>
                                        <p class="text-red-800 mb-2">
                                            @if($deadlinePassed && $job->application_deadline)
                                                This position closed on <strong>{{ $job->application_deadline->format('F d, Y') }}</strong>. We are no longer accepting applications for this role.
                                            @else
                                                This position is currently closed and not accepting applications.
                                            @endif
                                        </p>
                                        <p class="text-sm text-red-700 italic">
                                            This posting is kept for reference purposes to show our hiring history.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($status === 'accepting_with_applications')
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-sm border-2 border-blue-200 p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-blue-900 mb-2">Accepting Applications</h3>
                                        <p class="text-blue-800">This position is open and accepting applications. We have already received some applications, but you can still apply.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($status === 'accepting')
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl shadow-sm border-2 border-green-200 p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-green-900 mb-2">Open for Applications</h3>
                                        <p class="text-green-800">This position is currently open and actively accepting applications. Apply now to be considered!</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Job Description Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Job Description</h2>
                            </div>
                            <div class="text-gray-700 leading-relaxed prose prose-teal max-w-none">
                                {!! nl2br(e($job->description)) !!}
                            </div>
                        </div>

                        <!-- Key Responsibilities Card -->
                        @if($job->responsibilities)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Key Responsibilities</h2>
                            </div>
                            <div class="text-gray-700 leading-relaxed prose prose-teal max-w-none">
                                {!! nl2br(e($job->responsibilities)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Requirements Card -->
                        @if($job->requirements)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Requirements</h2>
                            </div>
                            <div class="text-gray-700 leading-relaxed prose prose-teal max-w-none">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Experience Level -->
                        @if($job->experience_level)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Experience Level</h3>
                            </div>
                            <p class="text-gray-700 text-lg">{{ $job->experience_level }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">
                            <!-- Quick Apply Card -->
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Ready to Apply?</h3>
                                @if($deadlinePassed || $status === 'closed' || $status === 'inactive')
                                    <button disabled class="w-full px-6 py-4 bg-gray-300 text-gray-500 rounded-xl cursor-not-allowed font-semibold text-center mb-4">
                                        Applications Closed
                                    </button>
                                @else
                                    <a href="{{ route('careers.apply', $job->slug) }}" class="block w-full px-6 py-4 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-xl hover:from-teal-700 hover:to-teal-800 transition-all font-semibold text-center mb-4 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        Apply Now
                                    </a>
                                @endif
                                
                                <div class="relative">
                                    <button type="button" onclick="toggleShareMenu()" class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        Share Position
                                    </button>
                                    <div id="share-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                                        <a href="#" onclick="shareOnFacebook(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            <span class="text-gray-700 font-medium">Facebook</span>
                                        </a>
                                        <a href="#" onclick="shareOnTwitter(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                            <svg class="w-5 h-5 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                            <span class="text-gray-700 font-medium">Twitter</span>
                                        </a>
                                        <a href="#" onclick="shareOnLinkedIn(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                            <svg class="w-5 h-5 mr-3 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                            <span class="text-gray-700 font-medium">LinkedIn</span>
                                        </a>
                                        <a href="#" onclick="shareOnWhatsApp(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                            <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            <span class="text-gray-700 font-medium">WhatsApp</span>
                                        </a>
                                        <a href="#" onclick="shareViaEmail(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                            <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-700 font-medium">Email</span>
                                        </a>
                                        <a href="#" onclick="copyLink(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                            <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-700 font-medium">Copy Link</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Details Card -->
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Job Details</h3>
                                <div class="space-y-4">
                                    @if($job->location)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Location</p>
                                            <p class="text-gray-900 font-semibold">{{ $job->location }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($job->department)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Department</p>
                                            <p class="text-gray-900 font-semibold">{{ $job->department }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Employment Type</p>
                                            <p class="text-gray-900 font-semibold">{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</p>
                                        </div>
                                    </div>

                                    @if($job->application_deadline)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 {{ $deadlinePassed ? 'bg-red-100' : 'bg-amber-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 {{ $deadlinePassed ? 'text-red-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Application Deadline</p>
                                            <p class="text-gray-900 font-semibold">{{ $job->application_deadline->format('F d, Y') }}</p>
                                            @if($deadlinePassed)
                                                <p class="text-xs text-red-600 font-medium mt-1">Deadline Passed</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Back to Jobs -->
                            <a href="{{ route('careers.index') }}" class="block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-center">
                                ← Back to All Jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedJobs->count() > 0)
        <!-- Related Jobs Section -->
        <section class="py-12 sm:py-16 bg-gray-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Related Positions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $relatedJob->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($relatedJob->description), 100) }}
                                </p>
                                <a href="{{ route('careers.show', $relatedJob->slug) }}" class="text-teal-800 font-semibold hover:text-teal-900">
                                    View Details →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

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
        (function() {
            'use strict';
            
            const url = window.location.href;
            const title = {!! json_encode($job->title) !!};
            const companyName = {!! json_encode($generalSettings->company_name ?? '') !!};
            const text = 'Check out this job opportunity' + (companyName ? ' at ' + companyName : '') + ': ' + title;
            
            function toggleShareMenu() {
                const menu = document.getElementById('share-menu');
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            }
            
            function shareOnFacebook(e) {
                e.preventDefault();
                const shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnTwitter(e) {
                e.preventDefault();
                const shareUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnLinkedIn(e) {
                e.preventDefault();
                const shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnWhatsApp(e) {
                e.preventDefault();
                const shareUrl = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
                window.open(shareUrl, '_blank');
                closeShareMenu();
            }
            
            function shareViaEmail(e) {
                e.preventDefault();
                const subject = encodeURIComponent('Job Opportunity: ' + title);
                const body = encodeURIComponent(text + '\n\n' + url);
                const mailtoUrl = 'mailto:?subject=' + subject + '&body=' + body;
                window.location.href = mailtoUrl;
                closeShareMenu();
            }
            
            function copyLink(e) {
                e.preventDefault();
                copyToClipboard(url);
                closeShareMenu();
            }
            
            function closeShareMenu() {
                const menu = document.getElementById('share-menu');
                if (menu) {
                    menu.classList.add('hidden');
                }
            }
            
            function copyToClipboard(text) {
                // Try modern clipboard API first
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(() => {
                        showNotification('Link copied to clipboard!', 'success');
                    }).catch((err) => {
                        console.error('Clipboard API failed:', err);
                        // Fallback to old method
                        fallbackCopyToClipboard(text);
                    });
                } else {
                    // Fallback for older browsers
                    fallbackCopyToClipboard(text);
                }
            }
            
            function fallbackCopyToClipboard(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        showNotification('Link copied to clipboard!', 'success');
                    } else {
                        showNotification('Unable to copy. Please copy manually: ' + text, 'error');
                    }
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                    showNotification('Unable to copy. Please copy manually: ' + text, 'error');
                }
                
                document.body.removeChild(textArea);
            }
            
            function showNotification(message, type = 'success') {
                // Remove any existing notifications
                const existing = document.querySelector('.share-notification');
                if (existing) {
                    existing.remove();
                }
                
                // Create a simple notification
                const notification = document.createElement('div');
                notification.className = 'share-notification';
                notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: ' + 
                    (type === 'error' ? '#ef4444' : '#10b981') + 
                    '; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 10000; font-weight: 500; max-width: 300px; opacity: 1; transition: opacity 0.3s;';
                notification.textContent = message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }
            
            // Make functions available globally
            window.toggleShareMenu = toggleShareMenu;
            window.shareOnFacebook = shareOnFacebook;
            window.shareOnTwitter = shareOnTwitter;
            window.shareOnLinkedIn = shareOnLinkedIn;
            window.shareOnWhatsApp = shareOnWhatsApp;
            window.shareViaEmail = shareViaEmail;
            window.copyLink = copyLink;
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const shareButton = event.target.closest('button[onclick="toggleShareMenu()"]');
                const shareMenu = document.getElementById('share-menu');
                
                if (!shareButton && !shareMenu?.contains(event.target)) {
                    closeShareMenu();
                }
            });
        })();
    </script>

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

