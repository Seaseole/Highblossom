<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Individual votes cast in polls with IP tracking.
 * Maps to the `poll_votes` database table.
 */
class PollVote extends Model
{
    protected $fillable = [
        'poll_id',
        'option_index',
        'ip_address',
        'user_id',
    ];

    /**
     * Get the poll this vote belongs to.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Get the user who cast this vote (if authenticated).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
