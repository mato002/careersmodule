@extends('layouts.candidate')

@section('title', $appraisal->title)
@section('header-description', 'View appraisal details')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $appraisal->title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $appraisal->type_label }} • {{ $appraisal->created_at->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('candidate.appraisals.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-sm">
                    Back to Appraisals
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Content -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Content</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $appraisal->content }}</p>
                </div>
            </div>

            <!-- Performance Review Details -->
            @if($appraisal->type === 'performance_review')
                <div class="border-t border-gray-200 pt-6 space-y-4">
                    @if($appraisal->rating)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Rating</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-bold text-blue-600">{{ $appraisal->rating }}</span>
                                <span class="text-gray-500">/ 10</span>
                            </div>
                        </div>
                    @endif

                    @if($appraisal->strengths)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Strengths</h4>
                            <p class="text-gray-700 whitespace-pre-line">{{ $appraisal->strengths }}</p>
                        </div>
                    @endif

                    @if($appraisal->areas_for_improvement)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Areas for Improvement</h4>
                            <p class="text-gray-700 whitespace-pre-line">{{ $appraisal->areas_for_improvement }}</p>
                        </div>
                    @endif

                    @if($appraisal->goals)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Goals</h4>
                            <p class="text-gray-700 whitespace-pre-line">{{ $appraisal->goals }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Warning Details -->
            @if($appraisal->type === 'warning')
                <div class="border-t border-red-200 pt-6 space-y-4 bg-red-50 p-4 rounded-lg">
                    @if($appraisal->warning_level)
                        <div>
                            <h4 class="font-semibold text-red-900 mb-2">Warning Level</h4>
                            <span class="px-3 py-1 bg-red-200 text-red-800 rounded text-sm font-semibold">
                                {{ $appraisal->warning_level_label }}
                            </span>
                        </div>
                    @endif

                    @if($appraisal->warning_date)
                        <div>
                            <h4 class="font-semibold text-red-900 mb-2">Warning Date</h4>
                            <p class="text-gray-700">{{ $appraisal->warning_date->format('F d, Y') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Attachments -->
            @if($appraisal->attachments && count($appraisal->attachments) > 0)
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Attachments</h4>
                    <div class="space-y-2">
                        @foreach($appraisal->attachment_urls as $index => $url)
                            <a href="{{ $url }}" target="_blank" 
                               class="flex items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">Attachment {{ $index + 1 }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Acknowledgment -->
            @if(!$appraisal->isAcknowledged())
                <div class="border-t border-gray-200 pt-6">
                    <form method="POST" action="{{ route('candidate.appraisals.acknowledge', $appraisal) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="acknowledgment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Acknowledgment Notes (Optional)
                            </label>
                            <textarea id="acknowledgment_notes" name="acknowledgment_notes" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                      placeholder="Add any notes or comments...">{{ old('acknowledgment_notes') }}</textarea>
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                            Acknowledge
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-200 pt-6">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="font-semibold text-green-900 mb-2">✅ Acknowledged</p>
                        <p class="text-sm text-green-700">
                            Acknowledged on {{ $appraisal->acknowledged_at->format('F d, Y \a\t g:i A') }}
                        </p>
                        @if($appraisal->acknowledgment_notes)
                            <p class="text-sm text-gray-700 mt-2">
                                <strong>Your Notes:</strong> {{ $appraisal->acknowledgment_notes }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Metadata -->
            <div class="border-t border-gray-200 pt-6 text-sm text-gray-500">
                <p>Created by: {{ $appraisal->createdByUser->name ?? 'HR' }}</p>
                <p>Created on: {{ $appraisal->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
@endsection
