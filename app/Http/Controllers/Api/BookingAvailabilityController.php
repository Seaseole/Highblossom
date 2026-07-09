<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\Settings\SettingsManager;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Controller for returning booking slot availability.
 */
final class BookingAvailabilityController extends Controller
{
    /**
     * Return available time slots for a given date.
     */
    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after:today'],
        ]);

        $date = Carbon::parse($validated['date']);
        $workingHours = app(SettingsManager::class)->get('working_hours', []);
        $timezone = app(SettingsManager::class)->get('timezone', 'UTC');

        $slots = app(AvailabilityServiceInterface::class)->getAvailableSlots($date, $workingHours, $timezone);

        $response = [];
        foreach ($slots as $time => $available) {
            $response[] = ['time' => $time, 'available' => $available];
        }

        return response()->json($response);
    }
}
