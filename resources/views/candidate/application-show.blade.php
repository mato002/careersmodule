@extends('layouts.candidate')

@section('title', 'Application Details')
@section('header-description', $application->jobPost->title ?? 'View application details')

@section('header-actions')
    <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-teal-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-white whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="hidden sm:inline">Back to Dashboard</span>
        <span class="sm:hidden">Back</span>
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        @include('candidate.application-status', ['application' => $application, 'token' => $token])
    </div>
@endsection

