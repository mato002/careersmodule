@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')

<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="{{ $inputClasses }}" placeholder="Enter post title">
        @error('title')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" class="{{ $inputClasses }}" placeholder="auto-generated-from-title">
        <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title</p>
        @error('slug')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="3" class="{{ $inputClasses }}" placeholder="Short summary of the post...">{{ old('excerpt', $post->excerpt) }}</textarea>
        @error('excerpt')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
        <textarea name="content" rows="12" required class="{{ $inputClasses }}" placeholder="Write your post content here...">{{ old('content', $post->content) }}</textarea>
        @error('content')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
        <input type="file" name="featured_image" class="block w-full text-sm text-gray-700">
        <p class="text-xs text-gray-500 mt-1">Upload JPG/PNG up to 5 MB.</p>
        @error('featured_image')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror

        @if ($post->featured_image_path)
            <img src="{{ asset('storage/'.$post->featured_image_path) }}" alt="{{ $post->title }}" class="mt-4 h-48 w-auto object-cover rounded-xl border border-gray-100">
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" class="{{ $inputClasses }}">
            @error('published_at')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3 mt-6">
            <input type="checkbox" name="is_published" value="1" class="h-5 w-5 text-teal-600 border-gray-300 rounded" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Publish this post</span>
        </div>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save Post' }}
    </button>
</div>




