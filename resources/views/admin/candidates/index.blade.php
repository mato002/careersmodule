@extends('layouts.admin')

@section('title', 'Candidates')
@section('header-description', 'Manage candidates and their applications.')

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Candidates</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalCandidates }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-emerald-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">With Applications</p>
                <p class="text-4xl font-bold text-emerald-700">{{ $withApplicationsCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">No Applications</p>
                <p class="text-4xl font-bold text-amber-600">{{ $withoutApplicationsCount }}</p>
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
                <p class="text-4xl font-bold text-purple-600">{{ $filteredCount }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.candidates.index') }}" class="mb-6">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Applications</label>
                    <select name="has_applications" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="">All Candidates</option>
                        <option value="1" {{ request('has_applications') === '1' ? 'selected' : '' }}>With Applications</option>
                        <option value="0" {{ request('has_applications') === '0' ? 'selected' : '' }}>Without Applications</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-5">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm shadow-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'has_applications']))
                    <a href="{{ route('admin.candidates.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
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
                    <th class="px-4 sm:px-6 py-4">Candidate</th>
                    <th class="px-4 sm:px-6 py-4 hidden sm:table-cell">Email</th>
                    <th class="px-4 sm:px-6 py-4">Applications</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Created</th>
                    <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($candidates as $candidate)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-slate-200 flex-shrink-0 shadow-sm flex items-center justify-center">
                                    <div class="w-full h-full flex items-center justify-center text-sm font-bold text-blue-700">{{ strtoupper(substr($candidate->name, 0, 2)) }}</div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $candidate->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-700 text-sm hidden sm:table-cell">{{ $candidate->email }}</td>
                        <td class="px-4 sm:px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                {{ $candidate->job_applications_count }} {{ Str::plural('Application', $candidate->job_applications_count) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">{{ $candidate->created_at->format('M d, Y') }}</td>
                        <td class="px-4 sm:px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 sm:gap-3">
                                <a href="{{ route('admin.candidates.show', $candidate) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden sm:inline">View</span>
                                </a>
                                <a href="{{ route('admin.candidates.edit', $candidate) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                @if($candidate->job_applications_count === 0)
                                    <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST" class="inline-block delete-form" data-id="{{ $candidate->id }}" data-name="{{ $candidate->name }}">
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
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-slate-500 font-medium text-base mb-2">No candidates found</p>
                                <p class="text-slate-400 text-sm">Candidates will appear here once they submit job applications</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $candidates->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const candidateName = formElement.getAttribute('data-name') || 'this candidate';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${candidateName}". This action cannot be undone!`,
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
                            text: 'Please wait while we delete the candidate.',
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


