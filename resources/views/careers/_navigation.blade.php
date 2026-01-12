@php
    $generalSettings = \App\Models\GeneralSetting::query()->latest()->first() ?? new \App\Models\GeneralSetting();
@endphp
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








