@extends('layouts.admin')

@section('title', 'Create Appraisal')

@section('header-description', 'Create a new appraisal, communication, or warning')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Create Appraisal</h1>
                <p class="text-sm text-slate-500 mt-1">Candidate: {{ $candidate->name }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.candidates.appraisals', $candidate) }}" 
                   class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Back to Appraisals
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.candidates.appraisals.store', $candidate) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
            @csrf

            <!-- Type Selection -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <select id="type" name="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="toggleFields()">
                    <option value="">Select Type...</option>
                    <option value="performance_review" {{ old('type') === 'performance_review' ? 'selected' : '' }}>Performance Review</option>
                    <option value="hr_communication" {{ old('type') === 'hr_communication' ? 'selected' : '' }}>HR Communication</option>
                    <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       placeholder="e.g., Q1 2024 Performance Review"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="6" required
                          placeholder="Enter the appraisal content..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Performance Review Fields -->
            <div id="performance_review_fields" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating (1-10)</label>
                        <input type="number" id="rating" name="rating" value="{{ old('rating') }}" 
                               min="1" max="10" placeholder="e.g., 8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="strengths" class="block text-sm font-medium text-gray-700 mb-2">Strengths</label>
                    <textarea id="strengths" name="strengths" rows="3"
                              placeholder="List the candidate's strengths..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('strengths') }}</textarea>
                    @error('strengths')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="areas_for_improvement" class="block text-sm font-medium text-gray-700 mb-2">Areas for Improvement</label>
                    <textarea id="areas_for_improvement" name="areas_for_improvement" rows="3"
                              placeholder="List areas that need improvement..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('areas_for_improvement') }}</textarea>
                    @error('areas_for_improvement')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="goals" class="block text-sm font-medium text-gray-700 mb-2">Goals</label>
                    <textarea id="goals" name="goals" rows="3"
                              placeholder="Set goals for the candidate..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('goals') }}</textarea>
                    @error('goals')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Warning Fields -->
            <div id="warning_fields" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="warning_level" class="block text-sm font-medium text-gray-700 mb-2">Warning Level</label>
                        <select id="warning_level" name="warning_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select...</option>
                            <option value="verbal" {{ old('warning_level') === 'verbal' ? 'selected' : '' }}>Verbal Warning</option>
                            <option value="written" {{ old('warning_level') === 'written' ? 'selected' : '' }}>Written Warning</option>
                            <option value="final" {{ old('warning_level') === 'final' ? 'selected' : '' }}>Final Warning</option>
                        </select>
                        @error('warning_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="warning_date" class="block text-sm font-medium text-gray-700 mb-2">Warning Date</label>
                        <input type="date" id="warning_date" name="warning_date" value="{{ old('warning_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('warning_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Attachments -->
            <div>
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
                <input type="file" id="attachments" name="attachments[]" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 5MB each)</p>
                @error('attachments.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.candidates.appraisals', $candidate) }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Create Appraisal
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            const performanceFields = document.getElementById('performance_review_fields');
            const warningFields = document.getElementById('warning_fields');

            if (type === 'performance_review') {
                performanceFields.style.display = 'block';
                warningFields.style.display = 'none';
            } else if (type === 'warning') {
                performanceFields.style.display = 'none';
                warningFields.style.display = 'block';
            } else {
                performanceFields.style.display = 'none';
                warningFields.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });
    </script>
@endsection
