<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Inspection;

/**
 * Update an existing inspection.
 */
class UpdateInspectionAction
{
    /**
     * Execute the action.
     *
     * @param array<string, mixed> $data
     */
    public function execute(Inspection $inspection, array $data): Inspection
    {
        $inspection->update($data);

        // Auto-complete booking when ended_at is set
        if (! empty($data['ended_at']) && $inspection->booking->status !== 'completed') {
            $inspection->booking->update(['status' => 'completed']);
        }

        return $inspection->fresh();
    }
}
