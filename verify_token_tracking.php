<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n=== TOKEN TRACKING VERIFICATION ===\n\n";

$company = \App\Models\Company::first();
if (!$company) {
    echo "❌ No company found!\n";
    exit(1);
}

echo "Company: {$company->name} (ID: {$company->id})\n\n";

// Check allocation
$allocation = $company->activeTokenAllocation();
if (!$allocation) {
    echo "❌ No active allocation found!\n";
    echo "   → Go to Token Management and allocate tokens first.\n";
    exit(1);
}

echo "✓ Active Allocation:\n";
echo "   - ID: {$allocation->id}\n";
echo "   - Allocated: " . number_format($allocation->allocated_tokens) . "\n";
echo "   - Used: " . number_format($allocation->used_tokens) . "\n";
echo "   - Remaining: " . number_format($allocation->remaining_tokens) . "\n\n";

// Check applications
$totalApps = \App\Models\JobApplication::count();
$appsWithCompany = \App\Models\JobApplication::whereNotNull('company_id')->count();
$appsWithoutCompany = $totalApps - $appsWithCompany;

echo "Applications:\n";
echo "   - Total: {$totalApps}\n";
echo "   - With company_id: {$appsWithCompany}\n";
echo "   - Without company_id: {$appsWithoutCompany}\n";

if ($appsWithoutCompany > 0) {
    echo "\n⚠️  {$appsWithoutCompany} applications still missing company_id!\n";
    echo "   → Run the SQL in fix_token_tracking.sql to fix this.\n";
}

// Check usage logs
$logs = \App\Models\TokenUsageLog::where('company_id', $company->id)->count();
echo "\nToken Usage Logs: {$logs}\n";

if ($logs > 0) {
    $recent = \App\Models\TokenUsageLog::where('company_id', $company->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    echo "Recent logs:\n";
    foreach ($recent as $log) {
        $appName = $log->jobApplication ? $log->jobApplication->name : "App #{$log->job_application_id}";
        echo "   • {$log->created_at->format('Y-m-d H:i')}: {$log->operation_type} - {$log->tokens_used} tokens ({$appName})\n";
    }
}

// Test company ID resolution
echo "\nTesting Company ID Resolution:\n";
$testApp = \App\Models\JobApplication::whereNotNull('company_id')->first();
if ($testApp) {
    $aiService = new \App\Services\AIAnalysisService();
    $reflection = new ReflectionClass($aiService);
    $method = $reflection->getMethod('getCompanyId');
    $method->setAccessible(true);
    $resolvedCompanyId = $method->invoke($aiService, $testApp);
    echo "   - Test App #{$testApp->id}: company_id = {$testApp->company_id}\n";
    echo "   - Resolved company_id = {$resolvedCompanyId}\n";
    if ($resolvedCompanyId == $testApp->company_id) {
        echo "   ✓ Company ID resolution working correctly!\n";
    } else {
        echo "   ⚠️  Company ID resolution mismatch!\n";
    }
} else {
    echo "   ⚠️  No applications with company_id to test\n";
}

echo "\n=== SUMMARY ===\n";
if ($appsWithoutCompany == 0 && $allocation) {
    echo "✓ All systems ready! Token tracking should work.\n";
    echo "  → Try re-sieving an application to test.\n";
} else {
    echo "⚠️  Issues found:\n";
    if ($appsWithoutCompany > 0) {
        echo "  - Fix applications missing company_id\n";
    }
    if (!$allocation) {
        echo "  - Allocate tokens to company\n";
    }
}

echo "\n";

