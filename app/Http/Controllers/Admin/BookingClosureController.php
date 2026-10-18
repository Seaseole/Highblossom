<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingClosedDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Manage the dates the shop takes no bookings at all.
 *
 * These sit above the weekly hours: a public holiday that falls on a Tuesday has
 * to close that Tuesday, and marking the weekly rule closed instead would close
 * every Tuesday with it.
 */
final class BookingClosureController extends Controller
{
    /**
     * List the closures still ahead, plus enough history to explain a recent gap.
     */
    public function index(): View
    {
        return view('admin.bookings.closures', [
            'upcoming' => BookingClosedDay::where('date', '>=', Date::today()->toDateString())
                ->orderBy('date')
                ->get(),
            'recent' => BookingClosedDay::where('date', '<', Date::today()->toDateString())
                ->orderByDesc('date')
                ->limit(10)
                ->get(),
        ]);
    }

    /**
     * Block a single date.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                Rule::unique('booking_closed_days', 'date'),
            ],
            'reason' => ['nullable', 'string', 'max:191'],
        ], [
            'date.after_or_equal' => 'Only future dates can be closed. Past bookings are not affected.',
            'date.unique' => 'That date is already closed.',
        ]);

        BookingClosedDay::create([
            'date' => Date::parse($validated['date'])->toDateString(),
            'reason' => $validated['reason'] ?? null,
        ]);

        return redirect()
            ->route('admin.bookings.closures.index')
            ->with('success', 'That date is now closed to bookings.');
    }

    /**
     * Lift a block.
     */
    public function destroy(BookingClosedDay $closure): RedirectResponse
    {
        $closure->delete();

        return redirect()
            ->route('admin.bookings.closures.index')
            ->with('success', 'Bookings are open again on '.Date::parse($closure->date)->format('d M Y').'.');
    }
}
