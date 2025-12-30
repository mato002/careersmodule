<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyTokenAllocation;
use App\Models\TokenPurchase;
use App\Models\TokenUsageLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
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
     * Display token balance and usage
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('client.dashboard')
                ->with('error', 'No company associated with your account.');
        }

        $balance = $this->tokenService->getBalance($company->id);
        $usageStats = $this->tokenService->getUsageStats($company->id, 'month');

        // Get recent usage logs
        $recentLogs = TokenUsageLog::where('company_id', $company->id)
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->paginate(20);

        // Get allocations
        $allocations = CompanyTokenAllocation::where('company_id', $company->id)
            ->with('tokenPurchase')
            ->orderBy('allocated_at', 'desc')
            ->get();

        return view('client.tokens.index', compact(
            'company',
            'balance',
            'usageStats',
            'recentLogs',
            'allocations'
        ));
    }

    /**
     * Show purchase tokens page
     */
    public function purchase()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('client.dashboard')
                ->with('error', 'No company associated with your account.');
        }

        // Get available token packages
        $packages = [
            [
                'name' => 'Starter',
                'tokens' => 10000,
                'price' => 50.00,
                'cost_per_token' => 0.005,
                'description' => 'Perfect for small businesses',
            ],
            [
                'name' => 'Professional',
                'tokens' => 50000,
                'price' => 200.00,
                'cost_per_token' => 0.004,
                'description' => 'Ideal for growing companies',
            ],
            [
                'name' => 'Enterprise',
                'tokens' => 200000,
                'price' => 600.00,
                'cost_per_token' => 0.003,
                'description' => 'For large organizations',
            ],
        ];

        return view('client.tokens.purchase', compact('company', 'packages'));
    }

    /**
     * Process token purchase request
     */
    public function storePurchase(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('client.dashboard')
                ->with('error', 'No company associated with your account.');
        }

        $request->validate([
            'package' => ['required', 'in:starter,professional,enterprise'],
            'payment_method' => ['required', 'in:card,bank_transfer'],
        ]);

        // Define packages
        $packages = [
            'starter' => ['tokens' => 10000, 'price' => 50.00, 'cost_per_token' => 0.005],
            'professional' => ['tokens' => 50000, 'price' => 200.00, 'cost_per_token' => 0.004],
            'enterprise' => ['tokens' => 200000, 'price' => 600.00, 'cost_per_token' => 0.003],
        ];

        $package = $packages[$request->package];

        // Create token purchase record
        DB::beginTransaction();
        try {
            $purchase = TokenPurchase::create([
                'purchase_date' => now(),
                'total_tokens' => $package['tokens'],
                'cost_per_token' => $package['cost_per_token'],
                'total_cost' => $package['price'],
                'provider' => 'internal',
                'status' => $request->payment_method === 'card' ? 'pending' : 'pending_approval',
                'notes' => "Purchase request from client: {$company->name}",
            ]);

            // If payment is by card (assume instant), allocate tokens
            if ($request->payment_method === 'card') {
                // In a real implementation, you would integrate with a payment gateway
                // For now, we'll mark it as pending and require admin approval
                $purchase->update(['status' => 'pending_approval']);
            }

            DB::commit();

            return redirect()->route('client.tokens.index')
                ->with('success', 'Token purchase request submitted. Tokens will be allocated after payment confirmation.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process purchase request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show usage statistics
     */
    public function usage(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('client.dashboard');
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
            ->where('created_at', '>=', $startDate)
            ->with('jobApplication')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('client.tokens.usage', compact('company', 'usageStats', 'logs', 'period'));
    }
}
