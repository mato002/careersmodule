<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfInterviewQuestion;
use App\Models\JobPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SelfInterviewQuestionController extends Controller
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
     * Check if user can access this question (for clients, must belong to their company)
     */
    protected function checkQuestionAccess(SelfInterviewQuestion $question)
    {
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this question.');
        }
    }

    /**
     * Display a listing of self‑interview questions.
     */
    public function index(Request $request)
    {
        $query = SelfInterviewQuestion::with('jobPost');
        
        // Filter by company for clients
        $query = $this->applyCompanyFilter($query);

        // Filter by job post
        $jobPostId = $request->input('job_post_id');
        if ($jobPostId !== null && $jobPostId !== '') {
            if ($jobPostId === 'global') {
                $query->whereNull('job_post_id');
            } else {
                $jobPostIdInt = (int) $jobPostId;
                if ($jobPostIdInt > 0) {
                    $query->where('job_post_id', $jobPostIdInt);
                }
            }
        }

        // Filter by active status
        $isActiveFilter = $request->input('is_active');
        if ($isActiveFilter !== null && $isActiveFilter !== '' && $isActiveFilter !== 'all') {
            $isActive = ($isActiveFilter === '1' || $isActiveFilter === 1 || $isActiveFilter === true);
            $query->where('is_active', $isActive);
        }

        $questions = $query->orderByRaw('job_post_id IS NULL ASC')
            ->orderBy('job_post_id')
            ->orderBy('display_order')
            ->paginate(20)
            ->withQueryString();

        // Get job posts for filter (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.index', compact('questions', 'jobPosts'));
    }

    public function create()
    {
        $question = new SelfInterviewQuestion([
            'points' => 4,
            'is_active' => true,
            'display_order' => 0,
        ]);

        // Get job posts (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.create', compact('question', 'jobPosts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'question' => 'required|string',
            'options' => 'nullable|array',
            'options.*' => 'required_with:options|string',
            'correct_answer' => 'nullable|string|max:5',
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

        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Normalize options array (simple text answers)
        $validated['options'] = $validated['options'] ?? [];

        SelfInterviewQuestion::create($validated);

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question created successfully!');
    }

    public function edit(SelfInterviewQuestion $selfInterview)
    {
        $this->checkQuestionAccess($selfInterview);
        
        // Get job posts (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.edit', [
            'question' => $selfInterview,
            'jobPosts' => $jobPosts,
        ]);
    }

    public function update(Request $request, SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'question' => 'required|string',
            'options' => 'nullable|array',
            'options.*' => 'required_with:options|string',
            'correct_answer' => 'nullable|string|max:5',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        $validated['options'] = $validated['options'] ?? [];

        $selfInterview->update($validated);

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question updated successfully!');
    }

    public function destroy(SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $selfInterview->delete();

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question deleted successfully!');
    }

    public function toggleStatus(SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $selfInterview->update(['is_active' => ! $selfInterview->is_active]);

        $status = $selfInterview->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.self-interview.index')
            ->with('success', "Question {$status} successfully!");
    }
}


