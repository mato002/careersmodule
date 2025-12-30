<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin · Fortress Lenders')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body class="bg-[#F0F9F8] text-teal-950 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-white via-[#F0F9F8] to-[#E1F4F2]">
        <aside id="sidebar" class="hidden lg:flex lg:flex-col h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white border-r border-teal-900/40 shadow-2xl lg:fixed lg:inset-y-0 lg:left-0 transition-all duration-300 ease-in-out overflow-hidden" style="width: 288px;" aria-label="Admin sidebar navigation">
            <div class="px-4 py-6 border-b border-white/10 flex items-center gap-3 flex-shrink-0 relative">
                @if(isset($logoPath) && $logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-10 w-auto object-contain sidebar-logo flex-shrink-0">
                @else
                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300 sidebar-logo flex-shrink-0">FL</div>
                @endif
                <div class="sidebar-text flex-1 min-w-0">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">Admin</p>
                    <p class="text-lg font-bold truncate">Fortress Lenders</p>
                </div>
                <button id="sidebar-toggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors flex-shrink-0 z-10" onclick="toggleSidebarCollapse()" title="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto overflow-x-hidden sidebar-nav min-h-0" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                @if(!auth()->user() || !auth()->user()->isClient())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Dashboard" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                @endif
                @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.team-members.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.team-members.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Team" @if(request()->routeIs('admin.team-members.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="sidebar-text">Team</span>
                </a>
                @endif
                
                @if(!auth()->user() || !auth()->user()->isClient())
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.contact-messages.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Contact Messages" @if(request()->routeIs('admin.contact-messages.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="sidebar-text">Contact Messages</span>
                </a>
                @endif
                @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.activity-logs.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Activity Logs" @if(request()->routeIs('admin.activity-logs.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="sidebar-text">Activity Logs</span>
                </a>
                @endif
                
                <!-- Careers Dropdown - Visible to Admin, HR Manager, and Clients -->
                @if(auth()->user() && auth()->user()->canAccessCareers())
                <div class="nav-dropdown">
                    <button type="button" onclick="toggleDropdown('careers-dropdown')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.jobs.*') || request()->routeIs('admin.job-applications.*') || request()->routeIs('admin.aptitude-test.*') || request()->routeIs('admin.self-interview.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Careers">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="sidebar-text flex-1 text-left">Careers</span>
                        <svg id="careers-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="careers-dropdown" class="dropdown-menu hidden ml-4 mt-1 space-y-1">
                        <a href="{{ route('admin.jobs.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.jobs.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Job Posts">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="sidebar-text">Job Posts</span>
                        </a>
                        <a href="{{ route('admin.job-applications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.job-applications.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Job Applications">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="sidebar-text">Job Applications</span>
                        </a>
                        <a href="{{ route('admin.aptitude-test.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.aptitude-test.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Aptitude Test Questions">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span class="sidebar-text">Aptitude Test Questions</span>
                        </a>
                        <a href="{{ route('admin.self-interview.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.self-interview.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Self Interview Questions">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h8m-8 4h6M4 5a2 2 0 012-2h12a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5z"/>
                            </svg>
                            <span class="sidebar-text">Self Interview Questions</span>
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Token Management - Admin Only -->
                @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.tokens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.tokens.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Token Management" @if(request()->routeIs('admin.tokens.*')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="sidebar-text">Token Management</span>
                </a>
                @endif
                
                <!-- Profile Settings - Available to all users -->
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.profile') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Profile Settings" @if(request()->routeIs('admin.profile')) aria-current="page" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="sidebar-text">Profile Settings</span>
                </a>
                
                <!-- Settings Dropdown - Admin Only (Hidden for clients) -->
                @if(!auth()->user() || !auth()->user()->isClient())
                @if(auth()->user() && auth()->user()->isAdmin())
                <div class="nav-dropdown">
                    <button type="button" onclick="toggleDropdown('settings-dropdown')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.logo.*') || request()->routeIs('admin.api.*') || request()->routeIs('admin.general.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.companies.*') || request()->routeIs('admin.tokens.*') || request()->routeIs('admin.home.*') || request()->routeIs('admin.about.*') || request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Settings">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="sidebar-text flex-1 text-left">Settings</span>
                        <svg id="settings-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="settings-dropdown" class="dropdown-menu hidden ml-4 mt-1 space-y-1">
                        <a href="{{ route('admin.general.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.general.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="General Settings">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="sidebar-text">General Settings</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="User Management">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="sidebar-text">User Management</span>
                        </a>
                        <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.companies.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Company Management">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="sidebar-text">Company Management</span>
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.permissions.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Role Permissions">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="sidebar-text">Role Permissions</span>
                        </a>
                        <a href="{{ route('admin.logo.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Logo Settings">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="sidebar-text">Logo Settings</span>
                        </a>
                        <a href="{{ route('admin.api.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.api.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="API Settings">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            <span class="sidebar-text">API Settings</span>
                        </a>
                        <a href="{{ route('admin.tokens.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.tokens.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Token Management">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="sidebar-text">Token Management</span>
                        </a>
                    </div>
                </div>
                @endif
                @endif
            </nav>
            <div class="px-6 py-6 border-t border-white/10 space-y-3 flex-shrink-0 sidebar-footer">
                <a href="{{ route('careers.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm font-semibold sidebar-link" target="_blank" rel="noopener" title="Open public website in a new tab">
                    <span class="sidebar-text">View Website (opens in new tab)</span>
                </a>
            </div>
        </aside>

        <div id="main-content" class="flex flex-col min-h-screen pl-0 lg:pl-[288px]">
            <div class="lg:hidden">
                <div id="mobile-backdrop" class="fixed inset-0 bg-black/50 hidden z-40" onclick="toggleSidebar()"></div>
                <aside id="mobile-menu" class="fixed inset-y-0 left-0 w-72 bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white z-50 transform -translate-x-full transition-transform duration-300 shadow-2xl flex flex-col overflow-hidden">
                    <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-3">
                            @if(isset($logoPath) && $logoPath)
                                <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-10 w-auto object-contain">
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">FL</div>
                            @endif
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-white/70">Admin</p>
                                <p class="text-lg font-bold">Fortress Lenders</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg border border-white/20" onclick="toggleSidebar()">✕</button>
                    </div>
                    <nav class="px-4 py-6 space-y-2 overflow-y-auto overflow-x-hidden flex-1 min-h-0" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                        @if(!auth()->user() || !auth()->user()->isClient())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        @endif
                        @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.team-members.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.team-members.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Team</span>
                        </a>
                        @endif
                        
                        @if(!auth()->user() || !auth()->user()->isClient())
                        <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.contact-messages.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Contact Messages</span>
                        </a>
                        @endif
                        @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.activity-logs.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            <span>Activity Logs</span>
                        </a>
                        @endif
                        
                        <!-- Careers Dropdown - Visible to Admin, HR Manager, and Clients -->
                        @if(auth()->user() && auth()->user()->canAccessCareers())
                        <div class="mobile-dropdown">
                            <button type="button" onclick="toggleMobileDropdown('mobile-careers-dropdown')" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.jobs.*') || request()->routeIs('admin.job-applications.*') || request()->routeIs('admin.aptitude-test.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Careers</span>
                                </div>
                                <svg id="mobile-careers-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="mobile-careers-dropdown" class="mobile-dropdown-menu hidden ml-4 mt-1 space-y-1">
                                <a href="{{ route('admin.jobs.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.jobs.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Job Posts</span>
                                </a>
                                <a href="{{ route('admin.job-applications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.job-applications.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Job Applications</span>
                                </a>
                                <a href="{{ route('admin.aptitude-test.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.aptitude-test.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <span>Aptitude Test Questions</span>
                                </a>
                                <a href="{{ route('admin.self-interview.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.self-interview.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h8m-8 4h6M4 5a2 2 0 012-2h12a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5z"/>
                                    </svg>
                                    <span>Self Interview Questions</span>
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Token Management - Admin Only -->
                        @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.tokens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.tokens.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Token Management</span>
                        </a>
                        @endif
                        
                        <!-- Profile Settings - Available to all admin users -->
                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.profile') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profile Settings</span>
                        </a>
                        
                        <!-- Settings Dropdown - Admin Only -->
                        @if(auth()->user() && auth()->user()->isAdmin())
                        <div class="mobile-dropdown">
                                <button type="button" onclick="toggleMobileDropdown('mobile-settings-dropdown')" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.logo.*') || request()->routeIs('admin.api.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.companies.*') || request()->routeIs('admin.general.*') || request()->routeIs('admin.tokens.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Settings</span>
                                </div>
                                <svg id="mobile-settings-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                </button>
                            <div id="mobile-settings-dropdown" class="mobile-dropdown-menu hidden ml-4 mt-1 space-y-1">
                                <a href="{{ route('admin.general.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.general.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>General Settings</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.users.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>User Management</span>
                                </a>
                                <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.companies.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>Company Management</span>
                                </a>
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>User Management</span>
                                </a>
                                <a href="{{ route('admin.permissions.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.permissions.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Role Permissions</span>
                                </a>
                                <a href="{{ route('admin.logo.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Logo Settings</span>
                                </a>
                                <a href="{{ route('admin.api.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.api.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    <span>API Settings</span>
                                </a>
                                <a href="{{ route('admin.tokens.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('admin.tokens.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Token Management</span>
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        <div class="border-t border-white/10 my-2"></div>
                        <a href="{{ route('careers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white/80 hover:bg-white/10">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>View Website</span>
                        </a>
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
                                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.3em] text-teal-500">Admin Panel</p>
                                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-teal-900 truncate">@yield('title', 'Dashboard')</h1>
                                    <p class="text-xs sm:text-sm text-teal-600 line-clamp-2">@yield('header-description', "Monitor everything that's happening today.")</p>
                            </div>
                        </div>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <!-- Messages Icon - Hidden on mobile -->
                                <a href="{{ route('admin.contact-messages.index') }}" class="hidden sm:flex relative p-2 rounded-full border border-teal-100 text-teal-500 hover:text-amber-500 bg-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-4.2A2 2 0 0 0 16.7 11H15V7a3 3 0 1 0-6 0v4H7.3a2 2 0 0 0-1.9 1.8L4 17h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 21h6"/>
                                    </svg>
                                    @if(($adminUnreadMessagesCount ?? 0) > 0)
                                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs rounded-full px-1">
                                            {{ $adminUnreadMessagesCount > 9 ? '9+' : $adminUnreadMessagesCount }}
                                        </span>
                                    @endif
                                </a>
                                <!-- Profile Menu -->
                                <div class="relative">
                                    <button id="profile-menu-trigger" class="flex items-center gap-1.5 sm:gap-3 bg-white border border-teal-100 rounded-xl sm:rounded-2xl px-2 sm:px-3 py-1.5 sm:py-1 shadow-sm hover:border-amber-200 transition" onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false">
                                        <div class="text-right leading-tight hidden md:block">
                                            <p class="text-xs text-teal-500">Welcome back</p>
                                            <p class="text-sm font-semibold text-teal-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                        </div>
                                        <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-teal-600 to-emerald-500 text-white font-semibold flex items-center justify-center text-xs sm:text-sm flex-shrink-0">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}
                                        </span>
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>
                                    <div id="profile-menu" class="hidden absolute right-0 mt-2 sm:mt-3 w-56 max-w-[calc(100vw-2rem)] bg-white rounded-xl sm:rounded-2xl shadow-xl border border-teal-50 overflow-hidden z-50">
                                        <div class="px-4 py-3 border-b border-teal-50">
                                            <p class="text-sm font-semibold text-teal-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                            <p class="text-xs text-teal-500 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                                        </div>
                                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-teal-800 hover:bg-teal-50 transition">
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
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                        <div class="relative flex-1 min-w-0 sm:min-w-[220px] sm:max-w-md">
                            <input type="text" id="admin-search-input" placeholder="Search..." class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2 text-sm rounded-xl sm:rounded-2xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:border-transparent" autocomplete="off">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 absolute left-2.5 sm:left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/>
                            </svg>
                            <div id="search-results" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-200 z-50 max-h-96 overflow-y-auto"></div>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            @hasSection('header-actions')
                                @yield('header-actions')
                            @else
                                <button class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-teal-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-white whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/>
                                    </svg>
                                    <span class="hidden sm:inline">Refresh</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                </header>

                <main class="flex-1 px-3 sm:px-4 lg:px-10 py-4 sm:py-6 lg:py-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-teal-200 bg-white px-4 py-3 text-teal-900 shadow-sm">
                            {{ session('status') }}
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
        /* Mobile responsive improvements */
        @media (max-width: 640px) {
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }
        
        /* Responsive table wrapper */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive table {
            min-width: 640px;
        }
        
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

        /* Collapsed sidebar styles */
        #sidebar.collapsed {
            width: 80px !important;
        }
        #sidebar.collapsed .sidebar-text,
        #sidebar.collapsed .sidebar-link span:not(.icon-only) {
            opacity: 0;
            width: 0;
            overflow: hidden;
            display: none;
        }
        #sidebar.collapsed nav a {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        #sidebar.collapsed #sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: 0.5rem;
            margin: 0;
            z-index: 20;
        }
        #sidebar.collapsed #sidebar-toggle svg {
            transform: rotate(180deg);
        }
        #sidebar:not(.collapsed) #sidebar-toggle svg {
            transform: rotate(0deg);
        }
        #sidebar.collapsed > div:first-child {
            justify-content: center;
            padding: 1rem 0.5rem;
            position: relative;
        }
        #sidebar.collapsed .sidebar-logo {
            margin: 0 auto;
        }
        
        /* Dropdown styles */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.2s ease-out;
            opacity: 0;
        }
        .dropdown-menu.open {
            max-height: 500px;
            opacity: 1;
        }
        .dropdown-menu.hidden {
            display: none;
            max-height: 0;
            opacity: 0;
        }
        
        /* Mobile dropdown styles */
        .mobile-dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.2s ease-out;
            opacity: 0;
        }
        .mobile-dropdown-menu.open {
            max-height: 500px;
            opacity: 1;
        }
        .mobile-dropdown-menu.hidden {
            display: none;
            max-height: 0;
            opacity: 0;
        }
        #sidebar.collapsed .dropdown-menu {
            display: none !important;
        }
        #sidebar.collapsed .nav-dropdown button {
            justify-content: center;
        }
        .dropdown-arrow-rotated {
            transform: rotate(180deg);
        }
        
        /* Main content padding - desktop only */
        @media (min-width: 1024px) {
            #main-content {
                transition: padding-left 0.3s ease-in-out;
            }
        }
        
        /* Ensure no padding on mobile */
        @media (max-width: 1023px) {
            #main-content {
                padding-left: 0 !important;
            }
        }
    </style>

    <script>
        // Sidebar collapse functionality (desktop only)
        function toggleSidebarCollapse() {
            // Only work on desktop (lg screens and above)
            if (window.innerWidth < 1024) return;
            
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const isCollapsed = sidebar.classList.contains('collapsed');
            
            if (isCollapsed) {
                // Expanding sidebar
                sidebar.classList.remove('collapsed');
                mainContent.style.paddingLeft = '288px';
                localStorage.setItem('sidebarCollapsed', 'false');
            } else {
                // Collapsing sidebar
                sidebar.classList.add('collapsed');
                mainContent.style.paddingLeft = '80px';
                localStorage.setItem('sidebarCollapsed', 'true');
            }
        }

        // Dropdown toggle functionality
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            if (!dropdown) return;
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== dropdownId) {
                    menu.classList.add('hidden');
                    menu.classList.remove('open');
                }
            });
            document.querySelectorAll('[id$="-arrow"]').forEach(arr => {
                if (arr.id !== dropdownId + '-arrow') {
                    arr.classList.remove('dropdown-arrow-rotated');
                }
            });
            
            // Toggle current dropdown
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => dropdown.classList.add('open'), 10);
                if (arrow) arrow.classList.add('dropdown-arrow-rotated');
            } else {
                dropdown.classList.remove('open');
                setTimeout(() => dropdown.classList.add('hidden'), 300);
                if (arrow) arrow.classList.remove('dropdown-arrow-rotated');
            }
        }

        // Auto-open dropdown if current page is in it
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            // Only apply sidebar collapse on desktop (lg screens and above)
            if (window.innerWidth >= 1024) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.style.paddingLeft = '80px';
                } else {
                    mainContent.style.paddingLeft = '288px';
                }
            } else {
                // On mobile, ensure no padding
                mainContent.style.paddingLeft = '0';
            }
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth < 1024) {
                        // Mobile: no padding
                        mainContent.style.paddingLeft = '0';
                    } else {
                        // Desktop: apply saved state
                        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (isCollapsed) {
                            mainContent.style.paddingLeft = '80px';
                        } else {
                            mainContent.style.paddingLeft = '288px';
                        }
                    }
                }, 250);
            });
            
            // Auto-open relevant dropdowns (desktop)
            @if(request()->routeIs('admin.jobs.*') || request()->routeIs('admin.job-applications.*') || request()->routeIs('admin.aptitude-test.*') || request()->routeIs('admin.self-interview.*'))
                if (!sidebar.classList.contains('collapsed')) {
                    toggleDropdown('careers-dropdown');
                }
                // Also open mobile dropdown if on mobile
                if (window.innerWidth < 1024) {
                    toggleMobileDropdown('mobile-careers-dropdown');
                }
            @endif
            @if(auth()->user() && auth()->user()->isAdmin())
                @if(request()->routeIs('admin.logo.*') || request()->routeIs('admin.api.*') || request()->routeIs('admin.general.*') || request()->routeIs('admin.users.*'))
                    if (!sidebar.classList.contains('collapsed')) {
                        toggleDropdown('settings-dropdown');
                    }
                    // Also open mobile dropdown if on mobile
                    if (window.innerWidth < 1024) {
                        toggleMobileDropdown('mobile-settings-dropdown');
                    }
                @endif
            @endif
        });

        function toggleSidebar() {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const isOpen = !menu.classList.contains('-translate-x-full');
            menu.classList.toggle('-translate-x-full', isOpen);
            backdrop.classList.toggle('hidden', isOpen);
        }

        // Mobile dropdown toggle functionality
        function toggleMobileDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            if (!dropdown) return;
            
            // Close all other mobile dropdowns
            document.querySelectorAll('.mobile-dropdown-menu').forEach(menu => {
                if (menu.id !== dropdownId) {
                    menu.classList.add('hidden');
                    menu.classList.remove('open');
                }
            });
            document.querySelectorAll('[id^="mobile-"][id$="-arrow"]').forEach(arr => {
                if (arr.id !== dropdownId + '-arrow') {
                    arr.classList.remove('dropdown-arrow-rotated');
                }
            });
            
            // Toggle current dropdown
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => dropdown.classList.add('open'), 10);
                if (arrow) arrow.classList.add('dropdown-arrow-rotated');
            } else {
                dropdown.classList.remove('open');
                setTimeout(() => dropdown.classList.add('hidden'), 300);
                if (arrow) arrow.classList.remove('dropdown-arrow-rotated');
            }
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden', isOpen);
            trigger.setAttribute('aria-expanded', (!isOpen).toString());
        }

        document.addEventListener('click', (event) => {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            if (trigger.contains(event.target)) {
                return;
            }

            if (!menu.contains(event.target)) {
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

        // Admin Search Functionality
        (function() {
            const searchInput = document.getElementById('admin-search-input');
            const searchResults = document.getElementById('search-results');
            let searchTimeout;
            const searchUrl = @json(route('admin.search'));

            if (!searchInput || !searchResults) {
                console.error('Search elements not found');
                return;
            }

            const iconMap = {
                'package': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                'briefcase': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'file-text': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'mail': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'dollar-sign': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'user': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                'map-pin': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
            };

            function performSearch(query) {
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                const url = `${searchUrl}?q=${encodeURIComponent(query)}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.results) {
                        displayResults(data.results, query);
                    } else {
                        displayResults([], query);
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = `
                        <div class="p-4 text-center text-red-500">
                            <p>Error performing search. Please try again.</p>
                        </div>
                    `;
                    searchResults.classList.remove('hidden');
                });
            }

            function displayResults(results, query) {
                if (results.length === 0) {
                    searchResults.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <p>No results found for "<strong>${escapeHtml(query)}</strong>"</p>
                        </div>
                    `;
                    searchResults.classList.remove('hidden');
                    return;
                }

                const html = results.map(result => {
                    const icon = iconMap[result.icon] || iconMap['file-text'];
                    return `
                        <a href="${result.url}" class="block px-4 py-3 hover:bg-teal-50 border-b border-gray-100 last:border-b-0 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="text-teal-600 mt-0.5 flex-shrink-0">
                                    ${icon}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">${escapeHtml(result.title)}</p>
                                    <p class="text-sm text-gray-600 truncate">${escapeHtml(result.description)}</p>
                                    <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded bg-teal-100 text-teal-700">${escapeHtml(result.type)}</span>
                                </div>
                            </div>
                        </a>
                    `;
                }).join('');

                searchResults.innerHTML = html;
                searchResults.classList.remove('hidden');
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            searchInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    performSearch(query);
                }
            });

            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });

            // Handle Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstResult = searchResults.querySelector('a');
                    if (firstResult) {
                        window.location.href = firstResult.href;
                    }
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
