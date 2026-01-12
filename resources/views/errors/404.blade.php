@php
    $layout = $layout ?? 'website';
    $layoutMap = [
        'admin' => 'layouts.admin',
        'candidate' => 'layouts.candidate',
        'website' => 'layouts.website',
    ];
    $selectedLayout = $layoutMap[$layout] ?? 'layouts.website';
@endphp
@extends($selectedLayout)

@section('title', 'Page Not Found - Fortress Lenders Ltd')
@section('meta_description', 'The page you are looking for could not be found. Visit Fortress Lenders home page, apply for a loan, or contact our team for assistance.')

@section('content')
    <section class="min-h-[70vh] flex items-center bg-gradient-to-br from-teal-900 via-teal-800 to-gray-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-3xl mx-auto text-center space-y-6 sm:space-y-8">
                <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/10 border border-white/20 shadow-lg mb-2">
                    <span class="text-3xl sm:text-4xl font-bold tracking-tight">404</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">
                    Page Not Found
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-teal-100 px-4">
                    The page you were looking for doesn’t exist or may have moved.
                    Let’s help you get back to what matters.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-4 sm:mt-6">
                    @if($layout === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white text-teal-900 font-semibold text-sm sm:text-base shadow-md hover:bg-teal-50 transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.job-applications.index') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-amber-500 text-white font-semibold text-sm sm:text-base shadow-md hover:bg-amber-600 transition-colors">
                            Job Applications
                        </a>
                        <button onclick="window.history.back()"
                                class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white/10 border border-white/20 text-white font-semibold text-sm sm:text-base hover:bg-white/15 transition-colors">
                            Go Back
                        </button>
                    @elseif($layout === 'candidate')
                        <a href="{{ route('candidate.dashboard') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white text-teal-900 font-semibold text-sm sm:text-base shadow-md hover:bg-teal-50 transition-colors">
                            My Dashboard
                        </a>
                        <a href="{{ route('careers.index') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-amber-500 text-white font-semibold text-sm sm:text-base shadow-md hover:bg-amber-600 transition-colors">
                            View Careers
                        </a>
                        <button onclick="window.history.back()"
                                class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white/10 border border-white/20 text-white font-semibold text-sm sm:text-base hover:bg-white/15 transition-colors">
                            Go Back
                        </button>
                    @else
                        <a href="{{ route('careers.index') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white text-teal-900 font-semibold text-sm sm:text-base shadow-md hover:bg-teal-50 transition-colors">
                            Home
                        </a>
                        <a href="{{ route('careers.index') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-amber-500 text-white font-semibold text-sm sm:text-base shadow-md hover:bg-amber-600 transition-colors">
                            View Careers
                        </a>
                        @if(Route::has('contact'))
                        <a href="{{ route('contact') }}"
                           class="px-4 py-3 sm:px-5 sm:py-4 rounded-xl bg-white/10 border border-white/20 text-white font-semibold text-sm sm:text-base hover:bg-white/15 transition-colors">
                            Contact Us
                        </a>
                        @endif
                    @endif
                </div>

                <p class="text-[11px] sm:text-xs text-teal-100/80 mt-4">
                    If you typed the web address, please check it is correct. If you clicked a link,
                    it may be outdated – you can always reach us through the contact page.
                </p>
            </div>
        </div>
    </section>
@endsection



