<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyTokenAllocation;
use App\Models\TokenPurchase;
use App\Models\TokenUsageLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Display token dashboard
     */
    public function index()
    {
        try {
            // Get or create default company (for single-tenant setup)
            $company = Company::first();
            
            if (!$company) {
                // Create a default company if none exists
                $company = Company::create([
                    'name' => config('app.name', 'Fortress Lenders'),
                    'slug' => 'default',
                    'email' => config('mail.from.address'),
                    'subscription_plan' => 'professional',
                    'subscription_status' => 'active',
                    'is_active' => true,
                    'ai_enabled' => true,
                    'ai_auto_sieve' => false,
                    'ai_threshold' => 7.0,
                    'token_package_type' => 'prepaid',
                    'token_alert_threshold' => 20,
                ]);
            }
        } catch (\Exception $e) {
            // If companies table doesn't exist, show migration message
            return redirect()->route('admin.dashboard')
                ->with('error', 'Token management requires database migrations. Please run: php artisan migrate');
        }

        $balance = $this->tokenService->getBalance($company->id);
        $usageStats = $this->tokenService->getUsageStats($company->id, 'month');
        
        // Get recent usage logs
        $recentLogs = TokenUsageLog::where('company_id', $company->id)
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get allocations
        $allocations = CompanyTokenAllocation::where('company_id', $company->id)
            ->with('tokenPurchase')
            ->orderBy('allocated_at', 'desc')
            ->get();

        return view('admin.tokens.index', compact(
            'company',
            'balance',
            'usageStats',
            'recentLogs',
            'allocations'
        ));
    }

    /**
     * Show usage statistics
     */
    public function usage(Request $request)
    {
        $company = Company::first();
        if (!$company) {
            return redirect()->route('admin.tokens.index');
        }
        $period = $request->get('period', 'month');

        $usageStats = $this->tokenService->getUsageStats($company->id, $period);

        // Get detailed logs for the period
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $logs = TokenUsageLog::where('company_id', $company->id)
            ->whereBetween('created_at', [$startDate, now()])
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.tokens.usage', compact('usageStats', 'logs', 'period'));
    }

    /**
     * Show token purchase management (admin only)
     */
    public function purchases()
    {
        $purchases = TokenPurchase::with('allocations')
            ->orderBy('purchase_date', 'desc')
            ->paginate(20);

        return view('admin.tokens.purchases', compact('purchases'));
    }

    /**
     * Create new token purchase
     */
    public function createPurchase(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'total_tokens' => 'required|integer|min:1',
            'cost_per_token' => 'required|numeric|min:0',
            'provider' => 'required|string|in:openai,anthropic,custom',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $purchase = $this->tokenService->createPurchase($validated);

        return redirect()->route('admin.tokens.purchases')
            ->with('success', "Token purchase of {$purchase->total_tokens} tokens created successfully.");
    }

    /**
     * Allocate tokens to a company
     */
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'token_amount' => 'required|integer|min:1',
            'token_purchase_id' => 'nullable|exists:token_purchases,id',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $allocation = $this->tokenService->allocateTokens(
                $validated['company_id'],
                $validated['token_amount'],
                $validated['token_purchase_id'] ?? null,
                $validated['expires_at'] ? new \DateTime($validated['expires_at']) : null,
                $validated['notes'] ?? null
            );

            return redirect()->back()
                ->with('success', "Successfully allocated {$validated['token_amount']} tokens to company.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get token balance via API
     */
    public function balance(Request $request)
    {
        $company = Company::first(); // Later: get from API key or auth
        
        if (!$company) {
            return response()->json(['error' => 'No company found'], 404);
        }
        
        $balance = $this->tokenService->getBalance($company->id);
        
        return response()->json($balance);
    }

    /**
     * Get usage statistics via API
     */
    public function stats(Request $request)
    {
        $company = Company::first(); // Later: get from API key or auth
        
        if (!$company) {
            return response()->json(['error' => 'No company found'], 404);
        }
        
        $period = $request->get('period', 'month');

        $stats = $this->tokenService->getUsageStats($company->id, $period);

        return response()->json($stats);
    }
}

