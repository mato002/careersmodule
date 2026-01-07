<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Token Pricing Plans
    |--------------------------------------------------------------------------
    |
    | These are virtual plans for how you resell OpenAI tokens to companies.
    | They are NOT connected to OpenAI billing – they are for your own
    | packaging and UI only.
    |
    | cost_per_token is your SELLING price (what you charge clients),
    | not necessarily the same as your real OpenAI cost.
    |
    */

    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'monthly_tokens' => 100_000,          // ~10 applicants if ~10k tokens each
            'monthly_price_usd' => 9.00,          // what you charge per month
            'effective_cost_per_token' => 0.00009 // 9$ / 100k
        ],
        // Use 'professional' as the plan code to match companies.subscription_plan enum
        'professional' => [
            'name' => 'Pro',
            'monthly_tokens' => 500_000,          // ~50 applicants
            'monthly_price_usd' => 29.00,
            'effective_cost_per_token' => 0.000058 // 29$ / 500k
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'monthly_tokens' => 2_000_000,         // ~200 applicants
            'monthly_price_usd' => 89.00,
            'effective_cost_per_token' => 0.0000445 // 89$ / 2M
        ],
    ],
];


