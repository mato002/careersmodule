<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CareerController extends Controller
{
    public function index()
    {
        // Show all active jobs (both open and closed) - closed jobs serve as evidence of past postings
        $jobs = JobPost::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $generalSettings = GeneralSetting::query()->latest()->first();

        return view('careers.index', compact('jobs', 'generalSettings'));
    }

    public function show($slug)
    {
        // Try to resolve the job post by slug - let Laravel show the actual error
        $job = JobPost::where('slug', $slug)->firstOrFail();
        
        // Only hide inactive jobs, but allow viewing closed jobs (as evidence)
        if (!$job->is_active) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->view('errors.404', ['message' => 'This job posting is not available.'], 404)
            );
        }

        $job->incrementViews();

        // Get related jobs (both open and closed, but active)
        $relatedJobs = JobPost::where('is_active', true)
            ->where('id', '!=', $job->id)
            ->where('department', $job->department)
            ->limit(3)
            ->get();

        $generalSettings = GeneralSetting::query()->latest()->first();

        return view('careers.show', compact('job', 'relatedJobs', 'generalSettings'));
    }
}

