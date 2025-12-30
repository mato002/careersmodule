@extends('layouts.admin')

@section('title', $post->title)
@section('header-description', 'View post details.')

@section('header-actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
            Edit
        </a>
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
            ← Back to Posts
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span>By {{ $post->author->name ?? 'Unknown' }}</span>
                @if($post->published_at)
                    <span>• Published {{ $post->published_at->format('M d, Y') }}</span>
                @endif
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                    {{ $post->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
        </div>

        @if($post->featured_image_path)
            <div class="mb-6">
                <img src="{{ asset('storage/'.$post->featured_image_path) }}" alt="{{ $post->title }}" class="w-full h-auto rounded-lg">
            </div>
        @endif

        @if($post->excerpt)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-lg text-gray-700 italic">{{ $post->excerpt }}</p>
            </div>
        @endif

        <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 whitespace-pre-wrap">{{ $post->content }}</div>
        </div>
    </div>
@endsection




