<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which AI provider to use for CV analysis and application
    | processing. Supported providers: openai, anthropic, local
    |
    */

    'provider' => env('AI_PROVIDER', 'openai'),

    'api_key' => env('AI_API_KEY', env('OPENAI_API_KEY')),

    'api_url' => env('AI_API_URL'),

    'model' => env('AI_MODEL', 'gpt-4o-mini'),

    /*
    |--------------------------------------------------------------------------
    | Local LLM Configuration (for Ollama, etc.)
    |--------------------------------------------------------------------------
    */

    'local_api_url' => env('AI_LOCAL_API_URL', 'http://localhost:11434/api/generate'),

    /*
    |--------------------------------------------------------------------------
    | AI Analysis Settings
    |--------------------------------------------------------------------------
    */

    'enable_cv_parsing' => env('AI_ENABLE_CV_PARSING', true),

    'enable_ai_analysis' => env('AI_ENABLE_AI_ANALYSIS', true),

    'enable_auto_sieving' => env('AI_ENABLE_AUTO_SIEVING', true),

    'analysis_temperature' => env('AI_TEMPERATURE', 0.3),

    'max_tokens' => env('AI_MAX_TOKENS', 2000),

    /*
    |--------------------------------------------------------------------------
    | Confidence Thresholds
    |--------------------------------------------------------------------------
    */

    'min_confidence_for_auto_pass' => env('AI_MIN_CONFIDENCE_AUTO_PASS', 0.85),

    'min_confidence_for_auto_reject' => env('AI_MIN_CONFIDENCE_AUTO_REJECT', 0.80),

];


