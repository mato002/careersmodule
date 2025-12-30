@php
    $isCandidate = isset($isCandidateView) && $isCandidateView;
    $layout = $isCandidate ? 'layouts.candidate' : 'layouts.website';
@endphp

@extends($layout)

@section('title', 'Aptitude Test Results')
@if($isCandidate)
    @section('header-description', 'Your aptitude test results for ' . ($application->jobPost->title ?? 'this position'))
    
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
                <div class="mb-8 text-center">
                    <h1 class="{{ $isCandidate ? 'text-2xl sm:text-3xl' : 'text-3xl' }} font-bold {{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} mb-2">Aptitude Test Results</h1>
                    <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }}">Position: <span class="font-semibold">{{ $application->jobPost->title ?? 'N/A' }}</span></p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Score Summary -->
                @if(isset($session) && $session)
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200">
                            <p class="text-sm font-medium text-teal-700 mb-2">Your Score</p>
                            <p class="text-4xl font-bold text-teal-900">{{ $session->total_score ?? 0 }}</p>
                            <p class="text-sm text-teal-600 mt-1">out of {{ $session->total_possible_score ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <p class="text-sm font-medium text-blue-700 mb-2">Pass Threshold</p>
                            <p class="text-4xl font-bold text-blue-900">{{ $session->pass_threshold ?? 70 }}</p>
                            <p class="text-sm text-blue-600 mt-1">points required</p>
                        </div>
                        
                        <div class="bg-gradient-to-br {{ ($session->is_passed ?? $application->aptitude_test_passed) ? 'from-green-50 to-green-100 border-green-200' : 'from-red-50 to-red-100 border-red-200' }} rounded-xl p-6 border">
                            <p class="text-sm font-medium {{ ($session->is_passed ?? $application->aptitude_test_passed) ? 'text-green-700' : 'text-red-700' }} mb-2">Result</p>
                            <p class="text-4xl font-bold {{ ($session->is_passed ?? $application->aptitude_test_passed) ? 'text-green-900' : 'text-red-900' }}">
                                {{ ($session->is_passed ?? $application->aptitude_test_passed) ? 'PASSED' : 'FAILED' }}
                            </p>
                            @if(isset($session->time_taken_seconds) && $session->time_taken_seconds)
                                <p class="text-sm {{ ($session->is_passed ?? $application->aptitude_test_passed) ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    Time: {{ gmdate('i:s', $session->time_taken_seconds) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Basic results without session -->
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200">
                            <p class="text-sm font-medium text-teal-700 mb-2">Your Score</p>
                            <p class="text-4xl font-bold text-teal-900">{{ $application->aptitude_test_score ?? 'N/A' }}%</p>
                        </div>
                        <div class="bg-gradient-to-br {{ $application->aptitude_test_passed ? 'from-green-50 to-green-100 border-green-200' : 'from-red-50 to-red-100 border-red-200' }} rounded-xl p-6 border">
                            <p class="text-sm font-medium {{ $application->aptitude_test_passed ? 'text-green-700' : 'text-red-700' }} mb-2">Result</p>
                            <p class="text-4xl font-bold {{ $application->aptitude_test_passed ? 'text-green-900' : 'text-red-900' }}">
                                {{ $application->aptitude_test_passed ? 'PASSED' : 'FAILED' }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Detailed Results -->
                @if(isset($session) && $session && isset($questions) && $questions->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold {{ $isCandidate ? 'text-gray-800' : 'text-slate-800' }} mb-4">Question Review</h2>
                        
                        @php
                            $sectionOrder = ['numerical', 'logical', 'verbal', 'scenario'];
                            $currentSection = null;
                        @endphp

                        @foreach($questions as $question)
                        @if($currentSection !== $question->section)
                            @if($currentSection !== null)
                                </div>
                            @endif
                            @php
                                $currentSection = $question->section;
                                $sectionTitles = [
                                    'numerical' => 'Section A: Numerical & Analytical',
                                    'logical' => 'Section B: Logical Reasoning',
                                    'verbal' => 'Section C: Verbal & Comprehension',
                                    'scenario' => 'Section D: Job-Fit Scenarios'
                                ];
                            @endphp
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold {{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }} mb-3">
                                    {{ $sectionTitles[$question->section] ?? ucfirst($question->section) }}
                                </h3>
                        @endif

                        @php
                            $userAnswer = $session->questions_answered[$question->id] ?? null;
                            $isCorrect = strtolower(trim($userAnswer ?? '')) === strtolower(trim($question->correct_answer));
                        @endphp

                        <div class="mb-6 p-5 rounded-xl border-2 {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    @if($isCorrect)
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium text-green-700">Correct (+{{ $question->points }} points)</span>
                                    @else
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium text-red-700">Incorrect (0 points)</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="{{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} font-medium mb-3">{{ $question->question }}</p>
                            
                            <div class="space-y-2">
                                @foreach($question->options as $key => $option)
                                    @php
                                        $isUserAnswer = strtolower($key) === strtolower(trim($userAnswer ?? ''));
                                        $isCorrectAnswer = strtolower($key) === strtolower(trim($question->correct_answer));
                                    @endphp
                                    <div class="p-3 rounded-lg border-2 {{ $isCorrectAnswer ? 'border-green-400 bg-green-100' : ($isUserAnswer ? 'border-red-400 bg-red-100' : ($isCandidate ? 'border-gray-200' : 'border-slate-200')) }}">
                                        <span class="font-semibold mr-2">{{ strtoupper($key) }}.</span>
                                        {{ $option }}
                                        @if($isCorrectAnswer)
                                            <span class="ml-2 text-green-700 font-medium">✓ Correct Answer</span>
                                        @endif
                                        @if($isUserAnswer && !$isCorrectAnswer)
                                            <span class="ml-2 text-red-700 font-medium">✗ Your Answer</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($question->explanation && !$isCorrect)
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-900">
                                        <span class="font-semibold">Explanation:</span> {{ $question->explanation }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                        </div>
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="{{ $isCandidate ? 'bg-gray-50' : 'bg-slate-50' }} rounded-xl p-6 border {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }}">
                    <h3 class="text-lg font-semibold {{ $isCandidate ? 'text-gray-800' : 'text-slate-800' }} mb-3">Next Steps</h3>
                    @php
                        $hasPassed = ($session->is_passed ?? $application->aptitude_test_passed);
                    @endphp
                    @if($hasPassed)
                        @if($isCandidate && ! $application->self_interview_completed_at)
                            <p class="text-gray-700 mb-2">
                                ✅ Great work! You have passed the aptitude test.
                            </p>
                            <p class="text-gray-600 text-sm mb-3">
                                Next, please complete your <strong>Self Interview</strong>. This helps us understand your experience and motivation in more depth.
                            </p>
                            <a href="{{ route('self-interview.show', $application) }}"
                               class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition">
                                Start Self Interview
                            </a>
                        @else
                            <p class="{{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }} mb-2">
                                ✅ Congratulations! You have passed the aptitude test. 
                            </p>
                            <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }} text-sm">
                                Our team will review your application and contact you for the next stage of the interview process.
                            </p>
                        @endif
                    @else
                        <p class="{{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }} mb-2">
                            Unfortunately, you did not meet the minimum score requirement for this position.
                        </p>
                        <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }} text-sm">
                            Thank you for your interest in Fortress Lenders. We encourage you to apply for other positions that may be a better fit.
                        </p>
                    @endif
                </div>

                <div class="mt-6 text-center">
                    @if($isCandidate)
                        <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition">
                            Back to Dashboard
                        </a>
                    @else
                        <a href="{{ route('careers.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition">
                            Return to Careers
                        </a>
                    @endif
                </div>
            </div>
        @if(!$isCandidate)
            </div>
        </div>
        @endif
    </div>
@endsection
