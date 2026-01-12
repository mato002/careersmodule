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
                    @if($application->status === 'hired') bg-purple-100 text-purple-800
                    @elseif($application->status === 'stage_2_passed') bg-blue-100 text-blue-800
                    @elseif($application->status === 'sieving_passed') bg-green-100 text-green-800
                    @elseif($application->status === 'sieving_rejected') bg-red-100 text-red-800
                    @elseif($application->status === 'pending_manual_review') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- AI Sieving Decision -->
    @if($application->aiSievingDecision)
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">AI Sieving Results</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">AI Score</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $application->aiSievingDecision->ai_score }}/100</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Confidence</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($application->aiSievingDecision->ai_confidence * 100, 1) }}%</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Decision</p>
                    <p class="text-lg font-semibold 
                        @if($application->aiSievingDecision->ai_decision === 'pass') text-green-600
                        @elseif($application->aiSievingDecision->ai_decision === 'reject') text-red-600
                        @else text-yellow-600
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->aiSievingDecision->ai_decision)) }}
                    </p>
                </div>
            </div>
            @if($application->aiSievingDecision->ai_reasoning)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">AI Reasoning</p>
                    <p class="text-sm text-blue-800 whitespace-pre-line">{{ $application->aiSievingDecision->ai_reasoning }}</p>
                </div>
            @endif
            @if($application->aiSievingDecision->ai_strengths && count($application->aiSievingDecision->ai_strengths) > 0)
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-900 mb-2">Strengths</p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                        @foreach($application->aiSievingDecision->ai_strengths as $strength)
                            <li>{{ $strength }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($application->aiSievingDecision->ai_weaknesses && count($application->aiSievingDecision->ai_weaknesses) > 0)
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-900 mb-2">Areas for Improvement</p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                        @foreach($application->aiSievingDecision->ai_weaknesses as $weakness)
                            <li>{{ $weakness }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Aptitude Test Section -->
    @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']))
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Aptitude Test</h2>
            @if($application->aptitude_test_completed_at)
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Test Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $application->aptitude_test_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Score</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $application->aptitude_test_score }}%</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Completed On</p>
                            <p class="font-semibold text-gray-900">{{ $application->aptitude_test_completed_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($application->aptitudeTestSession && $application->aptitudeTestSession->time_taken_seconds)
                            <div>
                                <p class="text-gray-500">Time Taken</p>
                                <p class="font-semibold text-gray-900">{{ gmdate("H:i:s", $application->aptitudeTestSession->time_taken_seconds) }}</p>
                            </div>
                        @endif
                    </div>
                    @if($application->aptitudeTestSession)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('aptitude-test.results', $application) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                View Detailed Results
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
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

    <!-- Application Timeline -->
    <div class="border-b border-gray-200 pb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Application Timeline</h2>
        <div class="space-y-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-teal-600 mt-2"></div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Application Submitted</p>
                    <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            @if($application->aiSievingDecision)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full {{ $application->aiSievingDecision->ai_decision === 'pass' ? 'bg-green-600' : ($application->aiSievingDecision->ai_decision === 'reject' ? 'bg-red-600' : 'bg-yellow-600') }} mt-2"></div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">AI Sieving Completed</p>
                        <p class="text-sm text-gray-500">{{ $application->aiSievingDecision->created_at->format('M d, Y H:i') }}</p>
                        <p class="text-sm text-gray-600 mt-1">Decision: {{ ucfirst(str_replace('_', ' ', $application->aiSievingDecision->ai_decision)) }}</p>
                    </div>
                </div>
            @endif
            @if($application->aptitude_test_completed_at)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full {{ $application->aptitude_test_passed ? 'bg-green-600' : 'bg-red-600' }} mt-2"></div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Aptitude Test Completed</p>
                        <p class="text-sm text-gray-500">{{ $application->aptitude_test_completed_at->format('M d, Y H:i') }}</p>
                        <p class="text-sm text-gray-600 mt-1">Result: {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }} ({{ $application->aptitude_test_score }}%)</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Next Steps Section -->
    @if($application->aptitude_test_passed)
        @php
            // Load interviews with eager loading
            $application->load('interviews');
            
            // Check for upcoming interviews/assessments
            $upcomingNextStage = $application->interviews
                ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                ->whereNull('result')
                ->where('scheduled_at', '>', now())
                ->sortBy('scheduled_at')
                ->first();
            
            // Check for completed first interview
            $completedFirstInterview = $application->interviews
                ->whereIn('interview_type', ['first', 'online_interview'])
                ->whereNotNull('result')
                ->sortByDesc('scheduled_at')
                ->first();
            
            // Check for completed next stage
            $completedNextStage = $application->interviews
                ->whereIn('interview_type', ['second', 'written_test', 'case_study'])
                ->whereNotNull('result')
                ->sortByDesc('scheduled_at')
                ->first();
        @endphp
        
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Next Steps</h2>
            
            @if($upcomingNextStage)
                @php
                    $stageLabels = [
                        'second' => 'Second Interview',
                        'written_test' => 'Written Test',
                        'case_study' => 'Case Study Assessment'
                    ];
                    $stageLabel = $stageLabels[$upcomingNextStage->interview_type] ?? ucfirst(str_replace('_', ' ', $upcomingNextStage->interview_type));
                @endphp
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-purple-900 mb-2">📅 {{ $stageLabel }} Scheduled</h3>
                            <p class="text-sm text-purple-800 mb-3">
                                Great progress! You've successfully passed both the aptitude test and first interview. 
                                Your {{ strtolower($stageLabel) }} has been scheduled.
                            </p>
                            <div class="bg-white rounded-lg p-4 border border-purple-300">
                                <p class="text-sm font-semibold text-purple-900 mb-2">📅 Schedule Details</p>
                                <p class="text-sm text-purple-800">
                                    <strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($upcomingNextStage->scheduled_at)->format('l, F d, Y \a\t g:i A') }}
                                </p>
                                @if($upcomingNextStage->location)
                                    <p class="text-sm text-purple-800 mt-2">
                                        <strong>Location:</strong> {{ $upcomingNextStage->location }}
                                    </p>
                                @endif
                                @if($upcomingNextStage->notes)
                                    <p class="text-sm text-purple-800 mt-2">
                                        <strong>Additional Information:</strong><br>
                                        <span class="text-purple-700">{{ $upcomingNextStage->notes }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($completedNextStage && $completedNextStage->result === 'pass')
                @if($completedNextStage->interview_type === 'second')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-green-900 mb-2">🎉 Second Interview Passed</h3>
                                <p class="text-sm text-green-800">
                                    Outstanding! You've successfully passed the second interview. Our team is finalizing the hiring decision. 
                                    <strong>You will be contacted shortly with the final outcome.</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-green-900 mb-2">✅ Assessment Passed</h3>
                                <p class="text-sm text-green-800">
                                    Great work! You've successfully completed and passed the assessment. 
                                    Our HR team is reviewing all your results and will contact you with next steps, which may include a final interview.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif(in_array($application->status, ['stage_2_passed', 'interview_passed']))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-green-900 mb-3">🎉 Stage 2 Complete - What's Next?</h3>
                            <p class="text-sm text-green-800 mb-4">
                                Congratulations! You've successfully passed both the <strong>aptitude test</strong> and <strong>first interview</strong>. 
                                You've reached Stage 2 of the hiring process.
                            </p>
                            
                            <div class="bg-white rounded-lg p-4 border border-green-300 mb-4">
                                <p class="text-sm font-semibold text-green-900 mb-3">📧 What Happens Next?</p>
                                <ul class="text-sm text-green-800 space-y-2 list-disc list-inside">
                                    <li>Our HR team is currently reviewing your complete application profile</li>
                                    <li>You may be invited for one of the following:</li>
                                    <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                                        <li>A <strong>Second Interview</strong> (in-person or video call)</li>
                                        <li>A <strong>Written Test</strong> or <strong>Case Study</strong> assessment</li>
                                        <li>Final decision on your application</li>
                                    </ul>
                                    <li class="font-semibold mt-3">You will receive an email notification when the next step is scheduled</li>
                                    <li>Please check your email regularly for updates</li>
                                </ul>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm font-semibold text-blue-900 mb-2">💡 Current Progress</p>
                                <div class="flex flex-wrap gap-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-green-800 font-medium">Aptitude Test: Passed ({{ $application->aptitude_test_score }}%)</span>
                                    </div>
                                    @if($completedFirstInterview && $completedFirstInterview->result === 'pass')
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-green-800 font-medium">First Interview: Passed</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(in_array($application->status, ['second_interview', 'written_test', 'case_study']))
                @php
                    $statusLabels = [
                        'second_interview' => 'Second Interview',
                        'written_test' => 'Written Test',
                        'case_study' => 'Case Study'
                    ];
                    $currentStageLabel = $statusLabels[$application->status] ?? ucfirst(str_replace('_', ' ', $application->status));
                @endphp
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-blue-900 mb-2">⏳ {{ $currentStageLabel }} - Awaiting Scheduling</h3>
                            <p class="text-sm text-blue-800">
                                Your application is at the {{ strtolower($currentStageLabel) }} stage. 
                                Our HR team will contact you with scheduling details soon. Please check your email regularly for updates.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Actions -->
    <div class="pt-6 space-y-3">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-semibold">
                ← Back to Dashboard
            </a>
            @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']) && !$application->aptitude_test_completed_at)
                <a href="{{ route('aptitude-test.show', $application) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                    Take Aptitude Test
                </a>
            @endif
            @if($application->aptitude_test_passed && !$application->self_interview_completed_at && !in_array($application->status, ['stage_2_passed', 'hired', 'sieving_rejected']))
                <a href="{{ route('self-interview.show', $application) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                    Start Self Interview
                </a>
            @endif
        </div>
    </div>
</div>

