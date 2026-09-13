<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('platform.dashboard.index', [
            'admin' => Auth::guard('platform')->user(),
            'organisationCount' => Organisation::query()->count(),
        ]);
    }
}
