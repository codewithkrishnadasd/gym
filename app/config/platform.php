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

];
