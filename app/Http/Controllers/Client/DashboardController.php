<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\TokenUsageLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || !$user->isClient()) {
                abort(403, 'Access denied. Client access required.');
            }
            return $next($request);
        });
        $this->tokenService = $tokenService;
    }

    /**
     * Display the client dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('client.setup')
                ->with('error', 'No company associated with your account. Please contact support.');
        }

        // Check subscription status
        if (!$company->isSubscriptionActive()) {
            return redirect()->route('client.subscription')
                ->with('warning', 'Your subscription is not active. Please renew to continue using the service.');
        }

        // Get token balance
        $balance = $this->tokenService->getBalance($company->id);
        $usageStats = $this->tokenService->getUsageStats($company->id, 'month');

        // Get job application stats (Note: company_id will be added in future migration)
        // For now, return empty stats until company_id is added to job_applications table
        $jobStats = [
            'total' => 0, // JobApplication::where('company_id', $company->id)->count(),
            'pending' => 0, // JobApplication::where('company_id', $company->id)->where('status', 'pending')->count(),
            'shortlisted' => 0, // JobApplication::where('company_id', $company->id)->where('status', 'shortlisted')->count(),
            'hired' => 0, // JobApplication::where('company_id', $company->id)->where('status', 'hired')->count(),
        ];

        // Get active job posts (Note: company_id will be added in future migration)
        $activeJobPosts = 0; // JobPost::where('company_id', $company->id)->where('is_active', true)->count();

        // Recent applications (Note: company_id will be added in future migration)
        $recentApplications = collect([]); // JobApplication::where('company_id', $company->id)->with('jobPost')->latest()->limit(10)->get();

        // Recent token usage
        $recentTokenUsage = TokenUsageLog::where('company_id', $company->id)
            ->with('jobApplication')
            ->latest()
            ->limit(10)
            ->get();

        return view('client.dashboard', compact(
            'user',
            'company',
            'balance',
            'usageStats',
            'jobStats',
            'activeJobPosts',
            'recentApplications',
            'recentTokenUsage'
        ));
    }
}

