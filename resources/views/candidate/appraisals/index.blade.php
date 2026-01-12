@extends('layouts.candidate')

@section('title', 'Appraisals')
@section('header-description', 'View your performance reviews, HR communications, and warnings')

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Performance Reviews</p>
                <p class="text-2xl font-bold text-blue-600">{{ $counts['performance_reviews'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">HR Communications</p>
                <p class="text-2xl font-bold text-green-600">{{ $counts['hr_communications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Warnings</p>
                <p class="text-2xl font-bold text-red-600">{{ $counts['warnings'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-4 bg-amber-50">
                <p class="text-sm text-gray-500 mb-1">Unacknowledged</p>
                <p class="text-2xl font-bold text-amber-600">{{ $counts['unacknowledged'] }}</p>
            </div>
        </div>

        <!-- Performance Reviews -->
        @if($appraisals->has('performance_review'))
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-900">Performance Reviews</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($appraisals->get('performance_review') as $appraisal)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $appraisal->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>Date: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        @if($appraisal->rating)
                                            <span>Rating: <strong class="text-blue-600">{{ $appraisal->rating }}/10</strong></span>
                                        @endif
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('candidate.appraisals.show', $appraisal) }}" 
                                   class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- HR Communications -->
        @if($appraisals->has('hr_communication'))
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-900">HR Communications</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($appraisals->get('hr_communication') as $appraisal)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $appraisal->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>Date: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        <span>From: {{ $appraisal->createdByUser->name ?? 'HR' }}</span>
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('candidate.appraisals.show', $appraisal) }}" 
                                   class="ml-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Warnings -->
        @if($appraisals->has('warning'))
            <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
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
                                    <p class="text-sm text-gray-700 mb-3">{{ Str::limit($appraisal->content, 150) }}</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        @if($appraisal->warning_date)
                                            <span>Warning Date: {{ $appraisal->warning_date->format('M d, Y') }}</span>
                                        @endif
                                        <span>Issued: {{ $appraisal->created_at->format('M d, Y') }}</span>
                                        @if($appraisal->isAcknowledged())
                                            <span class="text-green-600 font-semibold">✅ Acknowledged</span>
                                        @else
                                            <span class="text-amber-600 font-semibold">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('candidate.appraisals.show', $appraisal) }}" 
                                   class="ml-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($appraisals->isEmpty())
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Appraisals Yet</h3>
                <p class="text-gray-500">You don't have any appraisals, communications, or warnings at this time.</p>
            </div>
        @endif
    </div>
@endsection
