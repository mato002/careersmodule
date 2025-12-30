@extends('layouts.website')

@section('title', 'News & Updates - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900">
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">News & Updates</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">Stay informed with our latest news, tips, and announcements</p>
        </div>
    </section>

    <!-- Posts Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            @if($posts->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-gray-600 text-lg">No posts available at the moment. Please check back later.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($posts as $post)
                        <article class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                            @if($post->featured_image_path)
                                <a href="{{ route('posts.show', $post) }}">
                                    <img 
                                        src="{{ asset('storage/'.$post->featured_image_path) }}" 
                                        alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover"
                                    >
                                </a>
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-teal-700 to-teal-800 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <time datetime="{{ $post->published_at->toDateString() }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:text-teal-700 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                @if($post->excerpt)
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                @else
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                                @endif
                                <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center text-teal-700 font-semibold hover:text-teal-800">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

