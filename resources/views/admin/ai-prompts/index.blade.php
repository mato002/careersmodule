@extends('layouts.admin')

@section('title', 'AI Prompt Settings')

@section('header-description', 'View and manage AI prompts used for CV analysis and application evaluation. Customize prompts per role.')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header with Role Selector -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Prompt Settings</h1>
                <p class="text-gray-600 mt-1">Customize AI prompts for different roles. Each role can have its own set of prompts.</p>
            </div>
            <div class="flex items-center gap-4">
                <div>
                    <label for="role-select" class="block text-sm font-medium text-gray-700 mb-2">Select Role:</label>
                    <select id="role-select" onchange="window.location.href='{{ route('admin.ai-prompts.index') }}?role=' + this.value" class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @foreach($roles as $roleKey => $roleName)
                            <option value="{{ $roleKey }}" {{ $selectedRole === $roleKey ? 'selected' : '' }}>{{ $roleName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Model: {{ config('ai.model', 'gpt-4o-mini') }} | Temperature: 0.3</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-teal-50 border border-teal-200 text-teal-900 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Prompts List -->
    <div class="space-y-6">
        @foreach($prompts as $key => $prompt)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Prompt Header -->
            <div class="bg-gradient-to-r from-teal-50 to-blue-50 border-b border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-teal-100 rounded-lg text-teal-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $prompt['name'] }}</h2>
                                @if(isset($prompt['stored_id']))
                                    <span class="text-xs text-teal-600 bg-teal-100 px-2 py-1 rounded-full mt-1 inline-block">
                                        Customized (v{{ $prompt['version'] ?? 1 }})
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full mt-1 inline-block">
                                        Default
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">{{ $prompt['description'] }}</p>
                        <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $prompt['when_used'] }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-mono text-xs">{{ $prompt['location'] }}</span>
                            </div>
                            @if(isset($prompt['updated_at']))
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Updated: {{ \Carbon\Carbon::parse($prompt['updated_at'])->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prompt Content Form -->
            <form method="POST" action="{{ route('admin.ai-prompts.update') }}" class="p-6">
                @csrf
                <input type="hidden" name="prompt_type" value="{{ $key }}">
                <input type="hidden" name="role" value="{{ $selectedRole }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prompt Content
                        <span class="text-xs font-normal text-gray-500 ml-2">
                            ({{ number_format(strlen($prompt['content'])) }} characters)
                        </span>
                    </label>
                    <textarea 
                        name="content"
                        required
                        minlength="10"
                        class="w-full h-64 p-4 bg-gray-50 border border-gray-200 rounded-lg font-mono text-sm text-gray-800 resize-y focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        id="prompt-{{ $key }}"
                    >{{ old('content', $prompt['content']) }}</textarea>
                    @error('content')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description-{{ $key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description (Optional)
                    </label>
                    <input 
                        type="text"
                        name="description"
                        id="description-{{ $key }}"
                        value="{{ old('description', $prompt['description'] ?? '') }}"
                        maxlength="500"
                        class="w-full p-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Brief description of what this prompt does..."
                    >
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-semibold text-sm"
                        >
                            💾 Save Prompt
                        </button>
                        @if(isset($prompt['stored_id']))
                        <form method="POST" action="{{ route('admin.ai-prompts.reset') }}" class="inline">
                            @csrf
                            <input type="hidden" name="prompt_type" value="{{ $key }}">
                            <input type="hidden" name="role" value="{{ $selectedRole }}">
                            <button 
                                type="submit"
                                onclick="return confirm('Are you sure you want to reset this prompt to default? This will delete your custom version.')"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-sm"
                            >
                                🔄 Reset to Default
                            </button>
                        </form>
                        @endif
                    </div>
                    <button 
                        type="button"
                        onclick="copyToClipboard('prompt-{{ $key }}')"
                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600 hover:text-teal-600 text-sm"
                        title="Copy to clipboard"
                    >
                        📋 Copy
                    </button>
                </div>
            </form>

            <!-- Key Features (for application_analysis) -->
            @if($key === 'application_analysis')
            <div class="px-6 pb-6">
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <h3 class="text-sm font-semibold text-amber-900 mb-2">Key Features of This Prompt:</h3>
                    <ul class="space-y-1 text-xs text-amber-800">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Detects gibberish and meaningless responses</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Validates CV content and compares with form data</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Provides strict scoring guidelines (0-100)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Only lists meaningful strengths verified in CV</span>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-blue-100 rounded-lg text-blue-700 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">About AI Prompts</h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Role-Based Prompts:</strong> Each role (Admin, HR Manager, Client) can have its own customized prompts. If no custom prompt exists for a role, the default prompt will be used.</p>
                    <p><strong>Prompt Variables:</strong> You can use placeholders like <code class="bg-blue-100 px-1 rounded">{job_post->title}</code>, <code class="bg-blue-100 px-1 rounded">{application->name}</code>, etc. These will be replaced with actual data when the prompt is used.</p>
                    <p><strong>Versioning:</strong> Each time you save a prompt, a new version is created. You can reset to default at any time.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const textarea = document.getElementById(elementId);
    textarea.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '✓ Copied';
    button.classList.add('text-teal-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('text-teal-600');
    }, 2000);
}
</script>
@endsection
