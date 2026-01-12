@extends('layouts.admin')

@section('title', 'Candidate Details')

@section('header-description', 'View candidate profile and all their applications.')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $candidate->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $candidate->email }}</p>
                <p class="text-xs text-slate-400 mt-1">Member since {{ $candidate->created_at->format('M d, Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.candidates.edit', $candidate) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm">
                    Edit Candidate
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Back to list
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs -->
        @php
            $activeTab = request()->get('tab', 'applications');
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('admin.candidates.show', $candidate) }}?tab=applications" 
                       class="px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'applications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Applications
                    </a>
                    <a href="{{ route('admin.candidates.show', $candidate) }}?tab=biodata" 
                       class="px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ $activeTab === 'biodata' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Bio Data
                    </a>
                    <a href="{{ route('admin.candidates.documents', $candidate) }}" 
                       class="px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ request()->routeIs('admin.candidates.documents') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Documents
                    </a>
                    <a href="{{ route('admin.candidates.appraisals', $candidate) }}" 
                       class="px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ request()->routeIs('admin.candidates.appraisals*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        Appraisals
                    </a>
                </nav>
            </div>

            <div class="p-6">
                @if($activeTab === 'applications')
                    <!-- Applications Tab -->
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">Total</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 bg-green-50">
                            <p class="text-xs text-slate-600 mb-1">Passed</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['sieving_passed'] }}</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-4 bg-purple-50">
                            <p class="text-xs text-slate-600 mb-1">Hired</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-4 bg-red-50">
                            <p class="text-xs text-slate-600 mb-1">Rejected</p>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                        </div>
                    </div>

                    <!-- Applications by Company -->
        @foreach($applicationsByCompany as $companyName => $companyApplications)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ $companyName }} ({{ $companyApplications->count() }} {{ Str::plural('application', $companyApplications->count()) }})</h2>
                
                <div class="space-y-4">
                    @foreach($companyApplications as $application)
                        <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-semibold text-slate-900">
                                            <a href="{{ route('admin.job-applications.show', $application) }}" class="hover:text-blue-600">
                                                {{ $application->jobPost->title ?? 'Unknown Position' }}
                                            </a>
                                        </h3>
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold
                                            @if($application->status === 'hired') bg-purple-100 text-purple-800
                                            @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($application->status === 'sieving_passed' || $application->status === 'stage_2_passed') bg-green-100 text-green-800
                                            @elseif($application->status === 'shortlisted') bg-blue-100 text-blue-800
                                            @else bg-slate-100 text-slate-800
                                            @endif">
                                            {{ Str::headline(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600">
                                        <span>Applied: {{ $application->created_at->format('M d, Y') }}</span>
                                        @if($application->aptitude_test_passed)
                                            <span class="text-green-600 font-semibold">✓ Aptitude Test Passed</span>
                                        @endif
                                        @if($application->self_interview_passed)
                                            <span class="text-blue-600 font-semibold">✓ Self Interview Passed</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.job-applications.show', $application) }}" class="px-3 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors whitespace-nowrap">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

                    @if($applications->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-500 font-medium mb-2">No applications found</p>
                            <p class="text-slate-400 text-sm">This candidate hasn't submitted any job applications yet.</p>
                        </div>
                    @endif
                @elseif($activeTab === 'biodata')
                    <!-- Bio Data Tab -->
                    @include('admin.candidates.biodata')
                @endif
            </div>
        </div>
    </div>
@endsection


