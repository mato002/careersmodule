<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlockedIp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50);

        // Get unique actions for filter dropdown
        $actions = ActivityLog::distinct()->pluck('action')->sort()->values();

        // Get users for filter dropdown
        $users = User::whereHas('activityLogs')->get();

        // Get blocked IPs to show status
        $blockedIps = BlockedIp::active()->pluck('ip_address')->toArray();

        return view('admin.activity-logs.index', compact('logs', 'actions', 'users', 'blockedIps'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        $isIpBlocked = false;
        if ($activityLog->ip_address) {
            $isIpBlocked = BlockedIp::where('ip_address', $activityLog->ip_address)
                ->active()
                ->exists();
        }

        $isUserBanned = false;
        if ($activityLog->user_id) {
            $isUserBanned = $activityLog->user->is_banned ?? false;
        }

        return view('admin.activity-logs.show', compact('activityLog', 'isIpBlocked', 'isUserBanned'));
    }

    /**
     * Block an IP address from an activity log.
     */
    public function blockIp(Request $request, ActivityLog $activityLog): RedirectResponse
    {
        if (!$activityLog->ip_address) {
            return back()->withErrors(['error' => 'This activity log does not have an IP address.']);
        }

        // Check if already blocked
        $existingBlock = BlockedIp::where('ip_address', $activityLog->ip_address)
            ->active()
            ->first();

        if ($existingBlock) {
            return back()->with('warning', 'This IP address is already blocked.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:now',
        ]);

        BlockedIp::create([
            'ip_address' => $activityLog->ip_address,
            'reason' => $validated['reason'] ?? 'Blocked from activity log',
            'blocked_by' => auth()->id(),
            'activity_log_id' => $activityLog->id,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => true,
        ]);

        // Log the action
        activity()
            ->causedBy(auth()->user())
            ->performedOn($activityLog)
            ->withProperties(['ip_address' => $activityLog->ip_address])
            ->log('block_ip');

        return back()->with('success', 'IP address ' . $activityLog->ip_address . ' has been blocked successfully.');
    }

    /**
     * Unblock an IP address.
     */
    public function unblockIp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => 'required|string|max:45',
        ]);

        $ipAddress = $validated['ip_address'];
        $blockedIp = BlockedIp::where('ip_address', $ipAddress)
            ->active()
            ->first();

        if (!$blockedIp) {
            return back()->withErrors(['error' => 'This IP address is not currently blocked.']);
        }

        $blockedIp->update(['is_active' => false]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip_address' => $ipAddress])
            ->log('unblock_ip');

        return back()->with('success', 'IP address ' . $ipAddress . ' has been unblocked.');
    }

    /**
     * Ban a user from an activity log.
     */
    public function banUser(ActivityLog $activityLog): RedirectResponse
    {
        if (!$activityLog->user_id) {
            return back()->withErrors(['error' => 'This activity log does not have an associated user.']);
        }

        $user = $activityLog->user;
        
        if ($user->is_banned ?? false) {
            return back()->with('warning', 'This user is already banned.');
        }

        // Add is_banned column if it doesn't exist
        if (!DB::getSchemaBuilder()->hasColumn('users', 'is_banned')) {
            DB::statement('ALTER TABLE users ADD COLUMN is_banned BOOLEAN DEFAULT FALSE');
        }

        $user->update(['is_banned' => true]);

        // Revoke all active sessions for this user
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        // Log the action
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['user_id' => $user->id, 'user_email' => $user->email])
            ->log('ban_user');

        return back()->with('success', 'User ' . $user->email . ' has been banned and all their sessions have been revoked.');
    }

    /**
     * Unban a user.
     */
    public function unbanUser(User $user): RedirectResponse
    {
        if (!($user->is_banned ?? false)) {
            return back()->with('warning', 'This user is not currently banned.');
        }

        $user->update(['is_banned' => false]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['user_id' => $user->id, 'user_email' => $user->email])
            ->log('unban_user');

        return back()->with('success', 'User ' . $user->email . ' has been unbanned.');
    }

    /**
     * Revoke all sessions for a user from an activity log.
     */
    public function revokeUserSessions(ActivityLog $activityLog): RedirectResponse
    {
        if (!$activityLog->user_id) {
            return back()->withErrors(['error' => 'This activity log does not have an associated user.']);
        }

        $user = $activityLog->user;

        $sessionsDeleted = DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['user_id' => $user->id, 'sessions_deleted' => $sessionsDeleted])
            ->log('revoke_sessions');

        return back()->with('success', 'All sessions for ' . $user->email . ' have been revoked. (' . $sessionsDeleted . ' sessions deleted)');
    }
}


