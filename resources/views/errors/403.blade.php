@extends('layouts.website')

@section('title', '403 - Forbidden')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-orange-700">
                403
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Access Forbidden
            </h2>
            <p class="text-lg text-gray-600 mb-6">
                You don't have permission to access this resource.
            </p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-48 h-48 bg-orange-100 rounded-full flex items-center justify-center">
                <svg class="w-24 h-24 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('careers.index') }}" 
               class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Go to Homepage
            </a>
            <button onclick="window.history.back()" 
                    class="px-6 py-3 bg-white text-teal-800 border-2 border-teal-800 rounded-lg hover:bg-teal-50 transition-colors font-semibold shadow-md hover:shadow-lg">
                Go Back
            </button>
        </div>
    </div>
</div>
@endsection

