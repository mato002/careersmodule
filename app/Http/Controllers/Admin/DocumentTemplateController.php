<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentTemplateController extends Controller
{
    /**
     * Display a listing of document templates.
     */
    public function index(): View
    {
        $templates = DocumentTemplate::with(['createdByUser', 'updatedByUser'])
            ->orderBy('document_type')
            ->get();

        return view('admin.document-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating/editing a document template.
     */
    public function create(): View
    {
        return view('admin.document-templates.create');
    }

    /**
     * Store a newly created document template.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:offer_letter,contract'],
            'template_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Additional file security: verify actual file type
        $file = $request->file('template_file');
        $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return redirect()->route('admin.document-templates.create')
                ->withErrors(['template_file' => 'Invalid file type. Only PDF, DOC, and DOCX files are allowed.']);
        }

        // Check if template already exists for this type
        $existingTemplate = DocumentTemplate::where('document_type', $validated['document_type'])->first();

        try {
            // Store template file
            $path = $request->file('template_file')->store('document-templates', 'public');
        } catch (\Exception $e) {
            \Log::error('Failed to store document template: ' . $e->getMessage());
            return redirect()->route('admin.document-templates.create')
                ->withErrors(['template_file' => 'Failed to upload file. Please try again.']);
        }

        if ($existingTemplate) {
            // Delete old template file
            if ($existingTemplate->template_path) {
                try {
                    Storage::disk('public')->delete($existingTemplate->template_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old template file: ' . $e->getMessage());
                    // Continue even if deletion fails
                }
            }

            // Update existing template
            $existingTemplate->template_path = $path;
            $existingTemplate->name = $validated['name'] ?? null;
            $existingTemplate->description = $validated['description'] ?? null;
            $validated['notes'] = $validated['notes'] ?? null;
            $existingTemplate->version = $existingTemplate->version + 1;
            $existingTemplate->updated_by_user_id = auth()->id();
            $existingTemplate->is_active = true;
            $existingTemplate->save();
        } else {
            // Create new template
            DocumentTemplate::create([
                'document_type' => $validated['document_type'],
                'template_path' => $path,
                'name' => $validated['name'] ?? null,
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by_user_id' => auth()->id(),
                'updated_by_user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.document-templates.index')
            ->with('success', 'Document template uploaded successfully.');
    }

    /**
     * Download a template.
     */
    public function download(DocumentTemplate $template)
    {
        if (!$template->hasTemplate()) {
            abort(404, 'Template file not found.');
        }

        if (!Storage::disk('public')->exists($template->template_path)) {
            abort(404, 'Template file not found on disk.');
        }

        try {
            return Storage::disk('public')->download($template->template_path);
        } catch (\Exception $e) {
            \Log::error('Failed to download template: ' . $e->getMessage());
            abort(500, 'Failed to download template file.');
        }
    }

    /**
     * Toggle template active status.
     */
    public function toggleStatus(DocumentTemplate $template): RedirectResponse
    {
        $template->is_active = !$template->is_active;
        $template->save();

        $status = $template->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.document-templates.index')
            ->with('success', "Template {$status} successfully.");
    }

    /**
     * Remove the specified template.
     */
    public function destroy(DocumentTemplate $template): RedirectResponse
    {
        // Delete file
        if ($template->template_path) {
            try {
                Storage::disk('public')->delete($template->template_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete template file: ' . $e->getMessage());
                // Continue with deletion even if file deletion fails
            }
        }

        $template->delete();

        return redirect()->route('admin.document-templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
