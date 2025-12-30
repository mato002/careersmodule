<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Client Dashboard · Career SaaS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body class="bg-[#F0F9F8] text-teal-950 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-white via-[#F0F9F8] to-[#E1F4F2]">
        <!-- Mobile Header -->
        <header class="lg:hidden bg-gradient-to-r from-teal-900 via-teal-800 to-teal-900 text-white shadow-lg sticky top-0 z-50">
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">CS</div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-white/70">Client</p>
                        <p class="text-sm font-bold">{{ auth()->user()->company->name ?? 'Dashboard' }}</p>
                    </div>
                </div>
                <button id="mobile-menu-toggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 bg-teal-900">
            <div class="flex flex-col h-full">
                <div class="px-4 py-6 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">CS</div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-white/70">Client</p>
                            <p class="text-sm font-bold">{{ auth()->user()->company->name ?? 'Dashboard' }}</p>
                        </div>
                    </div>
                    <button id="mobile-menu-close" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('client.dashboard') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('client.tokens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('client.tokens.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Token Management</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white/75 hover:bg-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex lg:flex-col h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white border-r border-teal-900/40 shadow-2xl fixed inset-y-0 left-0" style="width: 288px;">
            <div class="px-4 py-6 border-b border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">CS</div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">Client</p>
                    <p class="text-lg font-bold truncate">{{ auth()->user()->company->name ?? 'Dashboard' }}</p>
                </div>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('client.dashboard') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('client.tokens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('client.tokens.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Token Management</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white/75 hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="lg:ml-[288px] min-h-screen">
            <div class="p-4 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                        {{ session('warning') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.remove('translate-x-full');
        });

        document.getElementById('mobile-menu-close')?.addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.add('translate-x-full');
        });

        // Close mobile menu when clicking outside
        document.getElementById('mobile-sidebar')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('translate-x-full');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

