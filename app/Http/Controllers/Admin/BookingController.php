<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Bookings\Models\Booking;
use Illuminate\View\View;

final class BookingController
{
    public function index(): View
    {
        $bookings = Booking::query()->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        return view('admin.bookings.show', compact('booking'));
    }
}
