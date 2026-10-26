<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Inspection;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only aggregate counts for the staff app's landing screen.
 *
 * The endpoint itself is gated by `can:view dashboard`; each section is included
 * only when the member holds the ability that gates that module, so the payload
 * never leaks counts the app could not otherwise list.
 */
final class DashboardController extends Controller
{
    /**
     * Return the metric sections the member is allowed to see.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($user->can('view bookings')) {
            $data['bookings'] = [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
            ];
        }

        if ($user->can('view inspections')) {
            $open = Inspection::whereNull('ended_at');
            $data['inspections'] = [
                'total' => Inspection::count(),
                'completed' => Inspection::whereNotNull('ended_at')->count(),
                'in_progress' => (clone $open)->whereNotNull('started_at')->count(),
                'overdue' => (clone $open)
                    ->whereNull('started_at')
                    ->where('scheduled_at', '<', now())
                    ->count(),
                'scheduled' => (clone $open)
                    ->whereNull('started_at')
                    ->where('scheduled_at', '>=', now())
                    ->count(),
            ];
        }

        // Quotes are gated by the bookings abilities, mirroring the web admin.
        if ($user->can('view bookings')) {
            $data['quotes'] = [
                'total' => Quote::count(),
                'pending' => Quote::where('status', 'pending')->count(),
                'contacted' => Quote::where('status', 'contacted')->count(),
                'completed' => Quote::where('status', 'completed')->count(),
                'cancelled' => Quote::where('status', 'cancelled')->count(),
            ];
        }

        if ($user->can('view contact messages')) {
            $data['contact_messages'] = [
                'total' => ContactMessage::count(),
                'unread' => ContactMessage::unread()->count(),
            ];
        }

        return response()->json(['data' => $data]);
    }
}
