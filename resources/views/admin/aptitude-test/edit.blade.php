@extends('layouts.admin')

@section('title', 'Edit Aptitude Test Question')

@section('header-actions')
    <a href="{{ route('admin.aptitude-test.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Questions
    </a>
@endsection

@section('content')
    @if(!$question)
        <div class="max-w-4xl mx-auto bg-red-50 border border-red-200 rounded-2xl p-6">
            <p class="text-red-800">Error: Question not found.</p>
            <a href="{{ route('admin.aptitude-test.index') }}" class="mt-4 inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Back to Questions
            </a>
        </div>
    @else
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        {{-- Use explicit URL to avoid route parameter naming issues --}}
        <form method="POST" action="{{ url('admin/aptitude-test/'.$question->id) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Post (Optional)</label>
                    <select name="job_post_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        <option value="">Global (Available for all jobs)</option>
                        @foreach(\App\Models\JobPost::orderBy('title')->get() as $jobPost)
                            <option value="{{ $jobPost->id }}" @selected(old('job_post_id', $question->job_post_id) == $jobPost->id)>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Leave empty to make this question available for all job posts, or select a specific job post.</p>
                    @error('job_post_id')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                    <select name="section" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        <option value="">Select Section</option>
                        <option value="numerical" @selected(old('section', $question->section) === 'numerical')>Numerical & Analytical</option>
                        <option value="logical" @selected(old('section', $question->section) === 'logical')>Logical Reasoning</option>
                        <option value="verbal" @selected(old('section', $question->section) === 'verbal')>Verbal & Comprehension</option>
                        <option value="scenario" @selected(old('section', $question->section) === 'scenario')>Job-Fit Scenarios</option>
                    </select>
                    @error('section')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                    <textarea name="question" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('question', $question->question) }}</textarea>
                    @error('question')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Answer Options *</label>
                    <div class="space-y-3">
                        @php
                            $oldOptions = old('options', []);
                            $questionOptions = $question->options ?? [];
                            $letters = ['a', 'b', 'c', 'd'];
                        @endphp
                        @foreach($letters as $index => $letter)
                            @php
                                // Handle both old() array format [0,1,2,3] and question options format ['a' => 'val', 'b' => 'val']
                                $value = '';
                                if (!empty($oldOptions)) {
                                    // After validation error, old('options') is in array format [0 => 'val1', 1 => 'val2']
                                    $value = $oldOptions[$index] ?? '';
                                } else {
                                    // Initial load, use question options which are in ['a' => 'val', 'b' => 'val'] format
                                    $value = $questionOptions[$letter] ?? '';
                                }
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-sm font-semibold text-gray-600">{{ strtoupper($letter) }}.</span>
                                <input type="text" name="options[]" value="{{ $value }}" required
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600"
                                       placeholder="Option {{ strtoupper($letter) }}">
                            </div>
                        @endforeach
                    </div>
                    @error('options')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                    <select name="correct_answer" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        <option value="">Select Correct Answer</option>
                        <option value="a" @selected(old('correct_answer', $question->correct_answer) === 'a')>A</option>
                        <option value="b" @selected(old('correct_answer', $question->correct_answer) === 'b')>B</option>
                        <option value="c" @selected(old('correct_answer', $question->correct_answer) === 'c')>C</option>
                        <option value="d" @selected(old('correct_answer', $question->correct_answer) === 'd')>D</option>
                    </select>
                    @error('correct_answer')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Points *</label>
                        <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" max="10" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        @error('points')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', $question->display_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                    <textarea name="explanation" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('explanation', $question->explanation) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">This will be shown to candidates who answer incorrectly.</p>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Active (question will appear in tests)</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold">
                        Update Question
                    </button>
                    <a href="{{ route('admin.aptitude-test.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
    @endif
@endsection

