<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Platform Hostname
    |--------------------------------------------------------------------------
    |
    | The dedicated hostname that serves the platform-admin ("root user")
    | surface — organisation setup, domain mapping, tenant health. This host
    | is architecturally separate from every tenant domain in the `domains`
    | table; requests to it never resolve a tenant. See MEP.md Section 3.3.
    |
    */

    'hostname' => env('PLATFORM_HOSTNAME', 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Horizon Access
    |--------------------------------------------------------------------------
    |
    | WhatsApp numbers (bare international digits) of the platform operators
    | allowed into the Horizon dashboard outside local development. Empty by
    | default, so the dashboard stays closed until someone is named.
    |
    */

    'horizon_phones' => array_filter(explode(',', (string) env('PLATFORM_HORIZON_PHONES', ''))),

    /*
    |--------------------------------------------------------------------------
    | Password Reset Links
    |--------------------------------------------------------------------------
    |
    | How long a password reset link stays usable. Short on purpose: the link
    | is handed over on WhatsApp, so it lives in a chat history that outlasts
    | any legitimate need for it. Long enough that someone who reads the
    | message an hour later can still use it, and no longer.
    |
    */

    'password_reset_link_minutes' => (int) env('PASSWORD_RESET_LINK_MINUTES', 60),

];
