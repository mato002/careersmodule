<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only log activities for authenticated admin users
        if ($request->user() && $request->user()->is_admin) {
            // Skip logging for certain routes to avoid noise
            $skipRoutes = [
                'admin.search',
                'admin.dashboard',
            ];

            $routeName = $request->route()?->getName();

            // Log view/access activities (but skip dashboard and search)
            if ($routeName && !in_array($routeName, $skipRoutes)) {
                // Determine action based on HTTP method
                $method = $request->method();
                $action = match($method) {
                    'GET' => 'view',
                    'POST' => 'create',
                    'PUT', 'PATCH' => 'update',
                    'DELETE' => 'delete',
                    default => 'access',
                };

                // Only log POST, PUT, PATCH, DELETE for now (to reduce noise)
                if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                    $this->activityLogService->logActivity(
                        $action,
                        "Accessed route: {$routeName}",
                        [
                            'method' => $method,
                            'route' => $routeName,
                            'url' => $request->fullUrl(),
                        ]
                    );
                }
            }
        }

        return $next($request);
    }
}


