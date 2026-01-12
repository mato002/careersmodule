@extends('layouts.candidate')

@section('title', 'My Applications')
@section('header-description', 'Track your job applications and next steps')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-green-100 p-4 bg-green-50">
            <p class="text-sm text-gray-500 mb-1">Passed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['sieving_passed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-red-100 p-4 bg-red-50">
            <p class="text-sm text-gray-500 mb-1">Rejected</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['sieving_rejected'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-blue-100 p-4 bg-blue-50">
            <p class="text-sm text-gray-500 mb-1">Stage 2</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['stage_2_passed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-purple-100 p-4 bg-purple-50">
            <p class="text-sm text-gray-500 mb-1">Hired</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</p>
        </div>
    </div>

    <!-- Next Steps / Action Required -->
    @php
        $pendingActions = $applications->filter(function($app) {
            return in_array($app->status, ['sieving_passed', 'pending_manual_review']) && !$app->aptitude_test_completed_at;
        });
        
        // Check for any upcoming interviews (all types: first, online_interview, second, written_test, case_study)
        $upcomingInterviews = $applications->filter(function($app) {
            if (in_array($app->status, ['hired', 'rejected', 'interview_failed'])) {
                return false;
            }
            $interview = $app->interviews()
                ->whereIn('interview_type', ['first', 'online_interview', 'second', 'written_test', 'case_study'])
                ->whereNull('result')
                ->where('scheduled_at', '>', now())
                ->first();
            return $interview !== null;
        });
        
        // Applications where aptitude test is passed but self interview is not yet completed
        $passedAptitudeWaiting = $applications->filter(function($app) {
            return $app->aptitude_test_passed
                && !$app->self_interview_completed_at
                && !in_array($app->status, ['stage_2_passed', 'hired', 'sieving_rejected']);
        });

        // Applications where self interview is completed (pass or fail) but no interview is scheduled yet
        $selfInterviewWaiting = $applications->filter(function($app) {
            if (!$app->aptitude_test_passed || !$app->self_interview_completed_at) {
                return false;
            }
            if (in_array($app->status, ['stage_2_passed', 'hired', 'sieving_rejected'])) {
                return false;
            }
            // Self interview is fully rule‑based; at this point the system has
            // already decided pass/fail. We just show a summary until HR (or
            // the system) schedules the next interview stage.
            return !$app->interviews()
                ->whereIn('interview_type', ['first', 'online_interview', 'second', 'written_test', 'case_study'])
                ->whereNull('result')
                ->where('scheduled_at', '>', now())
                ->exists();
        });
        
        // Applications where candidate passed aptitude + first interview (stage_2_passed or interview_passed)
        $passedStage2Waiting = $applications->filter(function($app) {
            return in_array($app->status, ['stage_2_passed', 'interview_passed', 'second_interview', 'written_test', 'case_study'])
                && !$app->interviews()
                    ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                    ->whereNull('result')
                    ->where('scheduled_at', '>', now())
                    ->exists();
        });
    @endphp
    
    @if($pendingActions->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-amber-900 mb-2">Action Required</h2>
                    <p class="text-sm text-amber-800 mb-4">You have {{ $pendingActions->count() }} application(s) that require your attention:</p>
                    <div class="space-y-3">
                        @foreach($pendingActions as $app)
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600">Aptitude test pending</p>
                                    </div>
                                    <a href="{{ route('aptitude-test.show', $app) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                                        Take Test
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($upcomingInterviews->count() > 0)
        <div class="bg-teal-50 border border-teal-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-teal-900 mb-2">Upcoming Interviews & Assessments</h2>
                    <p class="text-sm text-teal-800 mb-4">You have {{ $upcomingInterviews->count() }} scheduled interview(s) or assessment(s):</p>
                    <div class="space-y-3">
                        @foreach($upcomingInterviews as $app)
                            @php
                                $interview = $app->interviews()
                                    ->whereIn('interview_type', ['first', 'online_interview', 'second', 'written_test', 'case_study'])
                                    ->whereNull('result')
                                    ->where('scheduled_at', '>', now())
                                    ->orderBy('scheduled_at', 'asc')
                                    ->first();
                                
                                $interviewTypeLabels = [
                                    'first' => 'First Interview',
                                    'online_interview' => 'Online Interview',
                                    'second' => 'Second Interview',
                                    'written_test' => 'Written Test',
                                    'case_study' => 'Case Study Assessment'
                                ];
                                $interviewLabel = $interviewTypeLabels[$interview->interview_type] ?? ucfirst(str_replace('_', ' ', $interview->interview_type));
                            @endphp
                            <div class="bg-white rounded-lg p-4 border border-teal-200">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm font-medium text-teal-700 mt-1">{{ $interviewLabel }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <strong>Date & Time:</strong> {{ $interview->scheduled_at->format('l, F d, Y \a\t g:i A') }}
                                        </p>
                                        @if($interview->location)
                                            <p class="text-sm text-gray-600">
                                                <strong>Location:</strong> {{ $interview->location }}
                                            </p>
                                        @endif
                                        @if($interview->notes)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $interview->notes }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('candidate.application.show', $app) }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold whitespace-nowrap">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($passedStage2Waiting->count() > 0)
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-green-900 mb-2">🎉 Stage 2 Complete - Awaiting Final Steps</h2>
                    <p class="text-sm text-green-800 mb-4">
                        Excellent progress! You've successfully passed both the aptitude test and first interview for {{ $passedStage2Waiting->count() }} application(s). 
                        Our HR team is now evaluating your profile for the final stage.
                    </p>
                    <div class="bg-white rounded-lg p-4 border border-green-300 mb-3">
                        <p class="text-sm font-semibold text-green-900 mb-2">📧 What Happens Next?</p>
                        <ul class="text-sm text-green-800 space-y-1 list-disc list-inside">
                            <li>Our HR team is reviewing your complete application profile</li>
                            <li>You may be invited for a <strong>Second Interview</strong>, <strong>Written Test</strong>, or <strong>Case Study</strong> assessment</li>
                            <li>You will receive an email notification when the next step is scheduled</li>
                            <li>Please check your email regularly for updates</li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        @foreach($passedStage2Waiting as $app)
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Status: 
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                                @if($app->status === 'stage_2_passed')
                                                    Stage 2 Complete
                                                @elseif($app->status === 'interview_passed')
                                                    Interview Passed
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                                @endif
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-2">
                                            ✅ Aptitude Test: Passed ({{ $app->aptitude_test_score }}%) • 
                                            ✅ First Interview: Passed
                                        </p>
                                    </div>
                                    <a href="{{ route('candidate.application.show', $app) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold whitespace-nowrap">
                                        View Application
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($passedAptitudeWaiting->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-blue-900 mb-2">Aptitude Test Passed – Next: Self Interview</h2>
                    <p class="text-sm text-blue-800 mb-4">
                        You've successfully passed the aptitude test for {{ $passedAptitudeWaiting->count() }} application(s).
                        Next step: complete your <strong>Self Interview</strong> so HR can review your profile for interview scheduling.
                    </p>
                    <div class="space-y-3">
                        @foreach($passedAptitudeWaiting as $app)
                            <div class="bg-white rounded-lg p-4 border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600">
                                            Test Score: <strong class="text-green-600">{{ $app->aptitude_test_score }}%</strong>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Next: complete the self interview for this role. Once done, HR may invite you to an online or in‑person interview.
                                        </p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <a href="{{ route('self-interview.show', $app) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                            Start Self Interview
                                        </a>
                                        <a href="{{ route('candidate.application.show', $app) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($selfInterviewWaiting->count() > 0)
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-indigo-900 mb-2">Self Interview Completed – Automatic Evaluation</h2>
                    <p class="text-sm text-indigo-800 mb-4">
                        You've completed the self interview for {{ $selfInterviewWaiting->count() }} application(s).
                        The system has automatically evaluated your responses based on predefined rules. If you pass this stage, you may be invited for an online or in‑person interview.
                    </p>
                    <div class="space-y-3">
                        @foreach($selfInterviewWaiting as $app)
                            <div class="bg-white rounded-lg p-4 border border-indigo-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600">
                                            Aptitude: <strong class="text-green-600">{{ $app->aptitude_test_score }}%</strong> •
                                            @if($app->self_interview_passed)
                                                Self Interview: <strong class="text-green-600">{{ $app->self_interview_score ?? 'Passed' }}@if($app->self_interview_score)%@endif</strong>
                                            @else
                                                Self Interview: <strong class="text-amber-600">{{ $app->self_interview_score ?? 'Completed' }}@if($app->self_interview_score)%@endif</strong>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            You don't need to take any action now. We'll email you if an interview is scheduled.
                                        </p>
                                    </div>
                                    <a href="{{ route('candidate.application.show', $app) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-semibold">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Applications List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-900">Your Applications</h2>
        </div>

        @if($applications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($applications as $application)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $application->jobPost->title }}</h3>
                                    @php
                                        // Derive a more candidate‑friendly status label
                                        $rawStatus = $application->status;
                                        $displayStatus = $rawStatus;

                                        if ($application->aptitude_test_passed && !$application->self_interview_completed_at && !in_array($rawStatus, ['stage_2_passed', 'hired', 'sieving_rejected'])) {
                                            $displayStatus = 'aptitude_test_passed';
                                        } elseif ($application->aptitude_test_passed && $application->self_interview_passed && !in_array($rawStatus, ['stage_2_passed', 'hired', 'sieving_rejected'])) {
                                            $displayStatus = 'self_interview_passed';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($displayStatus === 'hired') bg-purple-100 text-purple-800
                                        @elseif($displayStatus === 'stage_2_passed') bg-blue-100 text-blue-800
                                        @elseif(in_array($displayStatus, ['sieving_passed','aptitude_test_passed','self_interview_passed'])) bg-green-100 text-green-800
                                        @elseif(in_array($displayStatus, ['sieving_rejected','interview_failed'])) bg-red-100 text-red-800
                                        @elseif($displayStatus === 'pending_manual_review') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($displayStatus === 'aptitude_test_passed')
                                            Aptitude Passed
                                        @elseif($displayStatus === 'self_interview_passed')
                                            Self Interview Passed
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                        @endif
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">
                                    Applied on {{ $application->created_at->format('M d, Y') }}
                                </p>
                                
                                <!-- Next Step Indicator -->
                                @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']) && !$application->aptitude_test_completed_at)
                                    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-amber-900 mb-1">📋 Next Step: Complete Aptitude Test</p>
                                        <p class="text-xs text-amber-700">You've passed the initial screening. Take the aptitude test to proceed.</p>
                                    </div>
                                @elseif($application->aptitude_test_passed && !$application->self_interview_completed_at && $application->status !== 'stage_2_passed' && $application->status !== 'hired')
                                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-blue-900 mb-1">✅ Next Step: Self Interview</p>
                                        <p class="text-xs text-blue-700">
                                            You've passed the aptitude test. Complete your self interview so the system can automatically evaluate your fit for the next stage.
                                        </p>
                                    </div>
                                @elseif($application->aptitude_test_passed && $application->self_interview_completed_at && $application->status !== 'stage_2_passed' && $application->status !== 'hired')
                                    @php
                                        // Check for any upcoming interviews (first, second, written_test, case_study)
                                        $upcomingInterview = $application->interviews()
                                            ->whereIn('interview_type', ['first', 'online_interview', 'second', 'written_test', 'case_study'])
                                            ->whereNull('result')
                                            ->where('scheduled_at', '>', now())
                                            ->orderBy('scheduled_at', 'asc')
                                            ->first();
                                        
                                        // Check for completed first/online interview
                                        $completedFirstInterview = $application->interviews()
                                            ->whereIn('interview_type', ['first', 'online_interview'])
                                            ->whereNotNull('result')
                                            ->orderBy('scheduled_at', 'desc')
                                            ->first();
                                    @endphp
                                    
                                    @if($upcomingInterview)
                                        @php
                                            $interviewTypeLabels = [
                                                'first' => 'First Interview',
                                                'online_interview' => 'Online Interview',
                                                'second' => 'Second Interview',
                                                'written_test' => 'Written Test',
                                                'case_study' => 'Case Study'
                                            ];
                                            $interviewLabel = $interviewTypeLabels[$upcomingInterview->interview_type] ?? ucfirst(str_replace('_', ' ', $upcomingInterview->interview_type));
                                        @endphp
                                        <div class="mt-3 bg-teal-50 border border-teal-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-teal-900 mb-1">📅 {{ $interviewLabel }} Scheduled</p>
                                            <p class="text-xs text-teal-700 mb-2">
                                                Your {{ strtolower($interviewLabel) }} is scheduled for 
                                                <strong>{{ $upcomingInterview->scheduled_at->format('l, F d, Y \a\t g:i A') }}</strong>
                                            </p>
                                            @if($upcomingInterview->location)
                                                <p class="text-xs text-teal-700">
                                                    <strong>Location:</strong> {{ $upcomingInterview->location }}
                                                </p>
                                            @endif
                                            @if($upcomingInterview->notes)
                                                <p class="text-xs text-teal-700 mt-1">
                                                    <strong>Notes:</strong> {{ $upcomingInterview->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif($completedFirstInterview && $completedFirstInterview->result === 'pass')
                                        <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-green-900 mb-1">✅ First Interview Passed</p>
                                            <p class="text-xs text-green-700 mb-2">
                                                Congratulations! You've successfully passed the first interview. Our HR team is now reviewing your profile for the next stage.
                                            </p>
                                            <p class="text-xs text-green-600 font-medium mt-2">
                                                📧 Next Steps: You will receive an email notification if you're selected for a second interview, written test, or case study assessment.
                                            </p>
                                        </div>
                                    @elseif($completedFirstInterview && $completedFirstInterview->result === 'fail')
                                        <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-red-900 mb-1">Interview Not Successful</p>
                                            <p class="text-xs text-red-700">Thank you for your interest. We'll keep your application on file for future opportunities.</p>
                                        </div>
                                    @elseif($application->status === 'interview_passed')
                                        <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-green-900 mb-1">✅ Interview Passed</p>
                                            <p class="text-xs text-green-700 mb-2">
                                                Great news! You've passed the interview stage. Our HR team is evaluating your application for the next phase.
                                            </p>
                                            <p class="text-xs text-green-600 font-medium mt-2">
                                                📧 Next Steps: You will be contacted via email if you're selected to proceed. This may include a second interview, written test, or case study assessment.
                                            </p>
                                        </div>
                                    @elseif($application->self_interview_passed)
                                        <div class="mt-3 bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-indigo-900 mb-1">✅ Self Interview Passed</p>
                                            <p class="text-xs text-indigo-700">
                                                You've completed and passed your self interview. Our HR team will review your profile and may invite you for an online or in‑person interview.
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-indigo-900 mb-1">Self Interview Completed</p>
                                            <p class="text-xs text-indigo-700">
                                                You've submitted your self interview responses. HR will review them together with your aptitude results and contact you if there are suitable next steps.
                                            </p>
                                        </div>
                                    @endif
                                @elseif($application->status === 'stage_2_passed')
                                    @php
                                        // Check for upcoming second interview, written test, or case study
                                        $upcomingNextStage = $application->interviews()
                                            ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                                            ->whereNull('result')
                                            ->where('scheduled_at', '>', now())
                                            ->orderBy('scheduled_at', 'asc')
                                            ->first();
                                        
                                        // Check for completed second interview/written test/case study
                                        $completedNextStage = $application->interviews()
                                            ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                                            ->whereNotNull('result')
                                            ->orderBy('scheduled_at', 'desc')
                                            ->first();
                                    @endphp
                                    
                                    @if($upcomingNextStage)
                                        @php
                                            $stageLabels = [
                                                'second' => 'Second Interview',
                                                'written_test' => 'Written Test',
                                                'case_study' => 'Case Study Assessment'
                                            ];
                                            $stageLabel = $stageLabels[$upcomingNextStage->interview_type] ?? ucfirst(str_replace('_', ' ', $upcomingNextStage->interview_type));
                                        @endphp
                                        <div class="mt-3 bg-purple-50 border border-purple-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-purple-900 mb-1">🎉 Stage 2 Complete - {{ $stageLabel }} Scheduled</p>
                                            <p class="text-xs text-purple-700 mb-2">
                                                Excellent progress! You've passed both the aptitude test and first interview. 
                                                Your {{ strtolower($stageLabel) }} is scheduled for:
                                                <strong>{{ $upcomingNextStage->scheduled_at->format('l, F d, Y \a\t g:i A') }}</strong>
                                            </p>
                                            @if($upcomingNextStage->location)
                                                <p class="text-xs text-purple-700">
                                                    <strong>Location:</strong> {{ $upcomingNextStage->location }}
                                                </p>
                                            @endif
                                            @if($upcomingNextStage->notes)
                                                <p class="text-xs text-purple-700 mt-1">
                                                    <strong>Additional Information:</strong> {{ $upcomingNextStage->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif($completedNextStage && $completedNextStage->result === 'pass')
                                        @if($completedNextStage->interview_type === 'second')
                                            <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-sm font-semibold text-green-900 mb-1">🎉 Second Interview Passed</p>
                                                <p class="text-xs text-green-700">
                                                    Outstanding! You've successfully passed the second interview. Our team is finalizing the hiring decision. 
                                                    <strong>You will be contacted shortly with the final outcome.</strong>
                                                </p>
                                            </div>
                                        @else
                                            <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-sm font-semibold text-green-900 mb-1">✅ Assessment Passed</p>
                                                <p class="text-xs text-green-700">
                                                    Great work! You've successfully completed and passed the assessment. 
                                                    Our HR team is reviewing all your results and will contact you with next steps, which may include a final interview.
                                                </p>
                                            </div>
                                        @endif
                                    @elseif($completedNextStage && $completedNextStage->result === 'fail')
                                        <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-amber-900 mb-1">Assessment Completed</p>
                                            <p class="text-xs text-amber-700">
                                                Thank you for completing the assessment. Our team will review all components of your application and contact you with further updates.
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-green-900 mb-1">🎉 Stage 2 Complete - Awaiting Next Steps</p>
                                            <p class="text-xs text-green-700 mb-2">
                                                Congratulations! You've successfully passed both the aptitude test and first interview. 
                                                You've reached Stage 2 of the hiring process.
                                            </p>
                                            <p class="text-xs text-green-600 font-medium mt-2">
                                                📧 Next Steps: Our HR team is reviewing your application and may invite you for:
                                                <ul class="list-disc list-inside mt-1 space-y-1">
                                                    <li>A <strong>Second Interview</strong> (in-person or video call)</li>
                                                    <li>A <strong>Written Test</strong> or <strong>Case Study</strong> assessment</li>
                                                    <li>Final decision on your application</li>
                                                </ul>
                                                <span class="block mt-2">You will receive an email notification when the next stage is scheduled. Please check your email regularly.</span>
                                            </p>
                                        </div>
                                    @endif
                                @elseif(in_array($application->status, ['second_interview', 'written_test', 'case_study']))
                                    @php
                                        $statusLabels = [
                                            'second_interview' => 'Second Interview',
                                            'written_test' => 'Written Test',
                                            'case_study' => 'Case Study'
                                        ];
                                        $currentStageLabel = $statusLabels[$application->status] ?? ucfirst(str_replace('_', ' ', $application->status));
                                        
                                        $upcomingStageInterview = $application->interviews()
                                            ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                                            ->whereNull('result')
                                            ->where('scheduled_at', '>', now())
                                            ->first();
                                    @endphp
                                    
                                    @if($upcomingStageInterview)
                                        <div class="mt-3 bg-purple-50 border border-purple-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-purple-900 mb-1">📅 {{ $currentStageLabel }} Scheduled</p>
                                            <p class="text-xs text-purple-700 mb-2">
                                                Your {{ strtolower($currentStageLabel) }} is scheduled for:
                                                <strong>{{ $upcomingStageInterview->scheduled_at->format('l, F d, Y \a\t g:i A') }}</strong>
                                            </p>
                                            @if($upcomingStageInterview->location)
                                                <p class="text-xs text-purple-700">
                                                    <strong>Location:</strong> {{ $upcomingStageInterview->location }}
                                                </p>
                                            @endif
                                            @if($upcomingStageInterview->notes)
                                                <p class="text-xs text-purple-700 mt-1">
                                                    <strong>Notes:</strong> {{ $upcomingStageInterview->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-blue-900 mb-1">⏳ {{ $currentStageLabel }} - Awaiting Scheduling</p>
                                            <p class="text-xs text-blue-700">
                                                Your application is at the {{ strtolower($currentStageLabel) }} stage. 
                                                Our HR team will contact you with scheduling details soon. Please check your email for updates.
                                            </p>
                                        </div>
                                    @endif
                                @elseif($application->status === 'sieving_rejected')
                                    <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-red-900 mb-1">Application Not Selected</p>
                                        <p class="text-xs text-red-700">This application did not meet the initial requirements.</p>
                                    </div>
                                @endif

                                @if($application->aiSievingDecision)
                                    <div class="flex items-center gap-4 text-sm mt-3">
                                        <span class="text-gray-600">
                                            AI Score: <strong class="text-gray-900">{{ $application->aiSievingDecision->ai_score }}/100</strong>
                                        </span>
                                        <span class="text-gray-600">
                                            Confidence: <strong class="text-gray-900">{{ number_format($application->aiSievingDecision->ai_confidence * 100, 1) }}%</strong>
                                        </span>
                                    </div>
                                @endif

                                @if($application->aptitude_test_completed_at)
                                    <div class="mt-2 text-sm">
                                        <span class="text-gray-600">Aptitude Test: </span>
                                        <span class="font-semibold {{ $application->aptitude_test_passed ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }} ({{ $application->aptitude_test_score }}%)
                                        </span>
                                        <a href="{{ route('aptitude-test.results', $application) }}" class="ml-2 text-xs text-teal-600 hover:text-teal-800 underline">
                                            View details
                                        </a>
                                    </div>
                                @endif

                                @if($application->self_interview_completed_at)
                                    <div class="mt-1 text-sm">
                                        <span class="text-gray-600">Self Interview: </span>
                                        <span class="font-semibold {{ $application->self_interview_passed ? 'text-green-600' : 'text-amber-600' }}">
                                            @if($application->self_interview_passed)
                                                Passed
                                            @else
                                                Completed
                                            @endif
                                            @if(!is_null($application->self_interview_score))
                                                ({{ $application->self_interview_score }}%)
                                            @endif
                                        </span>
                                        <a href="{{ route('self-interview.results', $application) }}" class="ml-2 text-xs text-teal-600 hover:text-teal-800 underline">
                                            View details
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.application.show', $application) }}" 
                                   class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                    View Details
                                </a>
                                @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']) && !$application->aptitude_test_completed_at)
                                    <a href="{{ route('aptitude-test.show', $application) }}" 
                                       class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                                        Take Test
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $applications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Applications Yet</h3>
                <p class="text-gray-500 mb-6">You haven't submitted any job applications yet.</p>
                <a href="{{ route('careers.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                    Browse Available Positions
                </a>
            </div>
        @endif
    </div>
@endsection
