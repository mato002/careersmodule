@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')

<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Question <span class="text-red-500">*</span></label>
        <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="{{ $inputClasses }}" placeholder="What are the requirements for a loan?">
        @error('question')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Answer <span class="text-red-500">*</span></label>
        <textarea name="answer" rows="6" required class="{{ $inputClasses }}" placeholder="Provide a detailed answer...">{{ old('answer', $faq->answer) }}</textarea>
        @error('answer')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
            <input type="number" name="display_order" min="0" value="{{ old('display_order', $faq->display_order) }}" class="{{ $inputClasses }}">
            @error('display_order')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-3 mt-6">
            <input type="checkbox" name="is_active" value="1" class="h-5 w-5 text-teal-600 border-gray-300 rounded" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Visible on website</span>
        </div>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save FAQ' }}
    </button>
</div>




