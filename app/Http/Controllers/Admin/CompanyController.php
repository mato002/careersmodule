<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index(Request $request): View
    {
        $query = Company::withCount(['users', 'tokenAllocations']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Subscription status filter
        if ($request->filled('subscription_status') && $request->string('subscription_status') !== 'all') {
            $query->where('subscription_status', $request->string('subscription_status'));
        }

        // Active status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $totalCompaniesCount = Company::count();
        $activeCompaniesCount = Company::where('is_active', true)->count();
        $activeSubscriptionsCount = Company::where('subscription_status', 'active')->count();
        $filteredCompaniesCount = $query->count();

        $companies = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.companies.index', compact(
            'companies',
            'totalCompaniesCount',
            'activeCompaniesCount',
            'activeSubscriptionsCount',
            'filteredCompaniesCount'
        ));
    }

    /**
     * Show the form for creating a new company
     */
    public function create(): View
    {
        $company = new Company([
            'subscription_plan' => 'starter',
            'subscription_status' => 'trial',
            'is_active' => true,
            'ai_enabled' => true,
            'ai_auto_sieve' => false,
        ]);

        return view('admin.companies.create', compact('company'));
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:companies,slug',
            'domain' => 'nullable|string|max:255|unique:companies,domain',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'subscription_plan' => 'required|in:starter,professional,enterprise',
            'subscription_status' => 'required|in:active,suspended,cancelled,trial',
            'subscription_expires_at' => 'nullable|date',
            'is_active' => 'boolean',
            'ai_enabled' => 'boolean',
            'ai_auto_sieve' => 'boolean',
            'ai_threshold' => 'nullable|numeric|min:0|max:100',
            'token_package_type' => 'nullable|string',
            'token_limit_per_month' => 'nullable|integer|min:0',
            'token_alert_threshold' => 'nullable|integer|min:0|max:100',
            
            // User creation fields
            'create_admin_user' => 'boolean',
            'admin_name' => 'required_if:create_admin_user,1|string|max:255',
            'admin_email' => 'required_if:create_admin_user,1|email|max:255|unique:users,email',
            'admin_password' => 'required_if:create_admin_user,1|string|min:8',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            // Ensure uniqueness
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (Company::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        // Generate API key
        $validated['api_key'] = Str::random(32);

        // Handle boolean fields
        $validated['is_active'] = $request->has('is_active');
        $validated['ai_enabled'] = $request->has('ai_enabled');
        $validated['ai_auto_sieve'] = $request->has('ai_auto_sieve');

        // Create company
        $company = Company::create($validated);

        // Create admin user if requested
        if ($request->has('create_admin_user') && $request->boolean('create_admin_user')) {
            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'client',
                'company_id' => $company->id,
                'email_verified_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified company
     */
    public function show(Company $company): View
    {
        $company->load(['users', 'tokenAllocations', 'tokenUsageLogs' => function ($query) {
            $query->latest()->limit(10);
        }]);

        $tokenBalance = app(\App\Services\TokenService::class)->getBalance($company->id);

        return view('admin.companies.show', compact('company', 'tokenBalance'));
    }

    /**
     * Show the form for editing the specified company
     */
    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:companies,slug,' . $company->id,
            'domain' => 'nullable|string|max:255|unique:companies,domain,' . $company->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'subscription_plan' => 'required|in:starter,professional,enterprise',
            'subscription_status' => 'required|in:active,suspended,cancelled,trial',
            'subscription_expires_at' => 'nullable|date',
            'is_active' => 'boolean',
            'ai_enabled' => 'boolean',
            'ai_auto_sieve' => 'boolean',
            'ai_threshold' => 'nullable|numeric|min:0|max:100',
            'token_package_type' => 'nullable|string',
            'token_limit_per_month' => 'nullable|integer|min:0',
            'token_alert_threshold' => 'nullable|integer|min:0|max:100',
        ]);

        // Handle boolean fields
        $validated['is_active'] = $request->has('is_active');
        $validated['ai_enabled'] = $request->has('ai_enabled');
        $validated['ai_auto_sieve'] = $request->has('ai_auto_sieve');

        $company->update($validated);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('success', 'Company updated successfully!');
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company): RedirectResponse
    {
        // Prevent deletion if company has users or data
        if ($company->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete company with associated users. Please remove users first.']);
        }

        $companyName = $company->name;
        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', "Company '{$companyName}' deleted successfully!");
    }

    /**
     * Regenerate API key for the company
     */
    public function regenerateApiKey(Company $company): RedirectResponse
    {
        $company->update(['api_key' => Str::random(32)]);

        return back()->with('success', 'API key regenerated successfully!');
    }

    /**
     * Toggle company active status
     */
    public function toggleStatus(Company $company): RedirectResponse
    {
        $company->update(['is_active' => !$company->is_active]);

        $status = $company->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Company {$status} successfully!");
    }
}

