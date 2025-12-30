@extends('layouts.website')

@section('title', '503 - Service Unavailable')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-purple-700">
                503
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Service Unavailable
            </h2>
            <p class="text-lg text-gray-600 mb-6">
                We're currently performing maintenance. Please check back soon.
            </p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-24 h-24 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button onclick="window.location.reload()" 
                    class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Try Again
            </button>
            <a href="{{ route('careers.index') }}" 
               class="px-6 py-3 bg-white text-teal-800 border-2 border-teal-800 rounded-lg hover:bg-teal-50 transition-colors font-semibold shadow-md hover:shadow-lg">
                Go to Homepage
            </a>
        </div>

        <!-- Contact Information -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-4">If you need immediate assistance:</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 text-sm">
                <a href="tel:+254743838312" class="text-teal-700 hover:text-teal-800 font-medium">
                    📞 +254 743 838 312
                </a>
                <a href="mailto:info@fortresslenders.com" class="text-teal-700 hover:text-teal-800 font-medium">
                    📧 info@fortresslenders.com
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

