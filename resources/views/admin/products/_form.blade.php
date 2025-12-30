@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="{{ $inputClasses }}">
            @error('title')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $product->category) }}" class="{{ $inputClasses }}">
            @error('category')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
            <textarea name="summary" rows="4" class="{{ $inputClasses }}">{{ old('summary', $product->summary) }}</textarea>
            @error('summary')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Highlight Color</label>
            <input type="text" name="highlight_color" value="{{ old('highlight_color', $product->highlight_color) }}" required class="{{ $inputClasses }}">
            @error('highlight_color')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Label</label>
                <input type="text" name="cta_label" value="{{ old('cta_label', $product->cta_label) }}" class="{{ $inputClasses }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Link</label>
                <input type="url" name="cta_link" value="{{ old('cta_link', $product->cta_link) }}" class="{{ $inputClasses }}">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="6" class="{{ $inputClasses }}">{{ old('description', $product->description) }}</textarea>
            @error('description')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', $product->display_order) }}" class="{{ $inputClasses }}">
    </div>
    <div class="flex items-center space-x-3 mt-2">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-5 w-5 text-teal-600 border-gray-300 rounded">
        <span class="text-sm text-gray-700">Visible on website</span>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
    <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-700">
    <p class="text-xs text-gray-500 mt-1">You can select multiple images (JPG, PNG, max 4 MB each).</p>
    @error('images.*')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div class="mt-8 flex justify-end space-x-3">
    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save Product' }}
    </button>
</div>

@push('styles')
    <style>
        .input-field {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent;
        }
    </style>
@endpush

