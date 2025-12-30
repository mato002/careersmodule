<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobSievingCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
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

    public function index(Request $request)
    {
        $query = JobPost::withCount('applications');
        
        // Filter by company for clients
        $query = $this->applyCompanyFilter($query);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('employment_type', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Department filter
        if ($request->filled('department') && $request->string('department') !== 'all') {
            $query->where('department', $request->string('department'));
        }

        // Get counts with company filter applied
        $totalJobsCount = $this->applyCompanyFilter(JobPost::query())->count();
        $filteredJobsCount = $query->count();

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $departments = $this->applyCompanyFilter(JobPost::whereNotNull('department'))
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        return view('admin.jobs.index', compact('jobs', 'totalJobsCount', 'filteredJobsCount', 'departments'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        
        // Auto-assign company_id for clients
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $validated['company_id'] = $user->company_id;
        }

        JobPost::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job post created successfully!');
    }

    public function show(JobPost $job)
    {
        // Check if client can access this job (must belong to their company)
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $job->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this job post.');
        }
        
        $job->loadCount('applications');
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(JobPost $job)
    {
        // Check if client can access this job (must belong to their company)
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $job->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this job post.');
        }
        
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($job->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->has('is_active');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job post updated successfully!');
    }

    public function toggleStatus(JobPost $job)
    {
        // Check if client can access this job (must belong to their company)
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $job->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to modify this job post.');
        }
        
        $job->update([
            'is_active' => !$job->is_active
        ]);

        $status = $job->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.jobs.index')
            ->with('success', "Job post {$status} successfully!");
    }

    public function configureSieving(JobPost $job)
    {
        // Check if client can access this job (must belong to their company)
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $job->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this job post.');
        }
        
        $criteria = $job->sievingCriteria ?? new JobSievingCriteria([
            'job_post_id' => $job->id,
            'criteria_json' => JobSievingCriteria::getDefaultCriteria(),
            'auto_pass_threshold' => 75,
            'auto_reject_threshold' => 35,
            'auto_pass_confidence' => 0.85,
            'auto_reject_confidence' => 0.90,
        ]);

        return view('admin.jobs.configure-sieving', compact('job', 'criteria'));
    }

    public function storeSieving(Request $request, JobPost $job)
    {
        // Check if client can access this job (must belong to their company)
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $job->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to modify this job post.');
        }
        
        $validated = $request->validate([
            'auto_pass_threshold' => 'required|integer|min:0|max:100',
            'auto_reject_threshold' => 'required|integer|min:0|max:100',
            'auto_pass_confidence' => 'required|numeric|min:0|max:1',
            'auto_reject_confidence' => 'required|numeric|min:0|max:1',
            'criteria_json' => 'required|json',
        ]);

        $validated['criteria_json'] = json_decode($validated['criteria_json'], true);
        $validated['job_post_id'] = $job->id;
        $validated['created_by'] = auth()->id();

        JobSievingCriteria::updateOrCreate(
            ['job_post_id' => $job->id],
            $validated
        );

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'AI Sieving criteria configured successfully!');
    }
}

