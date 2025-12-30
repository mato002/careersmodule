@extends('layouts.admin')

@section('title', 'CEO Messages')
@section('header-description', 'Manage CEO/Director messages displayed on the website.')

@section('header-actions')
    <a href="{{ route('admin.ceo-messages.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden sm:inline">Add Message</span>
        <span class="sm:hidden">Add</span>
    </a>
@endsection

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <!-- Total Messages Banner -->
    @if(request()->hasAny(['search', 'is_active']))
        <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                        {{ $filteredCeoMessagesCount }} Message{{ $filteredCeoMessagesCount !== 1 ? 's' : '' }} Found
                    </h2>
                    <p class="text-teal-100 text-sm">Filtered from {{ $totalCeoMessagesCount }} total messages</p>
                </div>
                <a href="{{ route('admin.ceo-messages.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                    Clear Filters
                </a>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.ceo-messages.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, position, title, or message..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="all">All Statuses</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'is_active']))
                    <a href="{{ route('admin.ceo-messages.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3">Name</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Position</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Title</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($ceoMessages as $message)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                @if($message->image_path)
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                        <img src="{{ asset('storage/'.$message->image_path) }}" alt="{{ $message->name }}" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs text-gray-500">{{ strtoupper(substr($message->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $message->name }}</p>
                                    <p class="text-xs text-gray-400">Updated {{ $message->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $message->position ?? '—' }}</td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell truncate max-w-[200px]">{{ \Illuminate\Support\Str::limit($message->title ?? '—', 40) }}</td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $message->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $message->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <div class="flex justify-end gap-1.5 sm:gap-2">
                                <a href="{{ route('admin.ceo-messages.edit', $message) }}" class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded-lg">Edit</a>
                                <form method="POST" action="{{ route('admin.ceo-messages.destroy', $message) }}" class="inline delete-form" data-id="{{ $message->id }}" data-name="{{ $message->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p>No messages yet. <a href="{{ route('admin.ceo-messages.create') }}" class="text-blue-600 hover:underline">Create your first message</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($ceoMessages->hasPages())
        <div class="mt-4 sm:mt-6">
            {{ $ceoMessages->links() }}
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const messageName = formElement.getAttribute('data-name') || 'this message';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete the message from "${messageName}". This action cannot be undone!`,
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
                            text: 'Please wait while we delete the message.',
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

