@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="name">Branch Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $branch->name) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
        @error('name')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="city">City / Town</label>
        <input type="text" name="city" id="city" value="{{ old('city', $branch->city) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        @error('city')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="address_line1">Address Line 1</label>
        <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1', $branch->address_line1) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
        @error('address_line1')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="address_line2">Address Line 2</label>
        <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2', $branch->address_line2) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        @error('address_line2')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="phone_primary">Primary Phone</label>
        <input type="text" name="phone_primary" id="phone_primary" value="{{ old('phone_primary', $branch->phone_primary) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        @error('phone_primary')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="phone_secondary">Secondary Phone</label>
        <input type="text" name="phone_secondary" id="phone_secondary" value="{{ old('phone_secondary', $branch->phone_secondary) }}" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        @error('phone_secondary')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="accent_color">Accent Color</label>
        <select name="accent_color" id="accent_color" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            @foreach (['teal' => 'Teal', 'amber' => 'Amber', 'green' => 'Green', 'purple' => 'Purple'] as $value => $label)
                <option value="{{ $value }}" @selected(old('accent_color', $branch->accent_color) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('accent_color')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-teal-900 mb-1" for="display_order">Display Order</label>
        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $branch->display_order) }}" min="0" class="w-full rounded-xl border border-teal-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        @error('display_order')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="flex items-center gap-3 md:col-span-2">
        <input type="checkbox" name="is_active" id="is_active" class="rounded border-teal-200 text-teal-600 focus:ring-teal-500" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-semibold text-teal-900">Show on website</label>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.branches.index') }}" class="px-5 py-2 rounded-xl border border-teal-200 text-teal-700 font-semibold">Cancel</a>
    <button type="submit" class="px-5 py-2 rounded-xl bg-teal-700 text-white font-semibold hover:bg-teal-800">
        {{ $submitLabel ?? 'Save Branch' }}
    </button>
</div>

