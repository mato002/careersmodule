<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    /**
     * Accept cookies and set consent cookie
     */
    public function accept(Request $request): JsonResponse
    {
        $cookie = cookie('cookie_consent', 'accepted', 60 * 24 * 365); // 1 year
        
        return response()->json([
            'success' => true,
            'message' => 'Cookie consent accepted'
        ])->cookie($cookie);
    }

    /**
     * Reject cookies (only essential cookies)
     */
    public function reject(Request $request): JsonResponse
    {
        $cookie = cookie('cookie_consent', 'rejected', 60 * 24 * 365); // 1 year
        
        return response()->json([
            'success' => true,
            'message' => 'Cookie consent rejected'
        ])->cookie($cookie);
    }

    /**
     * Check if user has given consent
     */
    public function check(Request $request): JsonResponse
    {
        $consent = $request->cookie('cookie_consent');
        
        return response()->json([
            'has_consent' => !empty($consent),
            'consent_status' => $consent ?? 'none'
        ]);
    }
}

