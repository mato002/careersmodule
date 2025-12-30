@extends('layouts.website')

@section('title', 'Application Status - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Application Status</h1>
            <p class="text-lg sm:text-xl text-teal-100">Track your job application progress</p>
        </div>
    </section>

    <!-- Application Status Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50 overflow-x-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 md:p-10 space-y-6">
                <!-- Application Info -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Application Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Position Applied For</p>
                            <p class="font-semibold text-gray-900">{{ $application->jobPost->title }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Applicant Name</p>
                            <p class="font-semibold text-gray-900">{{ $application->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Email</p>
                            <p class="font-semibold text-gray-900">{{ $application->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Phone</p>
                            <p class="font-semibold text-gray-900">{{ $application->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Application Date</p>
                            <p class="font-semibold text-gray-900">{{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Current Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($application->status === 'sieving_passed' || $application->status === 'stage_2_passed') bg-green-100 text-green-800
                                @elseif($application->status === 'sieving_rejected') bg-red-100 text-red-800
                                @elseif($application->status === 'pending_manual_review') bg-amber-100 text-amber-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- AI Sieving Decision -->
                @if($application->aiSievingDecision)
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">AI Sieving Results</h2>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">AI Decision:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($application->aiSievingDecision->ai_decision === 'pass') bg-green-100 text-green-800
                                    @elseif($application->aiSievingDecision->ai_decision === 'reject') bg-red-100 text-red-800
                                    @else bg-amber-100 text-amber-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $application->aiSievingDecision->ai_decision)) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Score:</span>
                                <span class="font-semibold text-gray-900">{{ $application->aiSievingDecision->ai_score }}/100</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Confidence:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($application->aiSievingDecision->ai_confidence * 100, 1) }}%</span>
                            </div>
                            @if($application->aiSievingDecision->ai_reasoning)
                                <div>
                                    <span class="text-sm text-gray-600 block mb-1">Reasoning:</span>
                                    <p class="text-sm text-gray-700">{{ $application->aiSievingDecision->ai_reasoning }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Aptitude Test Section -->
                @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']))
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Aptitude Test</h2>
                        
                        @if($application->aptitude_test_completed_at)
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($application->aptitude_test_passed) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }}
                                    </span>
                                </div>
                                @if($application->aptitude_test_score !== null)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Score:</span>
                                        <span class="font-semibold text-gray-900">{{ $application->aptitude_test_score }}%</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Completed:</span>
                                    <span class="font-semibold text-gray-900">{{ $application->aptitude_test_completed_at->format('M d, Y g:i A') }}</span>
                                </div>
                                <div class="pt-3">
                                    <a href="{{ route('aptitude-test.results', $application) }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                        View Test Results
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-lg font-semibold text-teal-900 mb-2">You're eligible to take the Aptitude Test!</h3>
                                        <p class="text-sm text-teal-700 mb-4">
                                            Congratulations! You've passed the initial screening. Please complete the aptitude test to proceed to the next stage.
                                        </p>
                                        <a href="{{ route('aptitude-test.show', $application) }}" class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                            Start Aptitude Test
                                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Next Steps -->
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Next Steps</h2>
                    <div class="space-y-3">
                        @if($application->status === 'sieving_rejected')
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm text-red-700">
                                    Unfortunately, your application did not meet the initial screening criteria. 
                                    We appreciate your interest and encourage you to apply for other positions in the future.
                                </p>
                            </div>
                        @elseif($application->status === 'pending_manual_review')
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <p class="text-sm text-amber-700">
                                    Your application is under manual review. Our team will carefully evaluate your application and get back to you soon.
                                </p>
                            </div>
                        @elseif($application->status === 'sieving_passed' && !$application->aptitude_test_completed_at)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-700">
                                    Please complete the aptitude test to proceed to the next stage of the hiring process.
                                </p>
                            </div>
                        @elseif($application->aptitude_test_passed)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-green-700">
                                    Great job! You've passed the aptitude test. Our team will contact you soon regarding the next steps, which may include an interview.
                                </p>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-sm text-gray-700">
                                    Your application is being processed. We'll keep you updated on any changes to your application status.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('careers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-semibold">
                            ← Back to Careers
                        </a>
                        @auth
                            @if(auth()->user()->isCandidate())
                                <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                    View All Applications
                                </a>
                            @endif
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                                <p class="font-semibold mb-1">Have an account?</p>
                                <p>If an admin has created an account for you, <a href="{{ route('login') }}" class="underline font-semibold">log in</a> to view all your applications in one place.</p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

