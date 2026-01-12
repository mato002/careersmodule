@extends('layouts.admin')

@section('title', 'Candidate Documents')

@section('header-description', 'Manage candidate documents')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $candidate->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $candidate->email }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.candidates.show', $candidate) }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Back to Profile
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Offer Letter Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-xl font-bold text-slate-900">Offer Letter</h2>
            </div>
            <div class="p-6">
                @php
                    $offerLetter = $documents->get('offer_letter')?->first();
                @endphp

                <!-- Assign Template Form -->
                @if(!$offerLetter)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-3">Assign Template</h3>
                        <form method="POST" action="{{ route('admin.candidates.documents.assign-template', $candidate) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="document_type" value="offer_letter">
                            
                            @if($globalTemplates->has('offer_letter'))
                                <div class="mb-4 p-3 bg-white border border-blue-300 rounded-lg">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="use_global_template" value="1" 
                                               onchange="toggleTemplateUpload(this)" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="font-semibold text-blue-900">Use Global Template</span>
                                            <p class="text-xs text-gray-600">Use the system-wide offer letter template</p>
                                            @if($globalTemplates->get('offer_letter')->name)
                                                <p class="text-xs text-gray-500 mt-1">Template: {{ $globalTemplates->get('offer_letter')->name }}</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endif

                            <div id="template_upload_section" style="display: {{ $globalTemplates->has('offer_letter') ? 'none' : 'block' }};">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <input type="file" name="template_file" id="offer_letter_file"
                                               accept=".pdf,.doc,.docx" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                                        @error('template_file')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                            Assign Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="offer_letter_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                <textarea id="offer_letter_notes" name="notes" rows="2" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Add any notes about this document...">{{ old('notes') }}</textarea>
                            </div>
                        </form>
                    </div>
                @endif

                @if($offerLetter)
                    <div class="space-y-4">
                        @if($offerLetter->hasTemplate())
                            <!-- Template Section -->
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="font-semibold text-green-900 mb-3">Template Available</p>
                                <div class="flex items-center gap-2 mb-4">
                                    <a href="{{ route('admin.candidates.documents.download-template', $offerLetter) }}" 
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                        📥 Download Template
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.documents.destroy', $offerLetter) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- HR Upload Filled Version -->
                                <div class="mt-4 p-3 bg-white border border-green-300 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-900 mb-2">HR Workflow: Fill & Sign Template</p>
                                    <form method="POST" action="{{ route('admin.candidates.documents.upload-filled-hr', $offerLetter) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input type="file" name="filled_file" 
                                                   accept=".pdf,.doc,.docx" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm whitespace-nowrap">
                                                Upload Filled & Signed
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-600">Download template above, fill in HR sections, sign, then upload here</p>
                                        <textarea name="notes" rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                  placeholder="Add notes about the filled version (optional)"></textarea>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if($offerLetter->hasFilledVersion())
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                <p class="font-semibold text-amber-900 mb-2">Filled Version Available</p>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <form method="POST" action="{{ route('admin.candidates.documents.update-status', $offerLetter) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="pending" {{ $offerLetter->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="submitted" {{ $offerLetter->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="approved" {{ $offerLetter->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $offerLetter->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </form>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.candidates.documents.download-filled', $offerLetter) }}" 
                                           class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-semibold text-sm">
                                            📥 Download Filled Version
                                        </a>
                                        <a href="{{ route('admin.candidates.documents.download-template', $offerLetter) }}" 
                                           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold text-sm">
                                            📄 View Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Contract Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-xl font-bold text-slate-900">Contract</h2>
            </div>
            <div class="p-6">
                @php
                    $contract = $documents->get('contract')?->first();
                @endphp

                <!-- Assign Template Form -->
                @if(!$contract)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-3">Assign Template</h3>
                        <form method="POST" action="{{ route('admin.candidates.documents.assign-template', $candidate) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="document_type" value="contract">
                            
                            @if($globalTemplates->has('contract'))
                                <div class="mb-4 p-3 bg-white border border-blue-300 rounded-lg">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="use_global_template" value="1" 
                                               onchange="toggleTemplateUpload(this, 'contract')" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="font-semibold text-blue-900">Use Global Template</span>
                                            <p class="text-xs text-gray-600">Use the system-wide contract template</p>
                                            @if($globalTemplates->get('contract')->name)
                                                <p class="text-xs text-gray-500 mt-1">Template: {{ $globalTemplates->get('contract')->name }}</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endif

                            <div id="contract_upload_section" style="display: {{ $globalTemplates->has('contract') ? 'none' : 'block' }};">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <input type="file" name="template_file" id="contract_file"
                                               accept=".pdf,.doc,.docx" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                                        @error('template_file')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                            Assign Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="contract_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                <textarea id="contract_notes" name="notes" rows="2" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Add any notes about this document...">{{ old('notes') }}</textarea>
                            </div>
                        </form>
                    </div>
                @endif

                @if($contract)
                    <div class="space-y-4">
                        @if($contract->hasTemplate())
                            <!-- Template Section -->
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="font-semibold text-green-900 mb-3">Template Available</p>
                                <div class="flex items-center gap-2 mb-4">
                                    <a href="{{ route('admin.candidates.documents.download-template', $contract) }}"
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                        📥 Download Template
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.documents.destroy', $contract) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- HR Upload Filled Version -->
                                <div class="mt-4 p-3 bg-white border border-green-300 rounded-lg">
                                    <p class="text-sm font-semibold text-gray-900 mb-2">HR Workflow: Fill & Sign Template</p>
                                    <form method="POST" action="{{ route('admin.candidates.documents.upload-filled-hr', $contract) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input type="file" name="filled_file" 
                                                   accept=".pdf,.doc,.docx" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm whitespace-nowrap">
                                                Upload Filled & Signed
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-600">Download template above, fill in HR sections, sign, then upload here</p>
                                        <textarea name="notes" rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                  placeholder="Add notes about the filled version (optional)"></textarea>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if($contract->hasFilledVersion())
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                <p class="font-semibold text-amber-900 mb-2">Filled Version Available</p>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <form method="POST" action="{{ route('admin.candidates.documents.update-status', $contract) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                    class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="pending" {{ $contract->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="submitted" {{ $contract->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="approved" {{ $contract->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $contract->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </form>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.candidates.documents.download-filled', $contract) }}"
                                           class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-semibold text-sm">
                                            📥 Download Filled Version
                                        </a>
                                        <a href="{{ route('admin.candidates.documents.download-template', $contract) }}"
                                           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold text-sm">
                                            📄 View Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- ID, KRA, SHA Documents -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-xl font-bold text-slate-900">Required Documents</h2>
                <p class="text-sm text-slate-600 mt-1">View candidate-uploaded documents</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- National ID -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">National ID</h3>
                    @php
                        $idDoc = $documents->get('id')?->first();
                    @endphp
                    
                    @if($idDoc && $idDoc->hasFilledVersion())
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900 mb-1">Document Uploaded</p>
                                    <p class="text-sm text-slate-600">Status: 
                                        <span class="font-semibold">
                                            @if($idDoc->status === 'approved')
                                                ✅ Approved
                                            @elseif($idDoc->status === 'rejected')
                                                ❌ Rejected
                                            @else
                                                ⏳ {{ ucfirst($idDoc->status) }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.candidates.documents.update-status', $idDoc) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                                class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="pending" {{ $idDoc->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="submitted" {{ $idDoc->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="approved" {{ $idDoc->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $idDoc->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form>
                                    <a href="{{ route('admin.candidates.documents.download', $idDoc) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 text-sm">No document uploaded yet.</p>
                    @endif
                </div>

                <!-- KRA PIN Certificate -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">KRA PIN Certificate</h3>
                    @php
                        $kraDoc = $documents->get('kra')?->first();
                    @endphp
                    
                    @if($kraDoc && $kraDoc->hasFilledVersion())
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900 mb-1">Document Uploaded</p>
                                    <p class="text-sm text-slate-600">Status: 
                                        <span class="font-semibold">
                                            @if($kraDoc->status === 'approved')
                                                ✅ Approved
                                            @elseif($kraDoc->status === 'rejected')
                                                ❌ Rejected
                                            @else
                                                ⏳ {{ ucfirst($kraDoc->status) }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.candidates.documents.update-status', $kraDoc) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                                class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="pending" {{ $kraDoc->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="submitted" {{ $kraDoc->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="approved" {{ $kraDoc->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $kraDoc->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form>
                                    <a href="{{ route('admin.candidates.documents.download', $kraDoc) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 text-sm">No document uploaded yet.</p>
                    @endif
                </div>

                <!-- SHA Certificate -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">SHA Certificate</h3>
                    @php
                        $shaDoc = $documents->get('sha')?->first();
                    @endphp
                    
                    @if($shaDoc && $shaDoc->hasFilledVersion())
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900 mb-1">Document Uploaded</p>
                                    <p class="text-sm text-slate-600">Status: 
                                        <span class="font-semibold">
                                            @if($shaDoc->status === 'approved')
                                                ✅ Approved
                                            @elseif($shaDoc->status === 'rejected')
                                                ❌ Rejected
                                            @else
                                                ⏳ {{ ucfirst($shaDoc->status) }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.candidates.documents.update-status', $shaDoc) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                                class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="pending" {{ $shaDoc->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="submitted" {{ $shaDoc->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="approved" {{ $shaDoc->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $shaDoc->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form>
                                    <a href="{{ route('admin.candidates.documents.download', $shaDoc) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 text-sm">No document uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTemplateUpload(checkbox, type = 'offer_letter') {
            const uploadSection = document.getElementById(type === 'contract' ? 'contract_upload_section' : 'template_upload_section');
            const fileInput = document.getElementById(type === 'contract' ? 'contract_file' : 'offer_letter_file');
            
            if (checkbox.checked) {
                uploadSection.style.display = 'none';
                if (fileInput) fileInput.removeAttribute('required');
            } else {
                uploadSection.style.display = 'block';
                if (fileInput) fileInput.setAttribute('required', 'required');
            }
        }
    </script>
@endsection
