@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $ceoMessage->name) }}" required class="{{ $inputClasses }}" placeholder="CEO Name">
            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
            <input type="text" name="position" value="{{ old('position', $ceoMessage->position) }}" class="{{ $inputClasses }}" placeholder="CEO / Managing Director">
            @error('position')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message Title</label>
            <input type="text" name="title" value="{{ old('title', $ceoMessage->title) }}" class="{{ $inputClasses }}" placeholder="Welcome Message">
            @error('title')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
            <input type="file" name="image" class="block w-full text-sm text-gray-700">
            <p class="text-xs text-gray-500 mt-1">Upload JPG/PNG up to 5 MB.</p>
            @error('image')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror

            @if ($ceoMessage->image_path)
                <img src="{{ asset('storage/'.$ceoMessage->image_path) }}" alt="{{ $ceoMessage->name }}" class="mt-4 h-32 w-32 object-cover rounded-xl border border-gray-100">
            @endif
        </div>
        <div class="flex items-center gap-3 mt-6">
            <input type="checkbox" name="is_active" value="1" class="h-5 w-5 text-teal-600 border-gray-300 rounded" {{ old('is_active', $ceoMessage->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Visible on website</span>
        </div>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
    <textarea name="message" rows="10" required class="{{ $inputClasses }}" placeholder="Write the CEO/Director message here...">{{ old('message', $ceoMessage->message) }}</textarea>
    @error('message')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.ceo-messages.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save Message' }}
    </button>
</div>




