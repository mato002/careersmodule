@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $inputClasses }}">
            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $inputClasses }}">
            @error('email')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
            <select name="role" required class="{{ $inputClasses }}">
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}" {{ old('role', $user->role ?? 'user') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('role')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Select the access level for this user.</p>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password @if(!$user->id)<span class="text-red-500">*</span>@else<span class="text-gray-400">(leave blank to keep current)</span>@endif
            </label>
            <input type="password" name="password" @if(!$user->id) required @endif class="{{ $inputClasses }}" autocomplete="new-password">
            @error('password')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Confirm Password @if(!$user->id)<span class="text-red-500">*</span>@endif
            </label>
            <input type="password" name="password_confirmation" @if(!$user->id) required @endif class="{{ $inputClasses }}" autocomplete="new-password">
            <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long.</p>
        </div>
        @if($user->id === auth()->id())
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-amber-800">Editing Your Own Account</p>
                        <p class="text-xs text-amber-700 mt-1">You cannot change your own role from Administrator. This prevents accidental lockout.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save User' }}
    </button>
</div>

