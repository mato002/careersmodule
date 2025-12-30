@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Job Applications')

@section('header-description', 'Review and manage job applications.')

@section('header-actions')
    <button class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap"
        onclick="window.location.reload()">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        <span class="hidden sm:inline">Refresh</span>
    </button>
    <div class="hidden" id="bulk-actions-container">
        <div class="inline-flex items-center gap-2 flex-wrap">
            <button type="button" id="bulk-create-accounts-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="hidden sm:inline">Create Accounts (<span id="selected-count-4">0</span>)</span>
            </button>
            <button type="button" id="bulk-send-email-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="hidden sm:inline">Send Emails (<span id="selected-count">0</span>)</span>
            </button>
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
    <a href="{{ route('admin.job-applications.export', request()->query()) }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-green-600 hover:bg-green-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span class="hidden sm:inline">Export CSV</span>
    </a>
    <a href="{{ route('admin.job-applications.calendar') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="hidden sm:inline">Interview Calendar</span>
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
            @if (session('show_bulk_credentials') && session('bulk_credentials'))
                <div class="mt-4 p-4 bg-white rounded-lg border border-emerald-300">
                    <p class="font-semibold text-emerald-900 mb-3">🔑 Generated Login Credentials:</p>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach(session('bulk_credentials') as $cred)
                            <div class="p-2 bg-gray-50 rounded border border-gray-200">
                                <p class="text-xs font-semibold text-gray-700">{{ $cred['name'] }}</p>
                                <p class="text-xs"><strong>Email:</strong> <code class="bg-white px-1 py-0.5 rounded">{{ $cred['email'] }}</code></p>
                                <p class="text-xs"><strong>Password:</strong> <code class="bg-white px-1 py-0.5 rounded font-mono">{{ $cred['password'] }}</code></p>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-emerald-700 mt-3">⚠️ Save these credentials now - they won't be shown again for security reasons.</p>
                </div>
            @endif
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Total Applications Banner -->
    <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                    @if(request()->hasAny(['status', 'search', 'job_post_id']))
                        {{ $filteredCount }} Application{{ $filteredCount !== 1 ? 's' : '' }} Found
                    @else
                        {{ $totalApplications }} Total Application{{ $totalApplications !== 1 ? 's' : '' }}
                    @endif
                </h2>
                @if(request()->hasAny(['status', 'search', 'job_post_id']))
                    <p class="text-teal-100 text-sm">Filtered from {{ $totalApplications }} total applications</p>
                @else
                    <p class="text-teal-100 text-sm">All job applications in the system</p>
                @endif
            </div>
            @if(request()->hasAny(['status', 'search', 'job_post_id']))
                <a href="{{ route('admin.job-applications.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                    Clear Filters
                </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.job-applications.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, or job title..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statusCounts as $status => $count)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ Str::headline(str_replace('_', ' ', $status)) }} ({{ $count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Post</label>
                    <select name="job_post_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Job Posts</option>
                        @foreach($jobPosts as $jobPost)
                            <option value="{{ $jobPost->id }}" {{ request('job_post_id') == $jobPost->id ? 'selected' : '' }}>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['status', 'search', 'job_post_id']))
                    <a href="{{ route('admin.job-applications.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Hidden forms for bulk actions -->
    <form id="bulk-create-accounts-form" method="POST" action="{{ route('admin.job-applications.bulk-create-candidate-accounts') }}" style="display: none;">
        @csrf
        <input type="hidden" name="selected_applications" id="bulk-create-accounts-ids">
    </form>
    <form id="bulk-email-form" method="POST" action="{{ route('admin.job-applications.bulk-send-confirmation') }}" style="display: none;">
        @csrf
        <div id="bulk-email-inputs"></div>
    </form>
    <form id="bulk-status-form" method="POST" action="{{ route('admin.job-applications.bulk-update-status') }}" style="display: none;">
        @csrf
        <div id="bulk-status-inputs"></div>
        <input type="hidden" name="status" id="bulk-status-value">
    </form>
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.job-applications.bulk-delete') }}" style="display: none;">
        @csrf
        <div id="bulk-delete-inputs"></div>
    </form>

    <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3 w-12">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-3 sm:px-6 py-3">Applicant</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Job Position</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Contact</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Applied</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($applications as $application)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <input type="checkbox" data-application-id="{{ $application->id }}" data-has-account="{{ $application->candidate_id ? 'true' : 'false' }}" class="application-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                            <td class="px-3 sm:px-6 py-4">
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $application->name }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[150px] sm:max-w-none">{{ $application->email }}</p>
                            </td>
                        <td class="px-3 sm:px-6 py-4 hidden sm:table-cell">
                            <p class="text-gray-900 text-xs sm:text-sm truncate max-w-[200px]">{{ optional($application->jobPost)->title ?? 'Unknown Position' }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell">{{ $application->phone }}</td>
                        <td class="px-3 sm:px-6 py-4">
                            @php
                                $statusClasses = match($application->status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'sieving_passed' => 'bg-green-100 text-green-800',
                                    'sieving_rejected' => 'bg-red-100 text-red-800',
                                    'pending_manual_review' => 'bg-yellow-100 text-yellow-800',
                                    'stage_2_passed' => 'bg-emerald-100 text-emerald-800',
                                    'reviewed' => 'bg-blue-100 text-blue-800',
                                    'shortlisted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'interview_scheduled' => 'bg-purple-100 text-purple-800',
                                    'interview_passed' => 'bg-emerald-100 text-emerald-800',
                                    'interview_failed' => 'bg-red-100 text-red-800',
                                    'second_interview' => 'bg-indigo-100 text-indigo-800',
                                    'written_test' => 'bg-cyan-100 text-cyan-800',
                                    'case_study' => 'bg-violet-100 text-violet-800',
                                    'hired' => 'bg-teal-100 text-teal-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{ Str::headline(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $application->created_at->diffForHumans() }}</td>
                        <td class="px-3 sm:px-6 py-4 text-right space-x-2 sm:space-x-3">
                            <a href="{{ route('admin.job-applications.show', $application) }}" class="text-blue-600 font-semibold text-xs sm:text-sm">View</a>
                            <form action="{{ route('admin.job-applications.destroy', $application) }}" method="POST" class="inline-block delete-form" data-id="{{ $application->id }}" data-name="{{ $application->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-semibold text-xs sm:text-sm hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No applications yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $applications->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete forms with SweetAlert
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                const applicantName = formElement.getAttribute('data-name') || 'this application';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete the application from ${applicantName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the application.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        formElement.submit();
                    }
                });
            });
        });

        // Bulk selection functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
        const bulkActionsContainer = document.getElementById('bulk-actions-container');
        const selectedCountSpans = document.querySelectorAll('#selected-count, #selected-count-2, #selected-count-3');
        const bulkEmailForm = document.getElementById('bulk-email-form');
        const bulkStatusForm = document.getElementById('bulk-status-form');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkActionsButton() {
            const selectedCount = document.querySelectorAll('.application-checkbox:checked').length;
            const selectedCountWithoutAccount = document.querySelectorAll('.application-checkbox:checked[data-has-account="false"]').length;
            if (selectedCount > 0) {
                bulkActionsContainer.classList.remove('hidden');
                selectedCountSpans.forEach(span => span.textContent = selectedCount);
                const count4Span = document.getElementById('selected-count-4');
                if (count4Span) count4Span.textContent = selectedCountWithoutAccount;
            } else {
                bulkActionsContainer.classList.add('hidden');
            }
        }

        function getSelectedApplicationIds() {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            return Array.from(checkedBoxes).map(cb => cb.getAttribute('data-application-id'));
        }

        // Select all checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                applicationCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsButton();
            });
        }

        // Individual checkboxes
        applicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update select all checkbox state
                const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(applicationCheckboxes).some(cb => cb.checked);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
                updateBulkActionsButton();
            });
        });

        // Bulk create accounts button
        const bulkCreateAccountsBtn = document.getElementById('bulk-create-accounts-btn');
        const bulkCreateAccountsForm = document.getElementById('bulk-create-accounts-form');
        if (bulkCreateAccountsBtn && bulkCreateAccountsForm) {
            bulkCreateAccountsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedApplicationIds();
                const selectedCount = selectedIds.length;
                const selectedCountWithoutAccount = document.querySelectorAll('.application-checkbox:checked[data-has-account="false"]').length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one application.' });
                    return;
                }
                
                if (selectedCountWithoutAccount === 0) {
                    Swal.fire({ icon: 'info', title: 'All Selected Have Accounts', text: 'All selected applications already have candidate accounts.' });
                    return;
                }
                
                Swal.fire({
                    title: 'Create Candidate Accounts?',
                    html: `Create candidate accounts for <strong>${selectedCountWithoutAccount}</strong> application(s)?<br><br>Login credentials will be sent via email.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#14b8a6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Create Accounts',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Creating...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                        document.getElementById('bulk-create-accounts-ids').value = JSON.stringify(selectedIds);
                        bulkCreateAccountsForm.submit();
                    }
                });
            });
        }
        
        // Bulk send email button
        const bulkSendEmailBtn = document.getElementById('bulk-send-email-btn');
        if (bulkSendEmailBtn && bulkEmailForm) {
            bulkSendEmailBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedApplicationIds();
                const selectedCount = selectedIds.length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one application.' });
                    return;
                }

                Swal.fire({
                    title: 'Send Confirmation Emails?',
                    html: `Send confirmation emails to <strong>${selectedCount}</strong> application(s)?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, send emails!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Sending...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                        
                        // Populate form
                        const inputsDiv = document.getElementById('bulk-email-inputs');
                        inputsDiv.innerHTML = '';
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'application_ids[]';
                            input.value = id;
                            inputsDiv.appendChild(input);
                        });
                        
                        bulkEmailForm.submit();
                    }
                });
            });
        }

        // Bulk status change button
        const bulkStatusBtn = document.getElementById('bulk-status-btn');
        if (bulkStatusBtn && bulkStatusForm) {
            bulkStatusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedApplicationIds();
                const selectedCount = selectedIds.length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one application.' });
                    return;
                }

                Swal.fire({
                    title: 'Change Status',
                    html: `
                        <p class="mb-4">Change status for <strong>${selectedCount}</strong> application(s) to:</p>
                        <select id="status-select" class="swal2-input">
                            <option value="pending">Pending</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="rejected">Rejected</option>
                            <option value="interview_scheduled">Interview Scheduled</option>
                            <option value="interview_passed">Interview Passed</option>
                            <option value="interview_failed">Interview Failed</option>
                            <option value="hired">Hired</option>
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
                            input.name = 'application_ids[]';
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
                const selectedIds = getSelectedApplicationIds();
                const selectedCount = selectedIds.length;
                
                if (selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one application.' });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    html: `Delete <strong>${selectedCount}</strong> application(s)?<br><br><span class="text-red-600">This action cannot be undone!</span>`,
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
                            input.name = 'application_ids[]';
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


