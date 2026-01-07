<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "Testing OpenAI API Connection\n";
echo "========================================\n\n";

$apiKey = config('ai.api_key');
$model = config('ai.model', 'gpt-4o-mini');

if (empty($apiKey)) {
    echo "❌ ERROR: API key not configured\n";
    exit(1);
}

echo "API Key: " . substr($apiKey, 0, 20) . "...\n";
echo "Model: {$model}\n";
echo "\nSending test request to OpenAI...\n\n";

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
        'model' => $model,
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant. Respond briefly.'
            ],
            [
                'role' => 'user',
                'content' => 'Say "OpenAI connection successful!" if you can read this.'
            ],
        ],
        'max_tokens' => 20,
        'temperature' => 0.3,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        $tokens = $data['usage']['total_tokens'] ?? 0;
        
        echo "✅ SUCCESS!\n";
        echo "Response: " . trim($content) . "\n";
        echo "Tokens used: {$tokens}\n";
        echo "\n🎉 OpenAI integration is working perfectly!\n";
        exit(0);
    } else {
        $status = $response->status();
        $body = $response->body();
        
        echo "❌ ERROR: HTTP {$status}\n";
        
        if ($status === 401) {
            echo "Reason: Unauthorized - Invalid API key\n";
            echo "→ Check your OPENAI_API_KEY in .env file\n";
        } elseif ($status === 429) {
            echo "Reason: Rate limit exceeded\n";
            echo "→ Wait a few minutes or check your OpenAI account limits\n";
        } else {
            echo "Response: " . substr($body, 0, 200) . "\n";
        }
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

