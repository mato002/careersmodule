@extends('layouts.admin')

@section('title', 'Document Templates')

@section('header-description', 'Manage global document templates for offer letters and contracts')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Document Templates</h1>
                <p class="text-sm text-slate-500 mt-1">Manage global templates that can be used for all candidates</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.document-templates.create') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm">
                    + Upload Template
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Offer Letter Template -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-900">Offer Letter Template</h2>
                </div>
                <div class="p-6">
                    @php
                        $offerLetterTemplate = $templates->where('document_type', 'offer_letter')->first();
                    @endphp

                    @if($offerLetterTemplate)
                        <div class="space-y-4">
                            @if($offerLetterTemplate->name)
                                <div>
                                    <p class="text-sm text-slate-500">Name</p>
                                    <p class="font-semibold text-slate-900">{{ $offerLetterTemplate->name }}</p>
                                </div>
                            @endif

                            @if($offerLetterTemplate->description)
                                <div>
                                    <p class="text-sm text-slate-500">Description</p>
                                    <p class="text-slate-700">{{ $offerLetterTemplate->description }}</p>
                                </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $offerLetterTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $offerLetterTemplate->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-xs text-slate-500">Version {{ $offerLetterTemplate->version }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-slate-200">
                                <a href="{{ route('admin.document-templates.download', $offerLetterTemplate) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('admin.document-templates.toggle-status', $offerLetterTemplate) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold text-sm">
                                        {{ $offerLetterTemplate->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.document-templates.destroy', $offerLetterTemplate) }}" 
                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <div class="text-xs text-slate-500 pt-2 border-t border-slate-200">
                                <p>Created: {{ $offerLetterTemplate->created_at->format('M d, Y') }}</p>
                                @if($offerLetterTemplate->createdByUser)
                                    <p>By: {{ $offerLetterTemplate->createdByUser->name }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-500 mb-4">No offer letter template uploaded yet.</p>
                            <a href="{{ route('admin.document-templates.create') }}?type=offer_letter" 
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                Upload Template
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contract Template -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-900">Contract Template</h2>
                </div>
                <div class="p-6">
                    @php
                        $contractTemplate = $templates->where('document_type', 'contract')->first();
                    @endphp

                    @if($contractTemplate)
                        <div class="space-y-4">
                            @if($contractTemplate->name)
                                <div>
                                    <p class="text-sm text-slate-500">Name</p>
                                    <p class="font-semibold text-slate-900">{{ $contractTemplate->name }}</p>
                                </div>
                            @endif

                            @if($contractTemplate->description)
                                <div>
                                    <p class="text-sm text-slate-500">Description</p>
                                    <p class="text-slate-700">{{ $contractTemplate->description }}</p>
                                </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $contractTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $contractTemplate->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-xs text-slate-500">Version {{ $contractTemplate->version }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-slate-200">
                                <a href="{{ route('admin.document-templates.download', $contractTemplate) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('admin.document-templates.toggle-status', $contractTemplate) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold text-sm">
                                        {{ $contractTemplate->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.document-templates.destroy', $contractTemplate) }}" 
                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <div class="text-xs text-slate-500 pt-2 border-t border-slate-200">
                                <p>Created: {{ $contractTemplate->created_at->format('M d, Y') }}</p>
                                @if($contractTemplate->createdByUser)
                                    <p>By: {{ $contractTemplate->createdByUser->name }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-500 mb-4">No contract template uploaded yet.</p>
                            <a href="{{ route('admin.document-templates.create') }}?type=contract" 
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                Upload Template
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
