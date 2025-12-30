@extends('layouts.admin')

@section('title', 'Role Permissions')
@section('header-description', 'Manage access permissions for each role in the system.')

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 border border-teal-200 rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-teal-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="hidden sm:inline">Back to Users</span>
        <span class="sm:hidden">Back</span>
    </a>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Info Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-sm border border-blue-100 p-4 sm:p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Role-Based Access Control</h3>
                    <p class="text-sm text-blue-700 leading-relaxed">
                        Use the checkboxes below to modify access permissions for each role. Changes are saved immediately when you click "Save Permissions". 
                        Permissions are enforced at both the route level (via middleware) and the UI level (navigation visibility).
                    </p>
                </div>
            </div>
        </div>

        <!-- Permissions Form -->
        <form method="POST" action="{{ route('admin.permissions.update') }}" id="permissions-form">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                            <tr>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                    Section / Feature
                                </th>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-center text-xs font-bold text-purple-700 uppercase tracking-wider">
                                    <div class="flex flex-col items-center gap-1">
                                        <span>Administrator</span>
                                        <span class="text-xs font-normal text-gray-500">Full Access</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-center text-xs font-bold text-pink-700 uppercase tracking-wider">
                                    <div class="flex flex-col items-center gap-1">
                                        <span>HR Manager</span>
                                        <span class="text-xs font-normal text-gray-500">Careers Focus</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider">
                                    <div class="flex flex-col items-center gap-1">
                                        <span>Editor</span>
                                        <span class="text-xs font-normal text-gray-500">Content Focus</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-center text-xs font-bold text-teal-700 uppercase tracking-wider">
                                    <div class="flex flex-col items-center gap-1">
                                        <span>Client</span>
                                        <span class="text-xs font-normal text-gray-500">Careers Only</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 sm:px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <div class="flex flex-col items-center gap-1">
                                        <span>User</span>
                                        <span class="text-xs font-normal text-gray-500">No Access</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($permissions as $permission)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $permission->permission_name }}</div>
                                        @if($permission->permission_group)
                                            <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($permission->permission_group) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $permission->permission_key }}][admin]" 
                                                value="1"
                                                {{ $permission->hasRole('admin') ? 'checked' : '' }}
                                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                                            >
                                        </label>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $permission->permission_key }}][hr_manager]" 
                                                value="1"
                                                {{ $permission->hasRole('hr_manager') ? 'checked' : '' }}
                                                class="w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500 focus:ring-2"
                                            >
                                        </label>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $permission->permission_key }}][editor]" 
                                                value="1"
                                                {{ $permission->hasRole('editor') ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2"
                                            >
                                        </label>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $permission->permission_key }}][client]" 
                                                value="1"
                                                {{ $permission->hasRole('client') ? 'checked' : '' }}
                                                class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500 focus:ring-2"
                                            >
                                        </label>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[{{ $permission->permission_key }}][user]" 
                                                value="1"
                                                {{ $permission->hasRole('user') ? 'checked' : '' }}
                                                class="w-5 h-5 text-gray-600 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                                            >
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="text-sm text-gray-600">
                    <p class="font-medium">Make changes to permissions above, then click "Save Permissions" to apply.</p>
                </div>
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm hover:shadow"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Permissions
                </button>
            </div>
        </form>

        <!-- Role Summaries -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
            <!-- Administrator -->
            <div class="bg-white rounded-2xl shadow-sm border border-purple-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-4 sm:px-6 py-4 border-b border-purple-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-purple-900">Administrator</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="text-sm text-gray-700 mb-4">Full system access with no restrictions. Can manage all sections, users, and settings.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            All sections accessible
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            User management
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            System settings
                        </li>
                    </ul>
                </div>
            </div>

            <!-- HR Manager -->
            <div class="bg-white rounded-2xl shadow-sm border border-pink-200 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 px-4 sm:px-6 py-4 border-b border-pink-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-pink-900">HR Manager</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="text-sm text-gray-700 mb-4">Focuses on career-related activities including job posts and applications.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Job posts & applications
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Content management
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            No loan access
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Editor -->
            <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 sm:px-6 py-4 border-b border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-green-900">Editor</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="text-sm text-gray-700 mb-4">Manages website content including blog posts, FAQs, and CEO messages.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Blog & content
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Products & messages
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            No admin features
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Client (Company Admin) -->
            <div class="bg-white rounded-2xl shadow-sm border border-teal-200 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-4 sm:px-6 py-4 border-b border-teal-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-teal-900">Client</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="text-sm text-gray-700 mb-4">SaaS clients with access only to the careers module for managing job applications.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Job posts & applications
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Aptitude & interview tests
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            No other admin access
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
