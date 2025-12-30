@extends('layouts.website')

@section('title', $post->title . ' - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900">
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex items-center justify-center text-sm text-teal-100 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <time datetime="{{ $post->published_at->toDateString() }}">
                        {{ $post->published_at->format('F d, Y') }}
                    </time>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 px-4">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="text-lg text-teal-100 px-4">{{ $post->excerpt }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Post Content -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                @if($post->featured_image_path)
                    <div class="mb-8">
                        <img 
                            src="{{ asset('storage/'.$post->featured_image_path) }}" 
                            alt="{{ $post->title }}"
                            class="w-full h-auto rounded-lg shadow-lg"
                        >
                    </div>
                @endif

                <article class="prose prose-lg max-w-none">
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </article>

                <!-- Back to News -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <a href="{{ route('posts.index') }}" class="inline-flex items-center text-teal-700 font-semibold hover:text-teal-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to News & Updates
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Posts -->
    @if($recentPosts->isNotEmpty())
        <section class="py-12 sm:py-16 bg-gray-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Recent Posts</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($recentPosts as $recentPost)
                            <article class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                                @if($recentPost->featured_image_path)
                                    <a href="{{ route('posts.show', $recentPost) }}">
                                        <img 
                                            src="{{ asset('storage/'.$recentPost->featured_image_path) }}" 
                                            alt="{{ $recentPost->title }}"
                                            class="w-full h-40 object-cover"
                                        >
                                    </a>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                        <a href="{{ route('posts.show', $recentPost) }}" class="hover:text-teal-700 transition-colors">
                                            {{ $recentPost->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
                                        <time datetime="{{ $recentPost->published_at->toDateString() }}">
                                            {{ $recentPost->published_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    @if($recentPost->excerpt)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $recentPost->excerpt }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection




