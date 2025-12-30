@extends('layouts.admin')

@section('title', $job->title)

@section('header-description', 'View job post details and applications.')

@section('header-actions')
    <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Jobs
    </a>
    <a href="{{ route('admin.jobs.edit', $job) }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
        Edit Job
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $job->title }}</h2>
                
                <div class="flex flex-wrap gap-4 mb-6">
                    @if($job->department)
                        <span class="text-sm text-gray-600">
                            <strong>Department:</strong> {{ $job->department }}
                        </span>
                    @endif
                    @if($job->location)
                        <span class="text-sm text-gray-600">
                            <strong>Location:</strong> {{ $job->location }}
                        </span>
                    @endif
                    <span class="text-sm text-gray-600">
                        <strong>Type:</strong> {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $job->status_badge_classes }}">
                        {{ $job->status_label }}
                    </span>
                    @if($job->application_deadline)
                        <span class="text-sm text-gray-600">
                            <strong>Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}
                            @if($job->application_deadline->isPast())
                                <span class="text-red-600">(Passed)</span>
                            @else
                                <span class="text-green-600">({{ $job->application_deadline->diffForHumans() }})</span>
                            @endif
                        </span>
                    @endif
                </div>

                <div class="prose max-w-none mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $job->description }}</p>

                    @if($job->responsibilities)
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-6">Key Responsibilities</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $job->responsibilities }}</p>
                    @endif

                    @if($job->requirements)
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-6">Requirements</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $job->requirements }}</p>
                    @endif
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        <strong>Views:</strong> {{ $job->views }} | 
                        <strong>Applications:</strong> {{ $job->applications_count ?? 0 }} |
                        <strong>Created:</strong> {{ $job->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('careers.show', $job->slug) }}" target="_blank" class="block w-full px-4 py-2 bg-teal-50 text-teal-800 rounded-lg hover:bg-teal-100 text-center font-semibold">
                        View on Site
                    </a>
                    <a href="{{ route('admin.job-applications.index', ['job_post_id' => $job->id]) }}" class="block w-full px-4 py-2 bg-blue-50 text-blue-800 rounded-lg hover:bg-blue-100 text-center font-semibold">
                        View Applications
                    </a>
                    <form action="{{ route('admin.jobs.toggle-status', $job) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="w-full px-4 py-2 rounded-lg text-center font-semibold transition-colors {{ $job->is_active ? 'bg-orange-50 text-orange-800 hover:bg-orange-100' : 'bg-green-50 text-green-800 hover:bg-green-100' }}">
                            {{ $job->is_active ? 'Deactivate Job' : 'Activate Job' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

