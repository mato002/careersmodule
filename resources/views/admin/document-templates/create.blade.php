@extends('layouts.admin')

@section('title', 'Upload Document Template')

@section('header-description', 'Upload a global template for offer letters or contracts')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Upload Document Template</h1>
                <p class="text-sm text-slate-500 mt-1">This template will be available for all candidates</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.document-templates.index') }}" 
                   class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Back to Templates
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.document-templates.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
            @csrf

            <!-- Document Type -->
            <div>
                <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Document Type <span class="text-red-500">*</span></label>
                <select id="document_type" name="document_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Type...</option>
                    <option value="offer_letter" {{ old('document_type', request()->get('type')) === 'offer_letter' ? 'selected' : '' }}>Offer Letter</option>
                    <option value="contract" {{ old('document_type', request()->get('type')) === 'contract' ? 'selected' : '' }}>Contract</option>
                </select>
                @error('document_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Template File -->
            <div>
                <label for="template_file" class="block text-sm font-medium text-gray-700 mb-2">Template File <span class="text-red-500">*</span></label>
                <input type="file" id="template_file" name="template_file" 
                       accept=".pdf,.doc,.docx" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                @error('template_file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name (Optional)</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       placeholder="e.g., Standard Offer Letter 2024"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                <textarea id="description" name="description" rows="3" 
                          placeholder="Describe this template..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="2" 
                          placeholder="Add any internal notes about this template..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.document-templates.index') }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Upload Template
                </button>
            </div>
        </form>
    </div>
@endsection
