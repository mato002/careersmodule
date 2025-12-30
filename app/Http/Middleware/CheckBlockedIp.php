<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ipAddress = $request->ip();

        if ($ipAddress) {
            $blockedIp = BlockedIp::where('ip_address', $ipAddress)
                ->active()
                ->first();

            if ($blockedIp) {
                // Log the blocked attempt
                if (auth()->check()) {
                    activity()
                        ->causedBy(auth()->user())
                        ->withProperties(['ip_address' => $ipAddress, 'blocked_ip_id' => $blockedIp->id])
                        ->log('blocked_ip_access_attempt');
                }

                abort(403, 'Your IP address has been blocked. Reason: ' . ($blockedIp->reason ?? 'No reason provided'));
            }
        }

        return $next($request);
    }
}
