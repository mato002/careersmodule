<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "========================================\n";
echo "FINAL SETUP VERIFICATION\n";
echo "========================================\n\n";

$allGood = true;

// Check dependencies
echo "1. Dependencies:\n";
$pdf = class_exists('\Smalot\PdfParser\Parser');
$docx = class_exists('\PhpOffice\PhpWord\IOFactory');
echo "   " . ($pdf ? "✓" : "✗") . " PDF Parser\n";
echo "   " . ($docx ? "✓" : "✗") . " PhpWord\n";
if (!$pdf || !$docx) $allGood = false;

// Check configuration
echo "\n2. Configuration:\n";
$provider = config('ai.provider') === 'openai';
$apiKey = !empty(config('ai.api_key')) && strpos(config('ai.api_key'), 'sk-') === 0;
echo "   " . ($provider ? "✓" : "✗") . " AI Provider: " . config('ai.provider') . "\n";
echo "   " . ($apiKey ? "✓" : "✗") . " API Key: " . ($apiKey ? "Configured" : "Missing") . "\n";
if (!$provider || !$apiKey) $allGood = false;

// Check database
echo "\n3. Database:\n";
try {
    \DB::table('cv_parsed_data')->limit(1)->get();
    echo "   ✓ cv_parsed_data table\n";
} catch (\Exception $e) {
    echo "   ✗ cv_parsed_data table\n";
    $allGood = false;
}

try {
    \DB::table('jobs')->limit(1)->get();
    echo "   ✓ jobs table\n";
} catch (\Exception $e) {
    echo "   ✗ jobs table\n";
    $allGood = false;
}

echo "\n========================================\n";
if ($allGood) {
    echo "✅ ALL CHECKS PASSED!\n";
    echo "========================================\n\n";
    echo "🎉 Setup is COMPLETE!\n\n";
    echo "Next step: Start queue worker:\n";
    echo "  php artisan queue:work\n\n";
    echo "Then submit a job application with CV to test!\n\n";
} else {
    echo "⚠️  SOME CHECKS FAILED\n";
    echo "========================================\n\n";
    echo "Please fix the issues above.\n\n";
}

