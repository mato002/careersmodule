@extends('layouts.website')

@section('title', '500 - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-700">
                500
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Internal Server Error
            </h2>
            <p class="text-lg text-gray-600 mb-6">
                We're sorry, but something went wrong on our end. Our team has been notified and is working to fix the issue.
            </p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-24 h-24 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('careers.index') }}" 
               class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Go to Homepage
            </a>
            <button onclick="window.location.reload()" 
                    class="px-6 py-3 bg-white text-teal-800 border-2 border-teal-800 rounded-lg hover:bg-teal-50 transition-colors font-semibold shadow-md hover:shadow-lg">
                Try Again
            </button>
        </div>

        <!-- Contact Information -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-4">If the problem persists, please contact us:</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 text-sm">
                <a href="tel:+254743838312" class="text-teal-700 hover:text-teal-800 font-medium">
                    📞 +254 743 838 312
                </a>
                <a href="mailto:info@fortresslenders.com" class="text-teal-700 hover:text-teal-800 font-medium">
                    📧 info@fortresslenders.com
                </a>
                <a href="{{ route('careers.index') }}" class="text-teal-700 hover:text-teal-800 font-medium">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

