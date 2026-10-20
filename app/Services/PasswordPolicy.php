<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\PasswordPolicyAudit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Throwable;

/**
 * The single source of truth for how strong a password must be.
 *
 * One place reads the configured limits, so registration, the reset link, the Security
 * page and the admin user forms cannot drift apart — the three definitions this replaced
 * (Password::min(12) in the provider, Password::min(8) in PasswordUpdateRequest and a
 * bare min:8 in UserRequest) disagreed with each other and with the hint shown in the
 * form. Password::defaults() in PasswordRulesServiceProvider is the only registration
 * point and delegates here, so every consumer of Password::default() picks up a save
 * immediately.
 *
 * Bounds are not configurable. bcrypt truncates silently at 72 bytes, so a ceiling above
 * that would let two different passphrases hash to the same value and both sign in.
 */
final class PasswordPolicy
{
    /**
     * Hard limits the stored settings are clamped to, whatever an admin submits.
     */
    public const MIN_FLOOR = 8;

    public const MAX_FLOOR = 12;

    public const MAX_CEILING = 72;

    /**
     * Used when a setting row is missing and when the database cannot be read at all,
     * which covers migrations, first boot and a cache backend pointing at the same
     * connection. Deliberately the policy the app already enforced, so a failure to
     * read settings cannot silently weaken a password form.
     *
     * @var array<string, mixed>
     */
    private const FALLBACK = [
        'password_min_length' => 12,
        'password_max_length' => 64,
        'password_require_mixed_case' => true,
        'password_require_numbers' => true,
        'password_require_symbols' => true,
        'password_check_breached' => false,
    ];

    /**
     * @var array<string, mixed>|null
     */
    private ?array $resolved = null;

    /**
     * The configured policy, clamped to the hard bounds.
     *
     * @return array{min: int, max: int, mixed_case: bool, numbers: bool, symbols: bool, breached: bool}
     */
    public function current(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $raw = self::FALLBACK;

        try {
            $raw = [
                'password_min_length' => CompanySetting::get('password_min_length', self::FALLBACK['password_min_length']),
                'password_max_length' => CompanySetting::get('password_max_length', self::FALLBACK['password_max_length']),
                'password_require_mixed_case' => CompanySetting::get('password_require_mixed_case', self::FALLBACK['password_require_mixed_case']),
                'password_require_numbers' => CompanySetting::get('password_require_numbers', self::FALLBACK['password_require_numbers']),
                'password_require_symbols' => CompanySetting::get('password_require_symbols', self::FALLBACK['password_require_symbols']),
                'password_check_breached' => CompanySetting::get('password_check_breached', self::FALLBACK['password_check_breached']),
            ];
        } catch (Throwable $e) {
            Log::warning('Password policy settings unreadable, using safe defaults.', ['reason' => $e->getMessage()]);

            $raw = self::FALLBACK;
        }

        $min = (int) $raw['password_min_length'];
        $max = (int) $raw['password_max_length'];

        // A stored pair can only be produced by this class, but a hand-edited row or a
        // half-finished migration must not be able to widen the rule past 72 bytes.
        $min = max(self::MIN_FLOOR, min($min, self::MAX_CEILING));
        $max = max(self::MAX_FLOOR, min($max, self::MAX_CEILING));

        if ($min > $max) {
            $min = $max;
        }

        return $this->resolved = [
            'min' => $min,
            'max' => $max,
            'mixed_case' => (bool) $raw['password_require_mixed_case'],
            'numbers' => (bool) $raw['password_require_numbers'],
            'symbols' => (bool) $raw['password_require_symbols'],
            'breached' => (bool) $raw['password_check_breached'],
        ];
    }

    /**
     * The same policy as the rule Laravel validates with.
     */
    public function rule(): Password
    {
        $policy = $this->current();

        $rule = Password::min($policy['min'])->max($policy['max']);

        if ($policy['mixed_case']) {
            $rule = $rule->mixedCase();
        }

        if ($policy['numbers']) {
            $rule = $rule->numbers();
        }

        if ($policy['symbols']) {
            $rule = $rule->symbols();
        }

        if ($policy['breached']) {
            $rule = $rule->uncompromised();
        }

        return $rule;
    }

    /**
     * Per-rule checklist for the live hint, in the words the server uses when it rejects.
     *
     * Each item is the tail of the matching validation sentence, so the hint and the
     * error that replaces it say the same thing about the same number.
     *
     * @return list<array{id: string, label: string, min: int, max: int}>
     */
    public function checklist(): array
    {
        $policy = $this->current();

        $items = [
            [
                'id' => 'min',
                'label' => 'at least '.$policy['min'].' characters',
                'min' => $policy['min'],
                'max' => 0,
            ],
            [
                'id' => 'max',
                'label' => 'no more than '.$policy['max'].' characters',
                'min' => 0,
                'max' => $policy['max'],
            ],
        ];

        if ($policy['mixed_case']) {
            $items[] = ['id' => 'mixed', 'label' => 'one uppercase and one lowercase letter', 'min' => 0, 'max' => 0];
        }

        if ($policy['numbers']) {
            $items[] = ['id' => 'numbers', 'label' => 'at least one number', 'min' => 0, 'max' => 0];
        }

        if ($policy['symbols']) {
            $items[] = ['id' => 'symbols', 'label' => 'at least one symbol', 'min' => 0, 'max' => 0];
        }

        return $items;
    }

