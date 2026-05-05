<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Bookings\Models\Quote;

final class QuoteService
{
    public function updateStatus(Quote $quote, string $status): Quote
    {
        $quote->update(['status' => $status]);

        return $quote->fresh();
    }

    public function delete(Quote $quote): void
    {
        $quote->delete();
    }
}
