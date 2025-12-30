<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Recipients
    |--------------------------------------------------------------------------
    |
    | Provide a comma-separated list of email addresses in the
    | CONTACT_NOTIFICATION_RECIPIENTS environment variable. Each address
    | receives a copy of new contact form submissions.
    |
    */
    'notification_recipients' => collect(
        explode(',', (string) env('CONTACT_NOTIFICATION_RECIPIENTS', ''))
    )
        ->map(fn ($address) => trim($address))
        ->filter()
        ->unique()
        ->values()
        ->all(),
];







