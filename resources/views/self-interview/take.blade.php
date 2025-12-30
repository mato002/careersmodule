@php
    $isCandidate = isset($isCandidateView) && $isCandidateView;
    $layout = $isCandidate ? 'layouts.candidate' : 'layouts.website';
@endphp

@extends($layout)

@section('title', 'Self Interview')
@if($isCandidate)
    @section('header-description', 'Complete your self interview for ' . ($application->jobPost->title ?? 'this position'))
    
    @section('header-actions')
        <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-teal-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-white whitespace-nowrap">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="hidden sm:inline">Back to Dashboard</span>
            <span class="sm:hidden">Back</span>
        </a>
    @endsection
@endif

@section('content')
    @if($isCandidate)
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
    @else
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
    @endif
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="{{ $isCandidate ? 'text-2xl sm:text-3xl' : 'text-3xl' }} font-bold {{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} mb-2">Self Interview</h1>
                    <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }}">Position: <span class="font-semibold">{{ $application->jobPost->title ?? 'N/A' }}</span></p>
                    <p class="text-sm {{ $isCandidate ? 'text-gray-500' : 'text-slate-500' }} mt-1">Answer the questions below honestly. Your responses help us understand your fit for this role.</p>
                </div>

                <!-- Optional timer (shorter, reflective style) -->
                <div class="mb-6 bg-teal-50 border border-teal-200 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-teal-900">Suggested Time:</span>
                        <span class="text-sm font-semibold text-teal-700">20–25 minutes</span>
                    </div>
                </div>

                <!-- Self Interview Form -->
                <form id="selfInterviewForm" method="POST" action="{{ route('self-interview.submit', $application) }}">
                    @csrf

                    @foreach($questions as $index => $question)
                        <div class="mb-8 p-6 {{ $isCandidate ? 'bg-gray-50' : 'bg-slate-50' }} rounded-xl border {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }}">
                            <div class="flex items-start justify-between mb-4">
                                <span class="text-sm font-medium {{ $isCandidate ? 'text-gray-500' : 'text-slate-500' }}">Question {{ $index + 1 }}</span>
                                <span class="text-xs {{ $isCandidate ? 'text-gray-400' : 'text-slate-400' }}">{{ $question->points }} points</span>
                            </div>

                            <p class="{{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} font-medium mb-4 leading-relaxed">{{ $question->question }}</p>

                            @if(!empty($question->options))
                                <!-- Multiple choice style (auto-marked like aptitude) -->
                                <div class="space-y-3">
                                    @foreach($question->options as $key => $option)
                                        <label class="flex items-start p-3 rounded-lg border-2 {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }} hover:border-teal-400 cursor-pointer transition">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $key }}"
                                                   class="mt-1 mr-3 w-4 h-4 text-teal-600 focus:ring-teal-500"
                                                   @if(old("answers.{$question->id}") === $key) checked @endif>
                                            <span class="flex-1 {{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }}">
                                                <span class="font-semibold mr-2">{{ strtoupper($key) }}.</span>
                                                {{ $option }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <!-- Open-ended answer -->
                                <textarea
                                    name="answers[{{ $question->id }}]"
                                    rows="4"
                                    class="w-full text-sm rounded-lg border {{ $isCandidate ? 'border-gray-300' : 'border-slate-300' }} focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Type your response here...">{{ old("answers.{$question->id}") }}</textarea>
                            @endif
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="mt-8 pt-6 border-t {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }}">
                        <button type="submit"
                                id="submitBtn"
                                class="w-full sm:w-auto px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                            Submit Self Interview
                        </button>
                        <p class="text-sm {{ $isCandidate ? 'text-gray-500' : 'text-slate-500' }} mt-3">
                            Please review your answers before submitting. Once submitted, you will not be able to edit them.
                        </p>
                    </div>
                </form>
            </div>
        @if(!$isCandidate)
            </div>
        </div>
        @endif
    </div>
@endsection


