@php
    $job = $job ?? null;
@endphp

<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Job Title <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $job->title ?? '') }}" required
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
        <textarea id="description" name="description" rows="6" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('description', $job->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
            <input type="text" id="department" name="department" value="{{ old('department', $job->department ?? '') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
        </div>

        <div>
            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location', $job->location ?? '') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="employment_type" class="block text-sm font-semibold text-gray-700 mb-2">Employment Type <span class="text-red-500">*</span></label>
            <select id="employment_type" name="employment_type" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                <option value="full-time" {{ old('employment_type', $job->employment_type ?? '') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                <option value="part-time" {{ old('employment_type', $job->employment_type ?? '') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                <option value="contract" {{ old('employment_type', $job->employment_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                <option value="internship" {{ old('employment_type', $job->employment_type ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
            </select>
        </div>

        <div>
            <label for="experience_level" class="block text-sm font-semibold text-gray-700 mb-2">Experience Level</label>
            <input type="text" id="experience_level" name="experience_level" value="{{ old('experience_level', $job->experience_level ?? '') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                   placeholder="e.g., Entry Level, Mid Level, Senior">
        </div>
    </div>

    <div>
        <label for="requirements" class="block text-sm font-semibold text-gray-700 mb-2">Requirements</label>
        <textarea id="requirements" name="requirements" rows="5"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('requirements', $job->requirements ?? '') }}</textarea>
    </div>

    <div>
        <label for="responsibilities" class="block text-sm font-semibold text-gray-700 mb-2">Key Responsibilities</label>
        <textarea id="responsibilities" name="responsibilities" rows="5"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('responsibilities', $job->responsibilities ?? '') }}</textarea>
    </div>

    <div>
        <label for="application_deadline" class="block text-sm font-semibold text-gray-700 mb-2">Application Deadline</label>
        <input type="date" id="application_deadline" name="application_deadline" value="{{ old('application_deadline', $job && $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
    </div>

    <div>
        <label class="flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $job->is_active ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-teal-800 focus:ring-teal-800">
            <span class="ml-2 text-sm text-gray-700">Active (visible on public site)</span>
        </label>
    </div>

    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.jobs.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-teal-800 text-white rounded-lg font-semibold hover:bg-teal-900">
            {{ $button }}
        </button>
    </div>
</div>

