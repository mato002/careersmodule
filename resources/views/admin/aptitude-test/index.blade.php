@extends('layouts.admin')

@section('title', 'Aptitude Test Questions')

@section('header-description', 'Manage aptitude test questions for candidate assessments.')

@section('header-actions')
    <div class="hidden" id="bulk-actions-container">
        <div class="inline-flex items-center gap-2 flex-wrap">
            <button type="button" id="bulk-activate-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="hidden sm:inline">Activate (<span id="selected-count-activate">0</span>)</span>
            </button>
            <button type="button" id="bulk-deactivate-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-slate-600 hover:bg-slate-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="hidden sm:inline">Deactivate (<span id="selected-count-deactivate">0</span>)</span>
            </button>
            <button type="button" id="bulk-delete-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span class="hidden sm:inline">Delete (<span id="selected-count-delete">0</span>)</span>
            </button>
        </div>
    </div>
    <a href="{{ route('admin.aptitude-test.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">Add Question</span>
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Section Counts -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        @foreach($sectionCounts as $section => $count)
            @php
                $colors = [
                    'numerical' => 'from-teal-600 to-teal-700',
                    'logical' => 'from-slate-600 to-slate-700',
                    'verbal' => 'from-teal-500 to-teal-600',
                    'scenario' => 'from-slate-500 to-slate-600',
                ];
                $labels = [
                    'numerical' => 'Numerical',
                    'logical' => 'Logical',
                    'verbal' => 'Verbal',
                    'scenario' => 'Scenario',
                ];
            @endphp
            <div class="bg-gradient-to-br {{ $colors[$section] }} rounded-xl p-4 text-white shadow-sm">
                <p class="text-sm opacity-90 mb-1">{{ $labels[$section] }}</p>
                <p class="text-3xl font-bold">{{ $count }}</p>
            </div>
        @endforeach
    </div>

    @php
        $hasActiveFilters = request()->hasAny(['job_post_id', 'section', 'is_active']) && 
                           (request('is_active') !== 'all' || request('job_post_id') || request('section'));
        $activeFilterCount = 0;
        if (request('job_post_id')) $activeFilterCount++;
        if (request('section')) $activeFilterCount++;
        if (request('is_active') && request('is_active') !== 'all') $activeFilterCount++;
    @endphp

    <!-- Filters Section -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 mb-4 sm:mb-6 overflow-hidden">
        <!-- Filter Header -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    @if($hasActiveFilters)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">
                            {{ $activeFilterCount }} Active
                        </span>
                    @endif
                </div>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.aptitude-test.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset All Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.aptitude-test.index') }}" class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Job Post Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Job Post
                        @if(request('job_post_id'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <select name="job_post_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                        <option value="">All Job Posts</option>
                        <option value="global" @selected(request('job_post_id') === 'global')>Global Questions Only</option>
                        @foreach($jobPosts as $jobPost)
                            <option value="{{ $jobPost->id }}" @selected(request('job_post_id') == $jobPost->id || request('job_post_id') == (string)$jobPost->id)>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Section
                        @if(request('section'))
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <select name="section" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                        <option value="">All Sections</option>
                        <option value="numerical" @selected(request('section') === 'numerical')>Numerical</option>
                        <option value="logical" @selected(request('section') === 'logical')>Logical</option>
                        <option value="verbal" @selected(request('section') === 'verbal')>Verbal</option>
                        <option value="scenario" @selected(request('section') === 'scenario')>Scenario</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                        @if(request('is_active') && request('is_active') !== 'all')
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                Active
                            </span>
                        @endif
                    </label>
                    <select name="is_active" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm transition-all">
                        <option value="all">All Statuses</option>
                        <option value="1" @selected(request('is_active') === '1')>Active</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Filter Action Buttons -->
            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply Filters
                </button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.aptitude-test.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear All
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Results Count Banner -->
    @if($hasActiveFilters)
    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-4 sm:p-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-1">
                    {{ $questions->total() }} Question{{ $questions->total() !== 1 ? 's' : '' }} Found
                </h2>
                <p class="text-teal-100 text-sm">Matching your filter criteria</p>
            </div>
            <a href="{{ route('admin.aptitude-test.index') }}" 
               class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition whitespace-nowrap">
                View All Questions
            </a>
        </div>
    </div>
    @endif

    <!-- Active Filters Display -->
    @if($hasActiveFilters)
    <div class="bg-teal-50 border border-teal-200 rounded-xl sm:rounded-2xl p-4 mb-4 sm:mb-6">
        <div class="flex items-center flex-wrap gap-2">
            <span class="text-sm font-semibold text-teal-900">Active Filters:</span>
            @if(request('job_post_id'))
                @if(request('job_post_id') === 'global')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                        <span class="font-medium">Job Post:</span>
                        <span class="font-semibold">Global Questions Only</span>
                        <a href="{{ route('admin.aptitude-test.index', array_merge(request()->except('job_post_id'), ['page' => 1])) }}" 
                           class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                @else
                    @php
                        $selectedJobPost = $jobPosts->firstWhere('id', request('job_post_id'));
                    @endphp
                    @if($selectedJobPost)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                            <span class="font-medium">Job Post:</span>
                            <span class="font-semibold">{{ $selectedJobPost->title }}</span>
                            <a href="{{ route('admin.aptitude-test.index', array_merge(request()->except('job_post_id'), ['page' => 1])) }}" 
                               class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </span>
                    @endif
                @endif
            @endif
            @if(request('section'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">Section:</span>
                    <span class="font-semibold">{{ ucfirst(request('section')) }}</span>
                    <a href="{{ route('admin.aptitude-test.index', array_merge(request()->except('section'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
            @if(request('is_active') && request('is_active') !== 'all')
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-teal-200 rounded-lg text-sm text-teal-800">
                    <span class="font-medium">Status:</span>
                    <span class="font-semibold">{{ request('is_active') === '1' ? 'Active' : 'Inactive' }}</span>
                    <a href="{{ route('admin.aptitude-test.index', array_merge(request()->except('is_active'), ['page' => 1])) }}" 
                       class="ml-1 text-teal-600 hover:text-red-600 transition-colors" title="Remove this filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Questions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Job Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($questions as $question)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" class="question-checkbox w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                                {{ ucfirst($question->section) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-900 max-w-md truncate">
                                {{ Str::limit($question->question, 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($question->jobPost)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-800">
                                    {{ $question->jobPost->title }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-teal-50 text-teal-800 border border-teal-200">
                                    Global
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $question->points }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $question->is_active ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $question->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.aptitude-test.edit', ['aptitude_test' => $question]) }}" class="text-teal-600 hover:text-teal-800">Edit</a>
                                <form action="{{ route('admin.aptitude-test.toggle-status', ['question' => $question]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-slate-600 hover:text-slate-800">
                                        {{ $question->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.aptitude-test.destroy', ['aptitude_test' => $question]) }}" method="POST" class="inline delete-question-form" data-question-id="{{ $question->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No questions found. <a href="{{ route('admin.aptitude-test.create') }}" class="text-teal-600 hover:text-teal-700">Create your first question</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $questions->links() }}
    </div>

    <!-- Bulk Actions Forms -->
    <form id="bulk-activate-form" action="{{ route('admin.aptitude-test.bulk-activate') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="question_ids" id="bulk-activate-ids">
    </form>

    <form id="bulk-deactivate-form" action="{{ route('admin.aptitude-test.bulk-deactivate') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="question_ids" id="bulk-deactivate-ids">
    </form>

    <form id="bulk-delete-form" action="{{ route('admin.aptitude-test.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="question_ids" id="bulk-delete-ids">
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All Checkbox
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.question-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }

            // Individual Checkboxes
            document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkActions();
                    // Update select-all state
                    const allCheckboxes = document.querySelectorAll('.question-checkbox');
                    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
                    const allChecked = checkedBoxes.length === allCheckboxes.length && allCheckboxes.length > 0;
                    const selectAllCheckbox = document.getElementById('select-all');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && checkedBoxes.length > 0;
                    }
                });
            });

            function updateBulkActions() {
                const checked = document.querySelectorAll('.question-checkbox:checked');
                const count = checked.length;
                const container = document.getElementById('bulk-actions-container');
                
                if (count > 0) {
                    if (container) {
                        container.classList.remove('hidden');
                    }
                    const activateCount = document.getElementById('selected-count-activate');
                    const deactivateCount = document.getElementById('selected-count-deactivate');
                    const deleteCount = document.getElementById('selected-count-delete');
                    if (activateCount) activateCount.textContent = count;
                    if (deactivateCount) deactivateCount.textContent = count;
                    if (deleteCount) deleteCount.textContent = count;
                } else {
                    if (container) {
                        container.classList.add('hidden');
                    }
                }
            }

            // Bulk Activate
            const bulkActivateBtn = document.getElementById('bulk-activate-btn');
            if (bulkActivateBtn) {
                bulkActivateBtn.addEventListener('click', function() {
                    const checked = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
                    if (checked.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one question to activate.'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Activating...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    const form = document.getElementById('bulk-activate-form');
                    const input = document.getElementById('bulk-activate-ids');
                    if (form && input) {
                        input.value = JSON.stringify(checked);
                        form.submit();
                    }
                });
            }

            // Bulk Deactivate
            const bulkDeactivateBtn = document.getElementById('bulk-deactivate-btn');
            if (bulkDeactivateBtn) {
                bulkDeactivateBtn.addEventListener('click', function() {
                    const checked = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
                    if (checked.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one question to deactivate.'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to deactivate ${checked.length} question(s).`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0f766e',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, deactivate them!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deactivating...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            const form = document.getElementById('bulk-deactivate-form');
                            const input = document.getElementById('bulk-deactivate-ids');
                            if (form && input) {
                                input.value = JSON.stringify(checked);
                                form.submit();
                            }
                        }
                    });
                });
            }

            // Bulk Delete
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const checked = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
                    if (checked.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one question to delete.'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${checked.length} question(s). This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleting...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            const form = document.getElementById('bulk-delete-form');
                            const input = document.getElementById('bulk-delete-ids');
                            if (form && input) {
                                input.value = JSON.stringify(checked);
                                form.submit();
                            }
                        }
                    });
                });
            }
            
            // Handle individual delete forms with SweetAlert
            document.querySelectorAll('.delete-question-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formElement = this;
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to delete this question. This action cannot be undone!',
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
                                allowOutsideClick: false,
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
@endsection

