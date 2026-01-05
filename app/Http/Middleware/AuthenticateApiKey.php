<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') 
            ?? $request->header('Authorization') 
            ?? $request->query('api_key');

        // Remove 'Bearer ' prefix if present
        if ($apiKey && str_starts_with($apiKey, 'Bearer ')) {
            $apiKey = substr($apiKey, 7);
        }

        if (!$apiKey) {
            return response()->json([
                'error' => 'API key is required',
                'message' => 'Please provide an API key via X-API-Key header, Authorization header, or api_key query parameter.'
            ], 401);
        }

        $company = Company::where('api_key', $apiKey)->first();

        if (!$company) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is not valid.'
            ], 401);
        }

        // Check if company is active
        if (!$company->is_active) {
            return response()->json([
                'error' => 'Company account is inactive',
                'message' => 'Your company account has been deactivated. Please contact support.'
            ], 403);
        }

        // Check subscription status
        if (!$company->isSubscriptionActive()) {
            return response()->json([
                'error' => 'Subscription inactive',
                'message' => 'Your subscription is not active. Please renew to continue using the API.'
            ], 403);
        }

        // Attach company to request for use in controllers
        $request->merge(['company' => $company]);
        $request->setUserResolver(function () use ($company) {
            return $company;
        });

        return $next($request);
    }
}

