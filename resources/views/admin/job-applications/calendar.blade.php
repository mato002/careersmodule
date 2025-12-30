@extends('layouts.admin')

@section('title', 'Interview Calendar')

@section('header-description', 'View and manage scheduled interviews.')

@section('header-actions')
    <a href="{{ route('admin.job-applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Applications
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.job-applications.calendar') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date', now()->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->addDays(30)->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interview Type</label>
                    <select name="interview_type" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">All Types</option>
                        <option value="first" {{ request('interview_type') === 'first' ? 'selected' : '' }}>First Interview</option>
                        <option value="second" {{ request('interview_type') === 'second' ? 'selected' : '' }}>Second Interview</option>
                        <option value="written_test" {{ request('interview_type') === 'written_test' ? 'selected' : '' }}>Written Test</option>
                        <option value="case_study" {{ request('interview_type') === 'case_study' ? 'selected' : '' }}>Case Study</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Result</label>
                    <select name="result" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">All Results</option>
                        <option value="pending" {{ request('result') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="pass" {{ request('result') === 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ request('result') === 'fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                <a href="{{ route('admin.job-applications.calendar') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                    Clear
                </a>
            </div>
        </div>
    </form>

    <!-- Calendar Grid View -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Calendar</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        View all scheduled interviews on a calendar. Click an event to open the application.
                    </p>
                </div>
            </div>
            <div id="interview-calendar"></div>
        </div>
    </div>

    <!-- List View -->
    <div class="space-y-6">
        @forelse($calendar as $date => $dayInterviews)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        {{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}
                    </h3>
                    <p class="text-teal-100 text-sm mt-1">
                        {{ $dayInterviews->count() }} interview{{ $dayInterviews->count() !== 1 ? 's' : '' }} scheduled
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($dayInterviews as $interview)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            @php
                                                $application = $interview->application;

                                                // Derive an effective result so calendar stays in sync with application status.
                                                // Treat explicit "pass"/"fail" on the interview as the source of truth,
                                                // but if it's missing or still "pending", fall back to the application status.
                                                $effectiveResult = $interview->result;

                                                if (! $effectiveResult || $effectiveResult === 'pending') {
                                                    if ($application) {
                                                        $status = $application->status;
                                                        if (in_array($status, ['interview_passed', 'hired'])) {
                                                            $effectiveResult = 'pass';
                                                        } elseif (in_array($status, ['interview_failed', 'rejected'])) {
                                                            $effectiveResult = 'fail';
                                                        } else {
                                                            $effectiveResult = 'pending';
                                                        }
                                                    } else {
                                                        $effectiveResult = 'pending';
                                                    }
                                                }
                                            @endphp
                                            <h4 class="font-semibold text-gray-900">
                                                {{ $application->name ?? 'Unknown Applicant' }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ match($interview->interview_type) {
                                                'first' => 'bg-blue-100 text-blue-800',
                                                'second' => 'bg-purple-100 text-purple-800',
                                                'written_test' => 'bg-amber-100 text-amber-800',
                                                'case_study' => 'bg-indigo-100 text-indigo-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            } }}">
                                                {{ Str::headline(str_replace('_', ' ', $interview->interview_type)) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ match($effectiveResult) {
                                                'pass' => 'bg-green-100 text-green-800',
                                                'fail' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            } }}">
                                                {{ Str::headline($effectiveResult ?: 'pending') }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p><strong>Position:</strong> {{ optional($application?->jobPost)->title ?? 'N/A' }}</p>
                                            <p><strong>Time:</strong> {{ $interview->scheduled_at->format('g:i A') }}</p>
                                            @if($interview->location)
                                                <p><strong>Location:</strong> {{ $interview->location }}</p>
                                            @endif
                                            @if($interview->notes)
                                                <p><strong>Notes:</strong> {{ $interview->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($application)
                                        <div class="ml-4">
                                            <a href="{{ route('admin.job-applications.show', $application) }}" 
                                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 text-teal-800 rounded-lg hover:bg-teal-100 font-semibold text-sm transition">
                                                View Application
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Interviews Scheduled</h3>
                <p class="text-gray-600">No interviews are scheduled for the selected date range.</p>
            </div>
            @endforelse
    </div>

    <!-- FullCalendar (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('interview-calendar');
            if (!calendarEl || typeof FullCalendar === 'undefined') return;

            const events = @json($events);

            const initialDate = "{{ request('start_date', now()->toDateString()) }}";

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: initialDate,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                height: 'auto',
                dayMaxEvents: true,
                events: events,
                eventClick: function (info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
            });

            calendar.render();
        });
    </script>

@endsection

