<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AptitudeTestQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AptitudeTestController extends Controller
{
    /**
     * Apply company filter if user is a client
     */
    protected function applyCompanyFilter($query)
    {
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            return $query->where('company_id', $user->company_id);
        }
        return $query;
    }

    /**
     * Display list of aptitude test questions
     */
    public function index(Request $request)
    {
        $query = AptitudeTestQuestion::query()->with('jobPost');
        
        // Filter by company for clients
        $query = $this->applyCompanyFilter($query);

        // Filter by job post - must work independently
        $jobPostId = $request->input('job_post_id');
        
        if ($jobPostId !== null && $jobPostId !== '') {
            if ($jobPostId === 'global') {
                $query->whereNull('job_post_id');
            } else {
                // Convert to integer - this should work for foreignId columns
                $jobPostIdInt = (int) $jobPostId;
                
                if ($jobPostIdInt > 0) {
                    // Use standard where clause - Laravel handles type conversion
                    $query->where('job_post_id', $jobPostIdInt);
                }
            }
        }

        // Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->string('section'));
        }

        // Filter by active status - only apply if explicitly set and not "all"
        $isActiveFilter = $request->input('is_active');
        if ($isActiveFilter !== null && $isActiveFilter !== '' && $isActiveFilter !== 'all') {
            // Convert "1" or "0" string to boolean
            $isActive = ($isActiveFilter === '1' || $isActiveFilter === 1 || $isActiveFilter === true);
            $query->where('is_active', $isActive);
        }

        // Build section counts query with same filters
        $sectionCountsQuery = AptitudeTestQuestion::query();
        
        // Apply company filter to section counts
        $sectionCountsQuery = $this->applyCompanyFilter($sectionCountsQuery);
        
        // Apply same job post filter to section counts
        $jobPostId = $request->input('job_post_id');
        if ($jobPostId !== null && $jobPostId !== '') {
            if ($jobPostId === 'global') {
                $sectionCountsQuery->whereNull('job_post_id');
            } else {
                $jobPostIdInt = (int) $jobPostId;
                if ($jobPostIdInt > 0) {
                    $sectionCountsQuery->where('job_post_id', $jobPostIdInt);
                }
            }
        }
        
        // Apply same section filter to section counts
        if ($request->filled('section')) {
            $sectionCountsQuery->where('section', $request->string('section'));
        }
        
        // Apply same active status filter to section counts
        $isActiveFilter = $request->input('is_active');
        if ($isActiveFilter !== null && $isActiveFilter !== '' && $isActiveFilter !== 'all') {
            // Convert "1" or "0" string to boolean
            $isActive = ($isActiveFilter === '1' || $isActiveFilter === 1 || $isActiveFilter === true);
            $sectionCountsQuery->where('is_active', $isActive);
        }

        $sectionCounts = [
            'numerical' => (clone $sectionCountsQuery)->where('section', 'numerical')->count(),
            'logical' => (clone $sectionCountsQuery)->where('section', 'logical')->count(),
            'verbal' => (clone $sectionCountsQuery)->where('section', 'verbal')->count(),
            'scenario' => (clone $sectionCountsQuery)->where('section', 'scenario')->count(),
        ];

        // Debug: Check query before pagination
        if ($request->filled('job_post_id') && $request->job_post_id !== 'global' && $request->job_post_id !== '') {
            $testCount = (clone $query)->count();
            \Log::info('Job Post Filter Debug', [
                'job_post_id' => $request->job_post_id,
                'job_post_id_type' => gettype($request->job_post_id),
                'query_count' => $testCount,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
            ]);
        }

        $questions = $query->orderByRaw('job_post_id IS NULL ASC')
            ->orderBy('job_post_id')
            ->orderBy('section')
            ->orderBy('display_order')
            ->paginate(20)
            ->withQueryString();

        // Get job posts for filter (filtered by company for clients)
        $jobPostsQuery = \App\Models\JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.aptitude-test.index', compact('questions', 'sectionCounts', 'jobPosts'));
    }

    /**
     * Show form to create new question
     */
    public function create()
    {
        $question = new AptitudeTestQuestion([
            'points' => 4,
            'is_active' => true,
            'display_order' => 0,
        ]);

        return view('admin.aptitude-test.create', compact('question'));
    }

    /**
     * Store new question
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'section' => 'required|in:numerical,logical,verbal,scenario',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:a,b,c,d',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        // Auto-assign company_id for clients
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $validated['company_id'] = $user->company_id;
        }
        
        // Convert empty string to null for job_post_id
        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format
        $optionsArray = [];
        $letters = ['a', 'b', 'c', 'd', 'e'];
        foreach ($validated['options'] as $index => $option) {
            if (!empty($option)) {
                $optionsArray[$letters[$index]] = $option;
            }
        }
        $validated['options'] = $optionsArray;

        AptitudeTestQuestion::create($validated);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question created successfully!');
    }

    /**
     * Show form to edit question
     */
    public function edit($id)
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        
        // Check if client can access this question
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this question.');
        }
        
        return view('admin.aptitude-test.edit', compact('question'));
    }

    /**
     * Update question
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'section' => 'required|in:numerical,logical,verbal,scenario',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:a,b,c,d',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Convert empty string to null for job_post_id
        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format
        $optionsArray = [];
        $letters = ['a', 'b', 'c', 'd', 'e'];
        foreach ($validated['options'] as $index => $option) {
            if (!empty($option)) {
                $optionsArray[$letters[$index]] = $option;
            }
        }
        $validated['options'] = $optionsArray;

        $question = AptitudeTestQuestion::findOrFail($id);
        
        // Check if client can access this question
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to modify this question.');
        }
        
        $question->update($validated);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete question
     */
    public function destroy($id): RedirectResponse
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        
        // Check if client can access this question
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to delete this question.');
        }
        
        $question = AptitudeTestQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Toggle question active status
     */
    public function toggleStatus($id): RedirectResponse
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        $question->update([
            'is_active' => !$question->is_active
        ]);

        $status = $question->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', "Question {$status} successfully!");
    }

    /**
     * Bulk activate questions
     */
    public function bulkActivate(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        $count = AptitudeTestQuestion::whereIn('id', $questionIds)->update(['is_active' => true]);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', "Activated {$count} question(s) successfully!");
    }

    /**
     * Bulk deactivate questions
     */
    public function bulkDeactivate(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        $count = AptitudeTestQuestion::whereIn('id', $questionIds)->update(['is_active' => false]);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', "Deactivated {$count} question(s) successfully!");
    }

    /**
     * Bulk delete questions
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        $count = AptitudeTestQuestion::whereIn('id', $questionIds)->delete();

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', "Deleted {$count} question(s) successfully!");
    }
}

