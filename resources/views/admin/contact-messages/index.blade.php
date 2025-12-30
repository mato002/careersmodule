@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('header-description', 'View and respond to inquiries submitted through the website.')

@section('header-actions')
    <div class="hidden" id="bulk-actions-container">
        <div class="inline-flex items-center gap-2 flex-wrap">
            <button type="button" id="bulk-status-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="hidden sm:inline">Change Status (<span id="selected-count-2">0</span>)</span>
            </button>
            <button type="button" id="bulk-delete-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span class="hidden sm:inline">Delete (<span id="selected-count-3">0</span>)</span>
            </button>
        </div>
    </div>
    <a href="{{ route('admin.contact-messages.export', request()->query()) }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-green-600 hover:bg-green-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span class="hidden sm:inline">Export CSV</span>
    </a>
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        <span class="hidden sm:inline">Refresh</span>
    </a>
@endsection

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <!-- Total Messages Banner -->
    @if(request()->hasAny(['status', 'search', 'start_date', 'end_date']))
        <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                        {{ $filteredMessagesCount }} Message{{ $filteredMessagesCount !== 1 ? 's' : '' }} Found
                    </h2>
                    <p class="text-teal-100 text-sm">Filtered from {{ $totalMessagesCount }} total messages</p>
                </div>
                <a href="{{ route('admin.contact-messages.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                    Clear Filters
                </a>
            </div>
        </div>
    @else
        <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                    {{ $totalMessagesCount }} Total Message{{ $totalMessagesCount !== 1 ? 's' : '' }}
                </h2>
                <p class="text-teal-100 text-sm">All contact messages in the system</p>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, subject, or message..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statusCounts as $key => $count)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ Str::headline($key) }} ({{ $count }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('admin.contact-messages.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Status Filter Pills -->
    <div class="mb-4 flex items-center gap-3 text-sm flex-wrap">
        <a href="{{ route('admin.contact-messages.index', request()->except('status')) }}" class="px-3 py-1 rounded-full border {{ !request('status') ? 'border-teal-600 text-teal-700 bg-teal-50' : 'border-gray-200 text-gray-500' }}">
            All ({{ array_sum($statusCounts) }})
        </a>
        @foreach ($statusCounts as $key => $count)
            <a href="{{ route('admin.contact-messages.index', array_merge(request()->except('status'), ['status' => $key])) }}" class="px-3 py-1 rounded-full border {{ request('status') === $key ? 'border-teal-600 text-teal-700 bg-teal-50' : 'border-gray-200 text-gray-500' }}">
                {{ Str::headline($key) }} ({{ $count }})
            </a>
        @endforeach
    </div>

    <!-- Hidden forms for bulk actions -->
    <form id="bulk-status-form" method="POST" action="{{ route('admin.contact-messages.bulk-update-status') }}" style="display: none;">
        @csrf
        <div id="bulk-status-inputs"></div>
        <input type="hidden" name="status" id="bulk-status-value">
    </form>
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.contact-messages.bulk-delete') }}" style="display: none;">
        @csrf
        <div id="bulk-delete-inputs"></div>
    </form>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3 w-12">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-3 sm:px-6 py-3">Name</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Email</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Subject</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Date</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $message)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <input type="checkbox" data-message-id="{{ $message->id }}" class="message-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-3 sm:px-6 py-4 font-semibold text-gray-900 text-sm sm:text-base">{{ $message->name }}</td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell truncate max-w-[200px]">{{ $message->email }}</td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell truncate max-w-[200px]">{{ $message->subject ?? '—' }}</td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-gray-200 text-gray-700' => $message->status === 'new',
                                    'bg-amber-100 text-amber-800' => $message->status === 'in_progress',
                                    'bg-green-100 text-green-800' => $message->status === 'handled',
                                ])">
                                {{ Str::headline($message->status) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $message->created_at->format('M d, Y g:i A') }}</td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-teal-700 font-semibold text-xs sm:text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $messages->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bulk selection functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const messageCheckboxes = document.querySelectorAll('.message-checkbox');
        const bulkActionsContainer = document.getElementById('bulk-actions-container');
        const selectedCountSpans = document.querySelectorAll('#selected-count-2, #selected-count-3');
        const bulkStatusForm = document.getElementById('bulk-status-form');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkActionsButton() {
            const selectedCount = document.querySelectorAll('.message-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkActionsContainer.classList.remove('hidden');
                selectedCountSpans.forEach(span => span.textContent = selectedCount);
            } else {
                bulkActionsContainer.classList.add('hidden');
            }
        }

        function getSelectedMessageIds() {
            const checkedBoxes = document.querySelectorAll('.message-checkbox:checked');
            return Array.from(checkedBoxes).map(cb => cb.getAttribute('data-message-id'));
        }

        // Select all checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                messageCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsButton();
            });
        }

        // Individual checkboxes
        messageCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(messageCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(messageCheckboxes).some(cb => cb.checked);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
                updateBulkActionsButton();
            });
        });

        // Bulk status change button
        const bulkStatusBtn = document.getElementById('bulk-status-btn');
        if (bulkStatusBtn && bulkStatusForm) {
            bulkStatusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedMessageIds();
                const selectedCount = selectedIds.length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one message.' });
                    return;
                }

                Swal.fire({
                    title: 'Change Status',
                    html: `
                        <p class="mb-4">Change status for <strong>${selectedCount}</strong> message(s) to:</p>
                        <select id="status-select" class="swal2-input">
                            <option value="new">New</option>
                            <option value="in_progress">In Progress</option>
                            <option value="handled">Handled</option>
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#14b8a6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Update Status',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const status = document.getElementById('status-select').value;
                        if (!status) {
                            Swal.showValidationMessage('Please select a status');
                            return false;
                        }
                        return status;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        Swal.fire({ title: 'Updating...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                        
                        // Populate form
                        const inputsDiv = document.getElementById('bulk-status-inputs');
                        inputsDiv.innerHTML = '';
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'message_ids[]';
                            input.value = id;
                            inputsDiv.appendChild(input);
                        });
                        document.getElementById('bulk-status-value').value = result.value;
                        
                        bulkStatusForm.submit();
                    }
                });
            });
        }

        // Bulk delete button
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        if (bulkDeleteBtn && bulkDeleteForm) {
            bulkDeleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedMessageIds();
                const selectedCount = selectedIds.length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one message.' });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    html: `Delete <strong>${selectedCount}</strong> message(s)?<br><br><span class="text-red-600">This action cannot be undone!</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                        
                        // Populate form
                        const inputsDiv = document.getElementById('bulk-delete-inputs');
                        inputsDiv.innerHTML = '';
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'message_ids[]';
                            input.value = id;
                            inputsDiv.appendChild(input);
                        });
                        
                        bulkDeleteForm.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
