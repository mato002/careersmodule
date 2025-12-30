<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\NewsletterSubscriber;
use App\Models\TeamMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Date range for analytics (in days)
        $range = (int) $request->input('range', 30);
        if (! in_array($range, [7, 30, 90], true)) {
            $range = 30;
        }

        $startDate = now()->subDays($range - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $stats = [
            // Messages
            'unread_messages' => ContactMessage::whereNull('handled_at')->count(),
            'total_messages' => ContactMessage::count(),
            
            // Job Applications
            'pending_job_applications' => JobApplication::where('status', 'pending')->count(),
            'total_job_applications' => JobApplication::count(),
            'shortlisted_job_applications' => JobApplication::where('status', 'shortlisted')->count(),
            'hired_job_applications' => JobApplication::where('status', 'hired')->count(),
            
            // Job Posts
            'active_job_posts' => JobPost::where('is_active', true)->count(),
            'total_job_posts' => JobPost::count(),
            
            // Team
            'team_members' => TeamMember::count(),
            
            // Subscribers
            'newsletter_subscribers' => NewsletterSubscriber::count(),
            
            // Users
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];

        // Time-series analytics
        $jobApplicationsTrend = JobApplication::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $contactMessagesTrend = ContactMessage::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentMessages = ContactMessage::latest()->limit(10)->get();
        $recentJobApplications = JobApplication::with('jobPost')->latest()->limit(10)->get();
        $recentActivityLogs = ActivityLog::with('user')->latest()->limit(10)->get();
        $latestJobPosts = JobPost::latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'range',
            'jobApplicationsTrend',
            'contactMessagesTrend',
            'recentMessages',
            'recentJobApplications',
            'recentActivityLogs',
            'latestJobPosts'
        ));
    }
}
