@extends('layouts.website')

@section('title', 'CEO Message - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900">
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Message from Leadership</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">A message from our leadership team</p>
        </div>
    </section>

    <!-- CEO Message Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                @if($ceoMessage)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                        <div class="md:flex">
                            @if($ceoMessage->image_path)
                                <div class="md:w-1/3 bg-gradient-to-br from-teal-700 to-teal-800 p-8 flex items-center justify-center">
                                    <img 
                                        src="{{ asset('storage/'.$ceoMessage->image_path) }}" 
                                        alt="{{ $ceoMessage->name }}"
                                        class="w-full h-auto rounded-lg shadow-lg"
                                    >
                                </div>
                            @else
                                <div class="md:w-1/3 bg-gradient-to-br from-teal-700 to-teal-800 p-8 flex items-center justify-center">
                                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            <div class="md:w-2/3 p-8">
                                @if($ceoMessage->title)
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $ceoMessage->title }}</h2>
                                @endif
                                <div class="mb-6">
                                    <h3 class="text-xl font-semibold text-teal-700 mb-1">{{ $ceoMessage->name }}</h3>
                                    @if($ceoMessage->position)
                                        <p class="text-gray-600">{{ $ceoMessage->position }}</p>
                                    @endif
                                </div>
                                <div class="prose prose-lg max-w-none text-gray-700">
                                    {!! nl2br(e($ceoMessage->message)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-gray-600 text-lg">No message available at the moment. Please check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 bg-gradient-to-br from-teal-700 to-teal-800 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4">Ready to get started?</h2>
                <p class="text-lg text-teal-100 mb-6">Contact us today to learn more about our services.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:{{ $generalSettings->company_email ?? 'info@example.com' }}" class="inline-block px-6 py-3 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-colors">
                        Contact Us
                    </a>
                    <a href="{{ route('products') }}" class="inline-block px-6 py-3 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-colors">
                        View Products
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection




