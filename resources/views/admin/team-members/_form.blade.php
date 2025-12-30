@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $teamMember->name) }}" required class="{{ $inputClasses }}">
            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role / Title</label>
            <input type="text" name="role" value="{{ old('role', $teamMember->role) }}" class="{{ $inputClasses }}">
            @error('role')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Short Bio</label>
            <textarea name="bio" rows="6" class="{{ $inputClasses }}">{{ old('bio', $teamMember->bio) }}</textarea>
            @error('bio')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $teamMember->email) }}" class="{{ $inputClasses }}">
            @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $teamMember->phone) }}" class="{{ $inputClasses }}">
            @error('phone')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $teamMember->linkedin_url) }}" class="{{ $inputClasses }}">
            @error('linkedin_url')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="display_order" min="1" value="{{ old('display_order', $teamMember->display_order) }}" class="{{ $inputClasses }}">
            </div>
            <div class="flex items-center gap-3 mt-6">
                <input type="checkbox" name="is_active" value="1" class="h-5 w-5 text-teal-600 border-gray-300 rounded" {{ old('is_active', $teamMember->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Visible on website</span>
            </div>
        </div>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">Headshot</label>
    <input type="file" name="photo" class="block w-full text-sm text-gray-700">
    <p class="text-xs text-gray-500 mt-1">Upload JPG/PNG up to 4 MB.</p>
    @error('photo')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror

    @if ($teamMember->photo_path)
        <img src="{{ asset('storage/'.$teamMember->photo_path) }}" alt="{{ $teamMember->name }}" class="mt-4 h-32 w-32 object-cover rounded-xl border border-gray-100">
    @endif
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save Member' }}
    </button>
</div>







