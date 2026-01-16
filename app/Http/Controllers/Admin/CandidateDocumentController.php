<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CandidateDocumentController extends Controller
{
    /**
     * Display candidate documents.
     */
    public function index(Candidate $candidate): View
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $documents = CandidateDocument::where('candidate_id', $candidate->id)
            ->orderBy('document_type')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('document_type');

        // Get global templates
        $globalTemplates = DocumentTemplate::active()
            ->whereIn('document_type', ['offer_letter', 'contract'])
            ->get()
            ->keyBy('document_type');

        return view('admin.candidates.documents', compact('candidate', 'documents', 'globalTemplates'));
    }

    /**
     * Assign global template to candidate or upload candidate-specific template.
     */
    public function assignTemplate(Request $request, Candidate $candidate): RedirectResponse
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:offer_letter,contract'],
            'use_global_template' => ['nullable', 'boolean'],
            'template_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Additional file security: verify actual file type if file is uploaded
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return redirect()->route('admin.candidates.documents', $candidate)
                    ->withErrors(['template_file' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.']);
            }
        }

        // Check if document already exists
        $existingDocument = CandidateDocument::where('candidate_id', $candidate->id)
            ->where('document_type', $validated['document_type'])
            ->first();

        $templatePath = null;

        if ($validated['use_global_template'] ?? false) {
            // Use global template
            $globalTemplate = DocumentTemplate::active()
                ->where('document_type', $validated['document_type'])
                ->first();

            if (!$globalTemplate) {
                return redirect()->route('admin.candidates.documents', $candidate)
                    ->withErrors(['error' => 'No active global template found for this document type.']);
            }

            // Copy global template to candidate-specific location
            $templatePath = 'candidate-documents/templates/' . $candidate->id . '/' . $validated['document_type'] . '_' . time() . '.' . pathinfo($globalTemplate->template_path, PATHINFO_EXTENSION);
            Storage::disk('public')->copy($globalTemplate->template_path, $templatePath);
        } else {
            // Upload candidate-specific template
            if (!$request->hasFile('template_file')) {
                return redirect()->route('admin.candidates.documents', $candidate)
                    ->withErrors(['error' => 'Please upload a template file or select a global template.']);
            }

            try {
                $templatePath = $request->file('template_file')->store('candidate-documents/templates', 'public');
            } catch (\Exception $e) {
                \Log::error('Failed to store template file: ' . $e->getMessage());
                return redirect()->route('admin.candidates.documents', $candidate)
                    ->withErrors(['error' => 'Failed to upload file. Please try again.']);
            }
        }

        if ($existingDocument) {
            // Delete old template if exists
            if ($existingDocument->template_path) {
                Storage::disk('public')->delete($existingDocument->template_path);
            }
            
            // Update existing document
            $existingDocument->template_path = $templatePath;
            $existingDocument->uploaded_by = 'hr';
            $existingDocument->uploaded_by_user_id = auth()->id();
            $existingDocument->notes = $validated['notes'] ?? null;
            $existingDocument->save();
        } else {
            // Create new document
            CandidateDocument::create([
                'candidate_id' => $candidate->id,
                'document_type' => $validated['document_type'],
                'template_path' => $templatePath,
                'uploaded_by' => 'hr',
                'uploaded_by_user_id' => auth()->id(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
        }

        return redirect()->route('admin.candidates.documents', $candidate)
            ->with('success', 'Template assigned successfully.');
    }

    /**
     * Upload filled/signed version by HR.
     */
    public function uploadFilledByHr(Request $request, CandidateDocument $document): RedirectResponse
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Only allow for offer_letter and contract
        if (!in_array($document->document_type, ['offer_letter', 'contract'])) {
            return redirect()->route('admin.candidates.documents', $candidate)
                ->withErrors(['error' => 'Filled version upload is only available for offer letters and contracts.']);
        }

        $validated = $request->validate([
            'filled_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Additional file security: verify actual file type
        $file = $request->file('filled_file');
        $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return redirect()->route('admin.candidates.documents', $candidate)
                ->withErrors(['filled_file' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.']);
        }

        // Store filled file
        try {
            $filledPath = $request->file('filled_file')->store('candidate-documents/filled-hr', 'public');
        } catch (\Exception $e) {
            \Log::error('Failed to store filled file: ' . $e->getMessage());
            return redirect()->route('admin.candidates.documents', $candidate)
                ->withErrors(['filled_file' => 'Failed to upload file. Please try again.']);
        }

        // Delete old filled version if exists
        if ($document->filled_path) {
            Storage::disk('public')->delete($document->filled_path);
        }

        // Update document
        $document->filled_path = $filledPath;
        if (isset($validated['notes'])) {
            $document->notes = ($document->notes ? $document->notes . "\n\n" : '') . 'HR Notes: ' . $validated['notes'];
        }
        $document->status = 'submitted';
        $document->save();

        return redirect()->route('admin.candidates.documents', $candidate)
            ->with('success', 'Filled document uploaded successfully.');
    }

    /**
     * Update document status (approve/reject).
     */
    public function updateStatus(Request $request, CandidateDocument $document): RedirectResponse
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,submitted,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->status = $validated['status'];
        if (isset($validated['notes'])) {
            $document->notes = $validated['notes'];
        }
        $document->save();

        return redirect()->route('admin.candidates.documents', $candidate)
            ->with('success', 'Document status updated successfully.');
    }

    /**
     * Download document.
     */
    public function download(CandidateDocument $document)
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $path = $document->filled_path ?? $document->template_path;
        
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'Document not found.');
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * Download template only.
     */
    public function downloadTemplate(CandidateDocument $document)
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        if (!$document->template_path || !Storage::disk('public')->exists($document->template_path)) {
            abort(404, 'Template file not found.');
        }

        return Storage::disk('public')->download($document->template_path);
    }

    /**
     * Download filled version only.
     */
    public function downloadFilled(CandidateDocument $document)
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        if (!$document->filled_path || !Storage::disk('public')->exists($document->filled_path)) {
            abort(404, 'Filled document not found.');
        }

        return Storage::disk('public')->download($document->filled_path);
    }

    /**
     * Delete document.
     */
    public function destroy(CandidateDocument $document): RedirectResponse
    {
        $candidate = $document->candidate;
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Delete files
        if ($document->template_path) {
            Storage::disk('public')->delete($document->template_path);
        }
        if ($document->filled_path) {
            Storage::disk('public')->delete($document->filled_path);
        }

        $document->delete();

        return redirect()->route('admin.candidates.documents', $candidate)
            ->with('success', 'Document deleted successfully.');
    }
}
