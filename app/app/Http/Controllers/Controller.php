<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Laravel no longer includes AuthorizesRequests on the base controller, but
 * every tenant-facing controller here must be able to call `$this->authorize()`
 * — policy checks are required on every protected action (MEP.md 8.3).
 */
abstract class Controller
{
    use AuthorizesRequests;
}
