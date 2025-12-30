@extends('layouts.admin')

@section('title', 'Users')
@section('header-description', 'Manage system users and their access roles.')

@section('header-actions')
    <div class="flex items-center gap-2 sm:gap-3">
        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 whitespace-nowrap">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span class="hidden sm:inline">View Permissions</span>
            <span class="sm:hidden">Permissions</span>
        </a>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">Add User</span>
            <span class="sm:hidden">Add</span>
        </a>
    </div>
@endsection

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="group relative bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Users</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalUsersCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-emerald-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Administrators</p>
                <p class="text-4xl font-bold text-emerald-700">{{ $adminUsersCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Regular Users</p>
                <p class="text-4xl font-bold text-amber-600">{{ $regularUsersCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-purple-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-purple-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Filtered Results</p>
                <p class="text-4xl font-bold text-purple-600">{{ $filteredUsersCount }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or role..." 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="all">All Roles</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Admin Status</label>
                    <select name="is_admin" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="all">All Users</option>
                        <option value="1" {{ request('is_admin') === '1' ? 'selected' : '' }}>Admins Only</option>
                        <option value="0" {{ request('is_admin') === '0' ? 'selected' : '' }}>Non-Admins</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-5">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm shadow-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'role', 'is_admin']))
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                        Clear Filters
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-slate-600 uppercase tracking-wide text-xs font-semibold">
                <tr>
                    <th class="px-4 sm:px-6 py-4">User</th>
                    <th class="px-4 sm:px-6 py-4 hidden sm:table-cell">Email</th>
                    <th class="px-4 sm:px-6 py-4">Role</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Status</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Created</th>
                    <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-slate-200 flex-shrink-0 shadow-sm flex items-center justify-center">
                                    <div class="w-full h-full flex items-center justify-center text-sm font-bold text-blue-700">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <p class="text-xs text-blue-600 mt-0.5 font-medium">(You)</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-700 text-sm hidden sm:table-cell">{{ $user->email }}</td>
                        <td class="px-4 sm:px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold 
                                @if($user->role === 'admin') bg-purple-100 text-purple-700 border border-purple-200
                                @elseif($user->role === 'hr_manager') bg-pink-100 text-pink-700 border border-pink-200
                                @elseif($user->role === 'editor') bg-green-100 text-green-700 border border-green-200
                                @else bg-slate-100 text-slate-700 border border-slate-200
                                @endif">
                                {{ $roles[$user->role] ?? ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $user->is_admin ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 sm:px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 sm:gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block delete-form" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden sm:inline">Delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-slate-500 font-medium text-base mb-2">No users found</p>
                                <p class="text-slate-400 text-sm mb-4">Get started by adding your first user</p>
                                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add First User
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $users->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const userName = formElement.getAttribute('data-name') || 'this user';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${userName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the user.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

