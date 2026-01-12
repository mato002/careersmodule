@extends('layouts.candidate')

@section('title', 'Documents')
@section('header-description', 'Manage your documents and file uploads')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        <!-- Offer Letter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Offer Letter</h2>
                <p class="text-sm text-gray-600 mt-1">Download the template, fill it, and upload the completed version.</p>
            </div>
            <div class="p-6">
                @php
                    $offerLetter = $documents->get('offer_letter')?->first();
                @endphp
                
                @if($offerLetter && $offerLetter->hasTemplate())
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">Template Available</p>
                                <p class="text-sm text-gray-600">HR has uploaded the offer letter template for you.</p>
                            </div>
                            <a href="{{ route('candidate.documents.download-template', $offerLetter) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Download Template
                            </a>
                        </div>

                        @if($offerLetter->hasFilledVersion())
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="font-semibold text-green-900 mb-2">Filled Version Submitted</p>
                                <p class="text-sm text-green-700 mb-3">Status: 
                                    <span class="font-semibold">
                                        @if($offerLetter->status === 'approved')
                                            ✅ Approved
                                        @elseif($offerLetter->status === 'rejected')
                                            ❌ Rejected
                                        @else
                                            ⏳ Pending Review
                                        @endif
                                    </span>
                                </p>
                                <div class="flex gap-2">
                                    <a href="{{ route('candidate.documents.download', $offerLetter) }}" 
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                        Download Submitted
                                    </a>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('candidate.documents.upload-filled', $offerLetter) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="offer_letter_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Filled Offer Letter</label>
                                <input type="file" id="offer_letter_file" name="filled_document" 
                                       accept=".pdf,.doc,.docx" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                                @error('filled_document')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                Upload Filled Document
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No offer letter template available yet. HR will upload it when ready.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contract Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Contract</h2>
                <p class="text-sm text-gray-600 mt-1">Download the contract template, fill it, and upload the completed version.</p>
            </div>
            <div class="p-6">
                @php
                    $contract = $documents->get('contract')?->first();
                @endphp
                
                @if($contract && $contract->hasTemplate())
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">Template Available</p>
                                <p class="text-sm text-gray-600">HR has uploaded the contract template for you.</p>
                            </div>
                            <a href="{{ route('candidate.documents.download-template', $contract) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Download Template
                            </a>
                        </div>

                        @if($contract->hasFilledVersion())
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="font-semibold text-green-900 mb-2">Filled Version Submitted</p>
                                <p class="text-sm text-green-700 mb-3">Status: 
                                    <span class="font-semibold">
                                        @if($contract->status === 'approved')
                                            ✅ Approved
                                        @elseif($contract->status === 'rejected')
                                            ❌ Rejected
                                        @else
                                            ⏳ Pending Review
                                        @endif
                                    </span>
                                </p>
                                <div class="flex gap-2">
                                    <a href="{{ route('candidate.documents.download', $contract) }}" 
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                        Download Submitted
                                    </a>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('candidate.documents.upload-filled', $contract) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="contract_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Filled Contract</label>
                                <input type="file" id="contract_file" name="filled_document" 
                                       accept=".pdf,.doc,.docx" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                                @error('filled_document')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                Upload Filled Document
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No contract template available yet. HR will upload it when ready.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ID, KRA, SHA Documents -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Required Documents</h2>
                <p class="text-sm text-gray-600 mt-1">Upload your identification and certification documents.</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- National ID -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">National ID</h3>
                    @php
                        $idDoc = $documents->get('id')?->first();
                    @endphp
                    
                    @if($idDoc && $idDoc->hasFilledVersion())
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
                            <p class="font-semibold text-green-900 mb-2">Document Uploaded</p>
                            <p class="text-sm text-green-700 mb-3">Status: 
                                <span class="font-semibold">
                                    @if($idDoc->status === 'approved')
                                        ✅ Approved
                                    @elseif($idDoc->status === 'rejected')
                                        ❌ Rejected
                                    @else
                                        ⏳ Pending Review
                                    @endif
                                </span>
                            </p>
                            <a href="{{ route('candidate.documents.download', $idDoc) }}" 
                               class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                Download
                            </a>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="id">
                        <div class="flex gap-4">
                            <input type="file" name="document" 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                {{ $idDoc && $idDoc->hasFilledVersion() ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, JPEG, PNG (Max 5MB)</p>
                        @error('document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </form>
                </div>

                <!-- KRA PIN Certificate -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">KRA PIN Certificate</h3>
                    @php
                        $kraDoc = $documents->get('kra')?->first();
                    @endphp
                    
                    @if($kraDoc && $kraDoc->hasFilledVersion())
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
                            <p class="font-semibold text-green-900 mb-2">Document Uploaded</p>
                            <p class="text-sm text-green-700 mb-3">Status: 
                                <span class="font-semibold">
                                    @if($kraDoc->status === 'approved')
                                        ✅ Approved
                                    @elseif($kraDoc->status === 'rejected')
                                        ❌ Rejected
                                    @else
                                        ⏳ Pending Review
                                    @endif
                                </span>
                            </p>
                            <a href="{{ route('candidate.documents.download', $kraDoc) }}" 
                               class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                Download
                            </a>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="kra">
                        <div class="flex gap-4">
                            <input type="file" name="document" 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                {{ $kraDoc && $kraDoc->hasFilledVersion() ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, JPEG, PNG (Max 5MB)</p>
                        @error('document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </form>
                </div>

                <!-- SHA Certificate -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SHA Certificate</h3>
                    @php
                        $shaDoc = $documents->get('sha')?->first();
                    @endphp
                    
                    @if($shaDoc && $shaDoc->hasFilledVersion())
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
                            <p class="font-semibold text-green-900 mb-2">Document Uploaded</p>
                            <p class="text-sm text-green-700 mb-3">Status: 
                                <span class="font-semibold">
                                    @if($shaDoc->status === 'approved')
                                        ✅ Approved
                                    @elseif($shaDoc->status === 'rejected')
                                        ❌ Rejected
                                    @else
                                        ⏳ Pending Review
                                    @endif
                                </span>
                            </p>
                            <a href="{{ route('candidate.documents.download', $shaDoc) }}" 
                               class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                Download
                            </a>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="sha">
                        <div class="flex gap-4">
                            <input type="file" name="document" 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                {{ $shaDoc && $shaDoc->hasFilledVersion() ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, JPG, JPEG, PNG (Max 5MB)</p>
                        @error('document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
