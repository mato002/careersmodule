@extends('layouts.admin')

@section('title', 'Create Self Interview Question')

@section('header-actions')
    <a href="{{ route('admin.self-interview.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Questions
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.self-interview.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Post (Optional)</label>
                    <select name="job_post_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        <option value="">Global (Available for all jobs)</option>
                        @foreach($jobPosts as $jobPost)
                            <option value="{{ $jobPost->id }}" @selected(old('job_post_id') == $jobPost->id)>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Leave empty to make this question available for all job posts, or select a specific job post.</p>
                    @error('job_post_id')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                    <textarea name="question" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('question') }}</textarea>
                    @error('question')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Answer Options (Optional)</label>
                    <p class="text-xs text-gray-500 mb-2">If you provide options, the question will be auto‑marked like the aptitude test. Leave all blank to treat this as an open‑ended question.</p>
                    <div class="space-y-3">
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-sm font-semibold text-gray-600">{{ chr(65 + $i) }}.</span>
                                <input type="text" name="options[]" value="{{ old("options.$i") }}"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600"
                                       placeholder="Option {{ chr(65 + $i) }}">
                            </div>
                        @endfor
                    </div>
                    @error('options')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer (Optional)</label>
                    <p class="text-xs text-gray-500 mb-1">Only needed when you provide multiple‑choice options and want auto‑marking.</p>
                    <select name="correct_answer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        <option value="">No automatic marking</option>
                        <option value="a" @selected(old('correct_answer') === 'a')>A</option>
                        <option value="b" @selected(old('correct_answer') === 'b')>B</option>
                        <option value="c" @selected(old('correct_answer') === 'c')>C</option>
                        <option value="d" @selected(old('correct_answer') === 'd')>D</option>
                        <option value="e" @selected(old('correct_answer') === 'e')>E</option>
                    </select>
                    @error('correct_answer')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Points *</label>
                        <input type="number" name="points" value="{{ old('points', 4) }}" min="1" max="10" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                        @error('points')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', 0) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes / Guidance (Optional)</label>
                    <textarea name="explanation" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('explanation') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Shown in the results to help interpret answers.</p>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Active (question will appear in self interviews)</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold">
                        Create Question
                    </button>
                    <a href="{{ route('admin.self-interview.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection


