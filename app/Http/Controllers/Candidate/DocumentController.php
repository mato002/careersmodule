<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{
    /**
     * Display the documents page.
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        
        $documents = CandidateDocument::where('candidate_id', $candidate->id)
            ->orderBy('document_type')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('document_type');
        
        return view('candidate.documents.index', compact('candidate', 'documents'));
    }

    /**
     * Download template document (offer letter or contract).
     */
    public function downloadTemplate(CandidateDocument $document)
    {
        $candidate = Auth::guard('candidate')->user();
        
        // Ensure document belongs to candidate
        if ($document->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access.');
        }
        
        if (!$document->template_path) {
            return Redirect::back()->with('error', 'Template document not available.');
        }
        
        if (!Storage::disk('public')->exists($document->template_path)) {
            return Redirect::back()->with('error', 'Template document not found.');
        }
        
        return Storage::disk('public')->download($document->template_path);
    }

    /**
     * Upload filled document (offer letter or contract).
     */
    public function uploadFilled(Request $request, CandidateDocument $document)
    {
        $candidate = Auth::guard('candidate')->user();
        
        // Ensure document belongs to candidate
        if ($document->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access.');
        }
        
        // Only allow upload for offer letter and contract
        if (!in_array($document->document_type, ['offer_letter', 'contract'])) {
            return Redirect::back()->with('error', 'Invalid document type for upload.');
        }
        
        $validated = $request->validate([
            'filled_document' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
        ]);

        // Additional file security: verify actual file type
        $file = $request->file('filled_document');
        $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return Redirect::back()->withErrors(['filled_document' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.']);
        }
        
        // Delete old filled document if exists
        if ($document->filled_path) {
            try {
                Storage::disk('public')->delete($document->filled_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete old filled document: ' . $e->getMessage());
                // Continue even if deletion fails
            }
        }
        
        // Store new filled document
        try {
            $path = $request->file('filled_document')->store('candidate-documents/filled', 'public');
        } catch (\Exception $e) {
            \Log::error('Failed to store filled document: ' . $e->getMessage());
            return Redirect::back()->withErrors(['filled_document' => 'Failed to upload file. Please try again.']);
        }
        
        $document->filled_path = $path;
        $document->status = 'submitted';
        $document->uploaded_by = 'candidate';
        $document->save();
        
        return Redirect::back()->with('success', 'Document uploaded successfully. HR will review it.');
    }

    /**
     * Upload document (ID, KRA, SHA).
     */
    public function upload(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:id,kra,sha'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ]);

        // Additional file security: verify actual file type
        $file = $request->file('document');
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return Redirect::back()->withErrors(['document' => 'Invalid file type. Only PDF, JPG, and PNG files are allowed.']);
        }
        
        // Check if document already exists
        $existingDocument = CandidateDocument::where('candidate_id', $candidate->id)
            ->where('document_type', $validated['document_type'])
            ->first();
        
        if ($existingDocument) {
            // Delete old document
            if ($existingDocument->filled_path) {
                try {
                    Storage::disk('public')->delete($existingDocument->filled_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old document: ' . $e->getMessage());
                    // Continue even if deletion fails
                }
            }
            
            // Update existing document
            try {
                $path = $request->file('document')->store('candidate-documents/' . $validated['document_type'], 'public');
            } catch (\Exception $e) {
                \Log::error('Failed to store document: ' . $e->getMessage());
                return Redirect::back()->withErrors(['document' => 'Failed to upload file. Please try again.']);
            }
            $existingDocument->filled_path = $path;
            $existingDocument->status = 'submitted';
            $existingDocument->uploaded_by = 'candidate';
            $existingDocument->save();
        } else {
            // Create new document
            try {
                $path = $request->file('document')->store('candidate-documents/' . $validated['document_type'], 'public');
            } catch (\Exception $e) {
                \Log::error('Failed to store document: ' . $e->getMessage());
                return Redirect::back()->withErrors(['document' => 'Failed to upload file. Please try again.']);
            }
            
            CandidateDocument::create([
                'candidate_id' => $candidate->id,
                'document_type' => $validated['document_type'],
                'filled_path' => $path,
                'uploaded_by' => 'candidate',
                'status' => 'submitted',
            ]);
        }
        
        return Redirect::back()->with('success', 'Document uploaded successfully.');
    }

    /**
     * Download filled document.
     */
    public function download(CandidateDocument $document)
    {
        $candidate = Auth::guard('candidate')->user();
        
        // Ensure document belongs to candidate
        if ($document->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access.');
        }
        
        if (!$document->filled_path) {
            return Redirect::back()->with('error', 'Document not available.');
        }
        
        if (!Storage::disk('public')->exists($document->filled_path)) {
            return Redirect::back()->with('error', 'Document not found.');
        }
        
        return Storage::disk('public')->download($document->filled_path);
    }
}
