@extends('layouts.admin')

@section('title', $teamMember->name)

@section('header-description', 'View team member details.')

@section('header-actions')
    <a href="{{ route('admin.team-members.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Team
    </a>
    <a href="{{ route('admin.team-members.edit', $teamMember) }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
        Edit Member
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start gap-6 mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 border-4 border-gray-200">
                            @if ($teamMember->photo_path)
                                <img src="{{ asset('storage/'.$teamMember->photo_path) }}" alt="{{ $teamMember->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-400">
                                    {{ strtoupper(substr($teamMember->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $teamMember->name }}</h2>
                        @if($teamMember->role)
                            <p class="text-xl text-gray-600 mb-4">{{ $teamMember->role }}</p>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $teamMember->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $teamMember->is_active ? 'Active' : 'Hidden' }}
                            </span>
                            <span class="text-sm text-gray-500">
                                Display Order: {{ $teamMember->display_order }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        @if($teamMember->email)
                            <div>
                                <dt class="text-gray-500 mb-1">Email</dt>
                                <dd class="text-gray-900 font-medium">
                                    <a href="mailto:{{ $teamMember->email }}" class="text-teal-700 hover:text-teal-800">
                                        {{ $teamMember->email }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($teamMember->phone)
                            <div>
                                <dt class="text-gray-500 mb-1">Phone</dt>
                                <dd class="text-gray-900 font-medium">
                                    <a href="tel:{{ $teamMember->phone }}" class="text-teal-700 hover:text-teal-800">
                                        {{ $teamMember->phone }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($teamMember->linkedin_url)
                            <div class="sm:col-span-2">
                                <dt class="text-gray-500 mb-1">LinkedIn</dt>
                                <dd class="text-gray-900 font-medium">
                                    <a href="{{ $teamMember->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-teal-700 hover:text-teal-800 inline-flex items-center gap-1">
                                        {{ $teamMember->linkedin_url }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if($teamMember->bio)
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Biography</h3>
                        <div class="prose max-w-none text-gray-700">
                            <p class="whitespace-pre-line">{{ $teamMember->bio }}</p>
                        </div>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-6 mt-6">
                    <p class="text-sm text-gray-600">
                        <strong>Created:</strong> {{ $teamMember->created_at->format('M d, Y') }} |
                        <strong>Last Updated:</strong> {{ $teamMember->updated_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.team-members.edit', $teamMember) }}" class="block w-full px-4 py-2 bg-teal-50 text-teal-800 rounded-lg hover:bg-teal-100 text-center font-semibold transition">
                        Edit Member
                    </a>
                    <form action="{{ route('admin.team-members.destroy', $teamMember) }}" method="POST" class="delete-form" data-id="{{ $teamMember->id }}" data-name="{{ $teamMember->name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-800 rounded-lg hover:bg-red-100 text-center font-semibold transition">
                            Delete Member
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Information</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $teamMember->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $teamMember->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Display Order</dt>
                        <dd class="text-gray-900 font-medium">{{ $teamMember->display_order }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Created</dt>
                        <dd class="text-gray-900">{{ $teamMember->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Last Updated</dt>
                        <dd class="text-gray-900">{{ $teamMember->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const memberName = formElement.getAttribute('data-name') || 'this member';
                
                Swal.fire({
                    title: 'Are you sure?',
                    html: `<p>You are about to delete <strong>${memberName}</strong>.</p><p class="mt-2 text-sm text-gray-600">This will permanently delete:</p><ul class="text-sm text-left mt-2 ml-4 list-disc"><li>The team member record</li><li>The photo (if any)</li></ul><p class="mt-3 text-red-600 font-semibold">This action cannot be undone!</p>`,
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
                            text: 'Please wait while we delete the team member.',
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






