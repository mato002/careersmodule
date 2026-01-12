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

        // Check if template already exists for this type
        $existingTemplate = DocumentTemplate::where('document_type', $validated['document_type'])->first();

        // Store template file
        $path = $request->file('template_file')->store('document-templates', 'public');

        if ($existingTemplate) {
            // Delete old template file
            if ($existingTemplate->template_path) {
                Storage::disk('public')->delete($existingTemplate->template_path);
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

        return Storage::disk('public')->download($template->template_path);
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
            Storage::disk('public')->delete($template->template_path);
        }

        $template->delete();

        return redirect()->route('admin.document-templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
