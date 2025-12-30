<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Applications · Fortress Lenders')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body class="bg-[#F0F9F8] text-teal-950 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-white via-[#F0F9F8] to-[#E1F4F2]">
        <aside id="sidebar" class="hidden lg:flex lg:flex-col h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white border-r border-teal-900/40 shadow-2xl lg:fixed lg:inset-y-0 lg:left-0 transition-all duration-300 ease-in-out overflow-hidden" style="width: 288px;" aria-label="Candidate sidebar navigation">
            <div class="px-4 py-6 border-b border-white/10 flex items-center gap-3 flex-shrink-0 relative">
                <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300 sidebar-logo flex-shrink-0">F</div>
                <div class="sidebar-text flex-1 min-w-0">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">Candidate</p>
                    <p class="text-lg font-bold truncate">Fortress Lenders</p>
                </div>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto overflow-x-hidden sidebar-nav min-h-0" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                <a href="{{ session('isAdminView') || (isset($isAdminView) && $isAdminView) ? '#' : route('candidate.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('candidate.dashboard') || request()->routeIs('admin.job-applications.view-candidate-dashboard') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="My Applications" @if(request()->routeIs('candidate.dashboard') || request()->routeIs('admin.job-applications.view-candidate-dashboard')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="sidebar-text">My Applications</span>
                </a>
                <a href="{{ session('isAdminView') || (isset($isAdminView) && $isAdminView) ? '#' : route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('profile.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Profile Settings" @if(request()->routeIs('profile.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="sidebar-text">Profile Settings</span>
                </a>
            </nav>
            @if(!session('isAdminView') && !(isset($isAdminView) && $isAdminView))
                <div class="px-6 py-6 border-t border-white/10 space-y-3 flex-shrink-0 sidebar-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm font-semibold sidebar-link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="px-6 py-6 border-t border-white/10 space-y-3 flex-shrink-0 sidebar-footer">
                    <a href="{{ route('admin.job-applications.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm font-semibold sidebar-link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span class="sidebar-text">Back to Admin</span>
                    </a>
                </div>
            @endif
        </aside>

        <div id="main-content" class="flex flex-col min-h-screen pl-0 lg:pl-[288px]">
            @if(session('isAdminView') || (isset($isAdminView) && $isAdminView))
                <div class="bg-amber-500 border-b border-amber-600 px-4 py-3 text-white">
                    <div class="max-w-7xl mx-auto flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="font-semibold">Admin Preview Mode</span>
                            <span class="text-sm text-amber-100">- Viewing as: {{ $candidate->name ?? 'Candidate' }}</span>
                        </div>
                        <a href="{{ route('admin.job-applications.index') }}" class="text-sm font-semibold hover:text-amber-100 underline">
                            Back to Admin Panel
                        </a>
                    </div>
                </div>
            @endif
            <div class="lg:hidden">
                <div id="mobile-backdrop" class="fixed inset-0 bg-black/50 hidden z-40" onclick="toggleSidebar()"></div>
                <aside id="mobile-menu" class="fixed inset-y-0 left-0 w-72 bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white z-50 transform -translate-x-full transition-transform duration-300 shadow-2xl flex flex-col overflow-hidden">
                    <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">F</div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-white/70">Candidate</p>
                                <p class="text-lg font-bold">Fortress Lenders</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg border border-white/20" onclick="toggleSidebar()">✕</button>
                    </div>
                    <nav class="px-4 py-6 space-y-2 overflow-y-auto overflow-x-hidden flex-1 min-h-0" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                        <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('candidate.dashboard') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>My Applications</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('profile.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profile Settings</span>
                        </a>
                        <div class="border-t border-white/10 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white/80 hover:bg-white/10">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </aside>
            </div>

            <div class="flex-1 flex flex-col min-h-screen">
                <header class="bg-white/80 backdrop-blur border-b border-teal-100 shadow-sm sticky top-0 z-20">
                    <div class="px-3 sm:px-4 lg:px-10 py-3 sm:py-4 lg:py-5 space-y-3 sm:space-y-4">
                        <div class="flex items-start sm:items-center justify-between gap-2 sm:gap-4">
                            <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                <button class="lg:hidden p-2 rounded-lg border border-teal-200 text-teal-700 flex-shrink-0" onclick="toggleSidebar()" aria-label="Toggle menu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.3em] text-teal-500">Candidate Portal</p>
                                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-teal-900 truncate">@yield('title', 'My Applications')</h1>
                                    <p class="text-xs sm:text-sm text-teal-600 line-clamp-2">@yield('header-description', 'Track and manage your job applications')</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <!-- Profile Menu -->
                                <div class="relative">
                                    <button id="profile-menu-trigger" class="flex items-center gap-1.5 sm:gap-3 bg-white border border-teal-100 rounded-xl sm:rounded-2xl px-2 sm:px-3 py-1.5 sm:py-1 shadow-sm hover:border-amber-200 transition" onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false">
                                        @php
                                            $currentCandidate = auth()->guard('candidate')->user() ?? ($candidate ?? null);
                                        @endphp
                                        <div class="text-right leading-tight hidden md:block">
                                            <p class="text-xs text-teal-500">Welcome</p>
                                            <p class="text-sm font-semibold text-teal-800">{{ $currentCandidate->name ?? 'Candidate' }}</p>
                                        </div>
                                        <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-teal-600 to-emerald-500 text-white font-semibold flex items-center justify-center text-xs sm:text-sm flex-shrink-0">
                                            {{ strtoupper(substr($currentCandidate->name ?? 'CA', 0, 2)) }}
                                        </span>
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>
                                    <div id="profile-menu" class="hidden absolute right-0 mt-2 sm:mt-3 w-56 max-w-[calc(100vw-2rem)] bg-white rounded-xl sm:rounded-2xl shadow-xl border border-teal-50 overflow-hidden z-50">
                                        <div class="px-4 py-3 border-b border-teal-50">
                                            <p class="text-sm font-semibold text-teal-900">{{ $currentCandidate->name ?? 'Candidate' }}</p>
                                            <p class="text-xs text-teal-500 truncate">{{ $currentCandidate->email ?? 'candidate@example.com' }}</p>
                                        </div>
                                        @if(!session('isAdminView') && !(isset($isAdminView) && $isAdminView))
                                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-teal-800 hover:bg-teal-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A4 4 0 0 1 4 15.172V5a2 2 0 0 1 2-2h6.172a4 4 0 0 1 2.829 1.172l4.828 4.828A4 4 0 0 1 20 11.828V19a2 2 0 0 1-2 2h-4"/>
                                                </svg>
                                                Profile
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}" class="border-t border-teal-50">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3m0 0 4-4m-4 4 4 4m8-10h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/>
                                                    </svg>
                                                    Logout
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.job-applications.index') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-teal-800 hover:bg-teal-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                                </svg>
                                                Back to Admin Panel
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                            @hasSection('header-actions')
                                @yield('header-actions')
                            @endif
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-3 sm:px-4 lg:px-10 py-4 sm:py-6 lg:py-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-teal-200 bg-white px-4 py-3 text-teal-900 shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </main>

                <footer class="bg-white/80 border-t border-teal-100 py-3 sm:py-4">
                    <div class="px-3 sm:px-4 lg:px-10 text-xs sm:text-sm text-teal-600 flex flex-col sm:flex-row justify-between items-center gap-2">
                        <span>© {{ now()->year }} Fortress Lenders Ltd.</span>
                        <span>Need help? <a href="mailto:{{ $generalSettings->company_email ?? 'support@example.com' }}" class="text-amber-600 font-semibold">Contact support</a></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar,
        #mobile-menu nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav::-webkit-scrollbar-track,
        #mobile-menu nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb,
        #mobile-menu nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover,
        #mobile-menu nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Mobile responsive improvements */
        @media (max-width: 640px) {
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }
    </style>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            if (menu.classList.contains('-translate-x-full')) {
                menu.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                menu.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Profile menu toggle
        function toggleProfileMenu() {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (menu && trigger) {
                menu.classList.toggle('hidden');
                const isExpanded = !menu.classList.contains('hidden');
                trigger.setAttribute('aria-expanded', isExpanded);
            }
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', (event) => {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            if (!trigger.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Close profile menu when clicking outside on mobile
        document.addEventListener('touchstart', (event) => {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            if (!trigger.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
