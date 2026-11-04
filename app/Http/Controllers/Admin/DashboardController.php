<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\DashboardService;
use Illuminate\View\View;

/**
 * Display the day desk: today measured against the shop's own trading hours.
 */
final class DashboardController
{
    public function __construct(private readonly DashboardService $desk) {}

    /**
     * Show the desk. All measurement lives in the service; this only picks the view.
     */
    public function __invoke(): View
    {
        return view('dashboard', $this->desk->desk());
    }
}
