@extends('layouts.website')

@section('title', '419 - Page Expired')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-yellow-700">
                419
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Page Expired
            </h2>
            <p class="text-lg text-gray-600 mb-6">
                Your session has expired. Please refresh the page and try again.
            </p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-24 h-24 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.location.reload()" 
                    class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Refresh Page
            </button>
            <a href="{{ route('careers.index') }}" 
               class="px-6 py-3 bg-white text-teal-800 border-2 border-teal-800 rounded-lg hover:bg-teal-50 transition-colors font-semibold shadow-md hover:shadow-lg">
                Go to Homepage
            </a>
        </div>
    </div>
</div>
@endsection