    /**
     * The active policy as one sentence, for the settings page and any plain hint.
     */
    public function summary(): string
    {
        $policy = $this->current();

        $parts = [$policy['min'].' to '.$policy['max'].' characters'];

        if ($policy['mixed_case']) {
            $parts[] = 'one uppercase and one lowercase letter';
        }

        if ($policy['numbers']) {
            $parts[] = 'at least one number';
        }

        if ($policy['symbols']) {
            $parts[] = 'at least one symbol';
        }

        if ($policy['breached']) {
            $parts[] = 'not seen in a known data leak';
        }

        return implode(', ', array_slice($parts, 0, -1)).
            (count($parts) > 1 ? ' and '.end($parts) : $parts[0]);
    }

    /**
     * Persist a new policy and record who changed which value.
     *
     * Each key is written with the type CompanySetting::get() coerces on, because a
     * boolean stored as text comes back as the string "0" and filter_var() reads that
     * as false only by luck — an array or number stored the wrong way round returns a
     * string and the toggle flips itself.
     *
     * @param array<string, mixed> $values
     */
    public function save(array $values, User $actor): void
    {
        $before = $this->current();
        $after = [
            'min' => (int) $values['password_min_length'],
            'max' => (int) $values['password_max_length'],
            'mixed_case' => (bool) ($values['password_require_mixed_case'] ?? false),
            'numbers' => (bool) ($values['password_require_numbers'] ?? false),
            'symbols' => (bool) ($values['password_require_symbols'] ?? false),
            'breached' => (bool) ($values['password_check_breached'] ?? false),
        ];

        CompanySetting::set('password_min_length', $after['min'], 'number');
        CompanySetting::set('password_max_length', $after['max'], 'number');
        CompanySetting::set('password_require_mixed_case', $after['mixed_case'] ? '1' : '0', 'boolean');
        CompanySetting::set('password_require_numbers', $after['numbers'] ? '1' : '0', 'boolean');
        CompanySetting::set('password_require_symbols', $after['symbols'] ? '1' : '0', 'boolean');
        CompanySetting::set('password_check_breached', $after['breached'] ? '1' : '0', 'boolean');

        // Reads through the same memo the forms use, so the audit holds the resolved
        // numbers rather than whatever a clamped input happened to arrive as.
        $this->resolved = null;
        $after = $this->current();

        $changes = $this->diff($before, $after);

        if ($changes !== []) {
            $this->record($changes, $actor);
        }
    }

    /**
     * The most recent policy changes, newest first.
     *
     * @return array<int, array{id: int, actor: string, changes: string, at: string}>
     */
    public function auditTrail(int $limit = 5): array
    {
        try {
            if (! DB::getSchemaBuilder()->hasTable('password_policy_audits')) {
                return [];
            }

            return PasswordPolicyAudit::with('user')->latest()->limit($limit)->get()
                ->map(fn (PasswordPolicyAudit $audit): array => [
                    'id' => $audit->id,
                    'actor' => $audit->user?->email ?? 'Deleted user',
                    'changes' => collect($audit->changes)
                        ->map(fn (array $change): string => $change['key'].' from '.var_export($change['from'], true).' to '.var_export($change['to'], true))
                        ->implode(', '),
                    'at' => $audit->created_at?->diffForHumans() ?? '',
                ])
                ->all();
        } catch (Throwable $e) {
            Log::warning('Password policy audit trail unreadable.', ['reason' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Field-by-field differences between two resolved policies.
     *
     * @param array<string, mixed> $before
     * @param array<string, mixed> $after
     *
     * @return list<array{key: string, from: mixed, to: mixed}>
     */
    private function diff(array $before, array $after): array
    {
        $changes = [];

        foreach ($after as $key => $value) {
            if ($before[$key] !== $value) {
                $changes[] = ['key' => $key, 'from' => $before[$key], 'to' => $value];
            }
        }

        return $changes;
    }

    /**
     * Write one audit row. Never lets a logging failure undo the save it describes.
     *
     * @param list<array{key: string, from: mixed, to: mixed}> $changes
     */
    private function record(array $changes, User $actor): void
    {
        try {
            PasswordPolicyAudit::create([
                'user_id' => $actor->id,
                'changes' => $changes,
                'ip_address' => request()->ip(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Password policy changed but could not be audited.', [
                'reason' => $e->getMessage(),
                'user_id' => $actor->id,
            ]);
        }
    }
}
