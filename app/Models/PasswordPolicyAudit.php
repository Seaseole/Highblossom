<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'changes', 'ip_address'])]
/**
 * One saved change to the password policy, with the value it moved from.
 *
 * The table is append-only: nothing updates or deletes these rows, so the trail cannot
 * be rewritten by the account it describes. The password itself is never part of a
 * change set, only the length and composition rules.
 *
 * Maps to the `password_policy_audits` database table.
 */
final class PasswordPolicyAudit extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    /**
     * Get the administrator who saved the change.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
