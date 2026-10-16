<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

/**
 * One cached section of the system overview: its lines, how long measuring them
 * took, and when they were measured.
 *
 * Serialized as plain scalars so a cached section can never revive an Eloquent
 * model, which is what makes the database cache store portable across runtimes.
 */
final class ProbeResult
{
    /**
     * @param string          $key         Allowlisted probe key.
     * @param string          $label       Card heading.
     * @param string          $description Card sub-heading.
     * @param string          $icon        Heroicons name for the card header.
     * @param list<ProbeItem> $items
     * @param ProbeStatus     $status      Worst severity of the lines, or the section's own state.
     * @param int|null        $elapsedMs   Wall-clock milliseconds the probe spent measuring.
     * @param int|null        $warmAt      Unix timestamp the measurement was taken.
     * @param string|null     $error       Failure message when the probe threw.
     */
    private function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $description,
        public readonly string $icon,
        public readonly array $items,
        public readonly ProbeStatus $status,
        public readonly ?int $elapsedMs = null,
        public readonly ?int $warmAt = null,
        public readonly ?string $error = null,
    ) {}

    /**
     * Build a section from measured lines.
     *
     * @param SystemProbe     $probe     Probe that produced the lines.
     * @param list<ProbeItem> $items     Measured lines.
     * @param int             $elapsedMs Wall-clock milliseconds the probe spent measuring.
     */
    public static function measured(SystemProbe $probe, array $items, int $elapsedMs): self
    {
        return new self(
            key: $probe->key(),
            label: $probe->label(),
            description: $probe->description(),
            icon: $probe->icon(),
            items: $items,
            status: self::worstStatus($items),
            elapsedMs: $elapsedMs,
            warmAt: time(),
        );
    }

    /**
     * Build a section whose probe threw; the rest of the page is unaffected.
     *
     * @param SystemProbe $probe     Probe that failed.
     * @param string      $message   Safe to render: the detail stays in the log.
     * @param int         $elapsedMs Wall-clock milliseconds spent before throwing.
     */
    public static function failure(SystemProbe $probe, string $message, int $elapsedMs): self
    {
        return new self(
            key: $probe->key(),
            label: $probe->label(),
            description: $probe->description(),
            icon: $probe->icon(),
            items: [ProbeItem::flagged('Probe error', $message, ProbeStatus::Fail)],
            status: ProbeStatus::Fail,
            elapsedMs: $elapsedMs,
            error: $message,
        );
    }

    /**
     * Build a placeholder for a heavy section that has not been warmed yet.
     *
     * @param SystemProbe $probe Probe with no cached result.
     * @param string      $hint  Why the section is empty.
     */
    public static function pending(SystemProbe $probe, string $hint): self
    {
        return new self(
            key: $probe->key(),
            label: $probe->label(),
            description: $probe->description(),
            icon: $probe->icon(),
            items: [ProbeItem::flagged('Not measured yet', null, ProbeStatus::Pending, $hint)],
            status: ProbeStatus::Pending,
        );
    }

    /**
     * Whether this section still has to be measured.
     */
    public function isPending(): bool
    {
        return $this->status === ProbeStatus::Pending;
    }

    /**
     * Whether the probe threw instead of measuring.
     */
    public function isFailed(): bool
    {
        return $this->error !== null;
    }

    /**
     * The lines that may leave the application in an export.
     *
     * @return list<ProbeItem>
     */
    public function exportableItems(): array
    {
        return array_values(array_filter(
            $this->items,
            fn (ProbeItem $item): bool => ! $item->sensitive,
        ));
    }

    /**
     * A copy carrying only the lines a reader may see, with the verdict recomputed so a
     * line this reader cannot see does not decide what the card reports.
     *
     * @param callable(ProbeItem): bool $visible Predicate deciding whether a line stays.
     */
    public function filter(callable $visible): self
    {
        $items = array_values(array_filter($this->items, $visible));

        return new self(
            key: $this->key,
            label: $this->label,
            description: $this->description,
            icon: $this->icon,
            items: $items,
            status: self::worstStatus($items),
            elapsedMs: $this->elapsedMs,
            warmAt: $this->warmAt,
            error: $this->error,
        );
    }

    /**
     * The worst severity carried by any line.
     *
     * @param list<ProbeItem> $items
     */
    private static function worstStatus(array $items): ProbeStatus
    {
        return array_reduce(
            $items,
            fn (ProbeStatus $carry, ProbeItem $item): ProbeStatus => $item->status === null
                ? $carry
                : $carry->worseThan($item->status),
            ProbeStatus::Ok,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'description' => $this->description,
            'icon' => $this->icon,
            'status' => $this->status->value,
            'items' => array_map(fn (ProbeItem $item): array => $item->toArray(), $this->items),
            'elapsed_ms' => $this->elapsedMs,
            'warm_at' => $this->warmAt,
            'error' => $this->error,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            key: (string) $data['key'],
            label: (string) $data['label'],
            description: (string) ($data['description'] ?? ''),
            icon: (string) ($data['icon'] ?? 'cog-6-tooth'),
            items: array_map(
                static fn (array $item): ProbeItem => ProbeItem::fromArray($item),
                $data['items'] ?? [],
            ),
            status: ProbeStatus::from((string) $data['status']),
            elapsedMs: isset($data['elapsed_ms']) ? (int) $data['elapsed_ms'] : null,
            warmAt: isset($data['warm_at']) ? (int) $data['warm_at'] : null,
            error: $data['error'] ?? null,
        );
    }
}
