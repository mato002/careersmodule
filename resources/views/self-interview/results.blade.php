@php
    $isCandidate = isset($isCandidateView) && $isCandidateView;
    $layout = $isCandidate ? 'layouts.candidate' : 'layouts.website';
@endphp

@extends($layout)

@section('title', 'Self Interview Results')
@if($isCandidate)
    @section('header-description', 'Your self interview results for ' . ($application->jobPost->title ?? 'this position'))
    
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
                    <h1 class="{{ $isCandidate ? 'text-2xl sm:text-3xl' : 'text-3xl' }} font-bold {{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} mb-2">Self Interview Results</h1>
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
                        
                        <div class="bg-gradient-to-br {{ $session->is_passed ? 'from-green-50 to-green-100 border-green-200' : 'from-red-50 to-red-100 border-red-200' }} rounded-xl p-6 border">
                            <p class="text-sm font-medium {{ $session->is_passed ? 'text-green-700' : 'text-red-700' }} mb-2">Result</p>
                            <p class="text-4xl font-bold {{ $session->is_passed ? 'text-green-900' : 'text-red-900' }}">
                                {{ $session->is_passed ? 'PASSED' : 'FAILED' }}
                            </p>
                            @if(isset($session->time_taken_seconds) && $session->time_taken_seconds)
                                <p class="text-sm {{ $session->is_passed ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    Time: {{ gmdate('i:s', $session->time_taken_seconds) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200">
                            <p class="text-sm font-medium text-teal-700 mb-2">Your Score</p>
                            <p class="text-4xl font-bold text-teal-900">{{ $application->self_interview_score ?? 'N/A' }}%</p>
                        </div>
                        <div class="bg-gradient-to-br {{ $application->self_interview_passed ? 'from-green-50 to-green-100 border-green-200' : 'from-red-50 to-red-100 border-red-200' }} rounded-xl p-6 border">
                            <p class="text-sm font-medium {{ $application->self_interview_passed ? 'text-green-700' : 'text-red-700' }} mb-2">Result</p>
                            <p class="text-4xl font-bold {{ $application->self_interview_passed ? 'text-green-900' : 'text-red-900' }}">
                                {{ $application->self_interview_passed ? 'PASSED' : 'FAILED' }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Detailed Answers (only for multiple-choice questions) -->
                @if(isset($session) && $session && isset($questions) && $questions->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold {{ $isCandidate ? 'text-gray-800' : 'text-slate-800' }} mb-4">Answer Review</h2>

                        @foreach($questions as $question)
                            @php
                                $userAnswer = $session->answers[$question->id] ?? null;
                                $hasOptions = !empty($question->options);
                                $isCorrect = $hasOptions && $question->correct_answer !== null &&
                                    strtolower(trim($userAnswer ?? '')) === strtolower(trim($question->correct_answer));
                            @endphp

                            <div class="mb-6 p-5 rounded-xl border-2 {{ $hasOptions ? ($isCorrect ? 'border-green-200 bg-green-50' : 'border-blue-200 bg-blue-50') : 'border-slate-200 bg-slate-50' }}">
                                <p class="{{ $isCandidate ? 'text-gray-900' : 'text-slate-900' }} font-medium mb-3">{{ $question->question }}</p>

                                @if($hasOptions)
                                    <div class="space-y-2">
                                        @foreach($question->options as $key => $option)
                                            @php
                                                $isUserAnswer = strtolower($key) === strtolower(trim($userAnswer ?? ''));
                                                $isCorrectAnswer = $question->correct_answer !== null &&
                                                    strtolower($key) === strtolower(trim($question->correct_answer));
                                            @endphp
                                            <div class="p-3 rounded-lg border-2 {{ $isCorrectAnswer ? 'border-green-400 bg-green-100' : ($isUserAnswer ? 'border-blue-400 bg-blue-100' : ($isCandidate ? 'border-gray-200' : 'border-slate-200')) }}">
                                                <span class="font-semibold mr-2">{{ strtoupper($key) }}.</span>
                                                {{ $option }}
                                                @if($isCorrectAnswer)
                                                    <span class="ml-2 text-green-700 font-medium">✓ Preferred Answer</span>
                                                @endif
                                                @if($isUserAnswer && !$isCorrectAnswer)
                                                    <span class="ml-2 text-blue-700 font-medium">Your Answer</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-2 p-3 rounded-lg border {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }} bg-white">
                                        <p class="text-sm {{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }}">
                                            {{ $userAnswer ?: 'No response provided.' }}
                                        </p>
                                    </div>
                                @endif

                                @if($question->explanation)
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-900">
                                            <span class="font-semibold">Notes:</span> {{ $question->explanation }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="{{ $isCandidate ? 'bg-gray-50' : 'bg-slate-50' }} rounded-xl p-6 border {{ $isCandidate ? 'border-gray-200' : 'border-slate-200' }}">
                    <h3 class="text-lg font-semibold {{ $isCandidate ? 'text-gray-800' : 'text-slate-800' }} mb-3">Next Steps</h3>
                    @if($session->is_passed ?? $application->self_interview_passed)
                        <p class="{{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }} mb-2">
                            ✅ Great work! You have passed the self interview stage.
                        </p>
                        <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }} text-sm">
                            Our HR team will now review your responses alongside your aptitude test results.
                            If shortlisted, you will be invited for an online or in‑person interview.
                        </p>
                    @else
                        <p class="{{ $isCandidate ? 'text-gray-700' : 'text-slate-700' }} mb-2">
                            Thank you for completing the self interview.
                        </p>
                        <p class="{{ $isCandidate ? 'text-gray-600' : 'text-slate-600' }} text-sm">
                            Your self interview responses will be reviewed together with the rest of your application.
                            We will contact you if there are suitable next steps.
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


