@extends('layouts.admin')

@section('title', $product->title)

@section('header-description', $product->category ?? 'General product details and assets.')

@section('header-actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
            ← Back
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
            Edit Product
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-500">Status</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                </span>
                <span class="text-sm text-gray-500">Display order: <span class="font-semibold text-gray-900">{{ $product->display_order }}</span></span>
                <span class="text-sm text-gray-500">Last updated {{ $product->updated_at->diffForHumans() }}</span>
            </div>

            <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-600">
                <div>
                    <p class="text-gray-500 uppercase tracking-wide text-xs font-semibold">Category</p>
                    <p class="mt-1 text-base text-gray-900">{{ $product->category ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 uppercase tracking-wide text-xs font-semibold">Slug</p>
                    <p class="mt-1 font-mono text-base text-gray-900">{{ $product->slug }}</p>
                </div>
                <div>
                    <p class="text-gray-500 uppercase tracking-wide text-xs font-semibold">Highlight color</p>
                    <div class="mt-1 flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 rounded-full border border-gray-200" style="background-color: {{ $product->highlight_color }};"></span>
                        <span class="font-mono text-base text-gray-900">{{ $product->highlight_color }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 uppercase tracking-wide text-xs font-semibold">CTA</p>
                    @if ($product->cta_label || $product->cta_link)
                        <div class="mt-1 space-y-1">
                            @if ($product->cta_label)
                                <p class="text-base text-gray-900 font-medium">{{ $product->cta_label }}</p>
                            @endif
                            @if ($product->cta_link)
                                <a href="{{ $product->cta_link }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline break-all">{{ $product->cta_link }}</a>
                            @endif
                        </div>
                    @else
                        <p class="mt-1 text-base text-gray-900">—</p>
                    @endif
                </div>
            </div>

            @if ($product->summary)
                <div class="space-y-2">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Summary</h2>
                    <p class="text-base text-gray-700">{{ $product->summary }}</p>
                </div>
            @endif

            @if ($product->description)
                <div class="space-y-2">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Detailed description</h2>
                    <div class="prose max-w-none text-gray-800">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif
        </div>

        @if ($product->images->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($product->images as $image)
                        <div class="relative border border-gray-100 rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/'.$image->path) }}" alt="" class="h-40 w-full object-cover">
                            @if ($image->is_primary)
                                <span class="absolute top-2 left-2 px-2 py-1 bg-teal-600 text-white text-xs font-semibold rounded-full">
                                    Primary
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

