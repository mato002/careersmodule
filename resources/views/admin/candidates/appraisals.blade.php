@extends('layouts.admin')

@section('title', 'Candidate Appraisals')

@section('header-description', 'Manage candidate appraisals, communications, and warnings')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $candidate->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $candidate->email }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.candidates.appraisals.create', $candidate) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm">
                    + Add Appraisal
                </a>
                <a href="{{ route('admin.candidates.show', $candidate) }}" 
                   class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Back to Profile
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-sm text-slate-500 mb-1">Performance Reviews</p>
                <p class="text-2xl font-bold text-blue-600">{{ $counts['performance_reviews'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-sm text-slate-500 mb-1">HR Communications</p>
                <p class="text-2xl font-bold text-green-600">{{ $counts['hr_communications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-sm text-slate-500 mb-1">Warnings</p>
                <p class="text-2xl font-bold text-red-600">{{ $counts['warnings'] }}</p>
            </div>
        </div>

        <!-- Performance Reviews -->
        @if($appraisals->has('performance_review'))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-900">Performance Reviews</h2>
                </div>
                <div class="divide-y divide-slate-200">
                    @foreach($appraisals->get('performance_review') as $appraisal)
                        <div class="p-6 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $appraisal->title }}</h3>
                                    <p class="text-sm text-slate-600 mb-3">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-slate-500">
                                        <span>Date: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        @if($appraisal->rating)
                                            <span>Rating: <strong class="text-blue-600">{{ $appraisal->rating }}/10</strong></span>
                                        @endif
                                        <span>By: {{ $appraisal->createdByUser->name ?? 'HR' }}</span>
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.candidates.appraisals.edit', $appraisal) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.appraisals.destroy', $appraisal) }}" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this appraisal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- HR Communications -->
        @if($appraisals->has('hr_communication'))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-900">HR Communications</h2>
                </div>
                <div class="divide-y divide-slate-200">
                    @foreach($appraisals->get('hr_communication') as $appraisal)
                        <div class="p-6 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $appraisal->title }}</h3>
                                    <p class="text-sm text-slate-600 mb-3">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-slate-500">
                                        <span>Date: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        <span>By: {{ $appraisal->createdByUser->name ?? 'HR' }}</span>
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.candidates.appraisals.edit', $appraisal) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.appraisals.destroy', $appraisal) }}" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this communication?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Warnings -->
        @if($appraisals->has('warning'))
            <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                    <h2 class="text-xl font-bold text-red-900">Warnings</h2>
                </div>
                <div class="divide-y divide-red-100">
                    @foreach($appraisals->get('warning') as $appraisal)
                        <div class="p-6 hover:bg-red-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-semibold text-red-900">{{ $appraisal->title }}</h3>
                                        @if($appraisal->warning_level)
                                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs font-semibold">
                                                {{ $appraisal->warning_level_label }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-700 mb-3">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-slate-600">
                                        @if($appraisal->warning_date)
                                            <span>Warning Date: {{ $appraisal->warning_date->format('M d, Y') }}</span>
                                        @endif
                                        <span>Issued: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        <span>By: {{ $appraisal->createdByUser->name ?? 'HR' }}</span>
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.candidates.appraisals.edit', $appraisal) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.appraisals.destroy', $appraisal) }}" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this warning?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($appraisals->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-2">No Appraisals Yet</p>
                <p class="text-slate-400 text-sm mb-4">You haven't created any appraisals, communications, or warnings for this candidate.</p>
                <a href="{{ route('admin.candidates.appraisals.create', $candidate) }}" 
                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Create First Appraisal
                </a>
            </div>
        @endif
    </div>
@endsection
