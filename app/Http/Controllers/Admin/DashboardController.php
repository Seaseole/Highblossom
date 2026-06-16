<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Inspection;
use App\Models\Quote;
use App\Models\User;
use Illuminate\View\View;

/**
 * Display the admin dashboard with key metrics.
 */
final class DashboardController
{
    /**
     * Show the admin dashboard with aggregate statistics.
     */
    public function __invoke(): View
    {
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $pendingInspections = Inspection::whereNull('ended_at')->count();
        $pendingQuotes = Quote::where('status', 'pending')->count();
        $contactedQuotes = Quote::where('status', 'contacted')->count();
        $completedQuotes = Quote::where('status', 'completed')->count();
        $cancelledQuotes = Quote::where('status', 'cancelled')->count();

        return view('dashboard', compact(
            'totalUsers',
            'totalBookings',
            'pendingInspections',
            'pendingQuotes',
            'contactedQuotes',
            'completedQuotes',
            'cancelledQuotes'
        ));
    }
}
