<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionManagementService
{
    /**
     * Maximum number of concurrent sessions allowed per user.
     */
    const MAX_SESSIONS = 2;

    /**
     * Track a new session for a user.
     */
    public function trackSession(User $user, Request $request): UserSession
    {
        $sessionId = $request->session()->getId();
        
        // Parse user agent
        $userAgent = $request->userAgent();
        $deviceInfo = $this->parseUserAgent($userAgent);

        // Check if session already exists
        $existingSession = UserSession::where('session_id', $sessionId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingSession) {
            // Update existing session
            // Note: 'location' field is reserved for future geolocation implementation
            $existingSession->update([
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'last_activity' => now(),
                'is_current' => true,
            ]);

            // Mark other sessions as not current
            UserSession::where('user_id', $user->id)
                ->where('id', '!=', $existingSession->id)
                ->update(['is_current' => false]);

            return $existingSession;
        }

        // Check session limit
        $activeSessions = $this->getActiveSessionsCount($user);
        
        if ($activeSessions >= self::MAX_SESSIONS) {
            // Remove oldest session
            $oldestSession = UserSession::where('user_id', $user->id)
                ->orderBy('last_activity', 'asc')
                ->first();
            
            if ($oldestSession) {
                $this->revokeSession($oldestSession->session_id);
            }
        }

        // Create new session
        // Note: 'location' field is reserved for future geolocation implementation
        // Can be populated using IP geolocation services (e.g., MaxMind GeoIP, ipapi.co, etc.)
        $session = UserSession::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'platform' => $deviceInfo['platform'],
            'last_activity' => now(),
            'is_current' => true,
        ]);

        // Mark other sessions as not current
        UserSession::where('user_id', $user->id)
            ->where('id', '!=', $session->id)
            ->update(['is_current' => false]);

        return $session;
    }

    /**
     * Update session activity.
     */
    public function updateActivity(User $user, string $sessionId): void
    {
        UserSession::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->update([
                'last_activity' => now(),
                'is_current' => true,
            ]);

        // Mark other sessions as not current
        UserSession::where('user_id', $user->id)
            ->where('session_id', '!=', $sessionId)
            ->update(['is_current' => false]);
    }

    /**
     * Revoke a session.
     */
    public function revokeSession(string $sessionId): bool
    {
        $session = UserSession::where('session_id', $sessionId)->first();
        
        if (!$session) {
            return false;
        }

        // Delete the session from database
        DB::table(config('session.table', 'sessions'))
            ->where('id', $sessionId)
            ->delete();

        // Delete the session record
        $session->delete();

        return true;
    }

    /**
     * Revoke all sessions except the current one.
     */
    public function revokeOtherSessions(User $user, string $currentSessionId): int
    {
        $sessions = UserSession::where('user_id', $user->id)
            ->where('session_id', '!=', $currentSessionId)
            ->get();

        $count = 0;
        foreach ($sessions as $session) {
            if ($this->revokeSession($session->session_id)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get active sessions count for a user.
     */
    public function getActiveSessionsCount(User $user): int
    {
        return UserSession::where('user_id', $user->id)
            ->where('last_activity', '>', now()->subMinutes(config('session.lifetime', 120)))
            ->count();
    }

    /**
     * Parse user agent to extract device information.
     */
    protected function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'device_type' => 'unknown',
                'browser' => 'Unknown',
                'platform' => 'Unknown',
            ];
        }

        $deviceType = 'desktop';
        $browser = 'Unknown';
        $platform = 'Unknown';

        // Detect device type
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            $deviceType = 'tablet';
        }

        // Detect browser
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edg/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect platform
        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }

    /**
     * Clean up expired sessions.
     */
    public function cleanupExpiredSessions(): int
    {
        $lifetime = config('session.lifetime', 120);
        
        return UserSession::where('last_activity', '<', now()->subMinutes($lifetime))
            ->delete();
    }
}

