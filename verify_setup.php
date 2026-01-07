<?php
/**
 * Quick Setup Verification Script
 * Run: php verify_setup.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$checks = [];

// Check 1: Dependencies
$checks[] = [
    'name' => 'PDF Parser',
    'status' => class_exists('\Smalot\PdfParser\Parser'),
    'fix' => 'Run: composer install'
];

$checks[] = [
    'name' => 'PhpWord',
    'status' => class_exists('\PhpOffice\PhpWord\IOFactory'),
    'fix' => 'Run: composer install'
];

// Check 2: Configuration
$checks[] = [
    'name' => 'AI Provider',
    'status' => config('ai.provider') === 'openai',
    'fix' => 'Set AI_PROVIDER=openai in .env'
];

$checks[] = [
    'name' => 'API Key',
    'status' => !empty(config('ai.api_key')) && strpos(config('ai.api_key'), 'sk-') === 0,
    'fix' => 'Add OPENAI_API_KEY to .env'
];

// Check 3: Database
try {
    \DB::table('cv_parsed_data')->limit(1)->get();
    $checks[] = ['name' => 'cv_parsed_data table', 'status' => true, 'fix' => ''];
} catch (\Exception $e) {
    $checks[] = ['name' => 'cv_parsed_data table', 'status' => false, 'fix' => 'Run: php artisan migrate'];
}

try {
    \DB::table('jobs')->limit(1)->get();
    $checks[] = ['name' => 'jobs table', 'status' => true, 'fix' => ''];
} catch (\Exception $e) {
    $checks[] = ['name' => 'jobs table', 'status' => false, 'fix' => 'Run: php artisan queue:table && php artisan migrate'];
}

// Output
echo "\n" . str_repeat("=", 50) . "\n";
echo "OpenAI Setup Verification\n";
echo str_repeat("=", 50) . "\n\n";

$passed = 0;
$failed = 0;

foreach ($checks as $check) {
    $icon = $check['status'] ? '✓' : '✗';
    $status = $check['status'] ? 'PASS' : 'FAIL';
    echo sprintf("%s %-30s %s\n", $icon, $check['name'], $status);
    
    if (!$check['status'] && !empty($check['fix'])) {
        echo "   → " . $check['fix'] . "\n";
    }
    
    if ($check['status']) {
        $passed++;
    } else {
        $failed++;
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Results: {$passed} passed, {$failed} failed\n";
echo str_repeat("=", 50) . "\n\n";

if ($failed === 0) {
    echo "✅ All checks passed! Setup is complete.\n";
    echo "\nNext step: Start queue worker:\n";
    echo "  php artisan queue:work\n\n";
} else {
    echo "⚠️  Some checks failed. Please fix the issues above.\n\n";
}

exit($failed > 0 ? 1 : 0);

