<?php

declare(strict_types=1);

namespace App\Actions;

use App\Mail\BookingConfirmedClientMail;
use App\Models\Inspection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Create a new inspection and auto-confirm the related booking.
 */
class StoreInspectionAction
{
    /**
     * Execute the action.
     *
     * @param array<string, mixed> $data
     */
    public function execute(array $data): Inspection
    {
        return DB::transaction(function () use ($data) {
            $inspection = Inspection::create($data);

            // Auto-confirm the booking when an inspection is scheduled
            if ($inspection->booking->status !== 'confirmed') {
                $inspection->booking->update(['status' => 'confirmed']);
                Mail::to($inspection->booking->client_email)->queue(new BookingConfirmedClientMail($inspection->booking));
            }

            return $inspection;
        });
    }
}
