@extends('layouts.admin')

@section('title', 'Self Interview Questions')

@section('header-description', 'Manage self interview questions that candidates answer after passing the aptitude test.')

@section('header-actions')
    <a href="{{ route('admin.self-interview.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">Add Question</span>
        <span class="sm:hidden">New</span>
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Job Post</label>
                    <select name="job_post_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">All Job Posts</option>
                        <option value="global" @selected(request('job_post_id') === 'global')>Global Questions Only</option>
                        @foreach($jobPosts as $jobPost)
                            <option value="{{ $jobPost->id }}" @selected(request('job_post_id') == $jobPost->id || request('job_post_id') == (string) $jobPost->id)>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="all">All</option>
                        <option value="1" @selected(request('is_active') === '1')>Active</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['job_post_id', 'is_active']) && request('job_post_id') !== null || (request('is_active') && request('is_active') !== 'all'))
                    <a href="{{ route('admin.self-interview.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Questions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Job Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-700 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($questions as $question)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-900">{{ \Illuminate\Support\Str::limit($question->question, 80) }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
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
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $question->points }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $question->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.self-interview.edit', $question) }}" class="text-teal-600 hover:text-teal-800">Edit</a>
                            <form action="{{ route('admin.self-interview.destroy', $question) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                            <form action="{{ route('admin.self-interview.toggle-status', $question) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 rounded border {{ $question->is_active ? 'border-slate-300 text-slate-700' : 'border-green-300 text-green-700' }}">
                                    {{ $question->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-500 text-sm">
                            No self interview questions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $questions->links() }}
    </div>
@endsection


