<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

/**
 * One diagnostic line on the system overview page.
 *
 * Values are pre-formatted strings: probes own the presentation decisions so
 * the Blade view stays a loop instead of re-deriving labels and severities.
 *
 * Two independent filters exist on a line. `sensitive` keeps the value out of the
 * export payload while the page still shows it, and `requires` hides the whole line
 * from readers who lack an ability. Both are applied when a section is read, never
 * when it is cached, so one cache entry can serve every viewer.
 */
final class ProbeItem
{
    /**
     * @param string           $label     Human readable metric name.
     * @param string|null      $value     Pre-formatted value, or null when unavailable.
     * @param ProbeStatus|null $status    Severity; null renders a neutral line.
     * @param string|null      $hint      Context shown under the value.
     * @param string|null      $route     Named route to link the value to.
     * @param string|null      $requires  Ability a reader must hold to see this line at all.
     * @param bool             $sensitive Whether the value must be withheld from the export payload.
     */
    private function __construct(
        public readonly string $label,
        public readonly ?string $value,
        public readonly ?ProbeStatus $status = null,
        public readonly ?string $hint = null,
        public readonly ?string $route = null,
        public readonly ?string $requires = null,
        public readonly bool $sensitive = false,
    ) {}

    /**
     * A neutral informational line.
     *
     * @param string      $label Human readable metric name.
     * @param string|null $value Pre-formatted value.
     * @param string|null $hint  Context shown under the value.
     */
    public static function text(string $label, ?string $value, ?string $hint = null): self
    {
        return new self($label, $value, null, $hint);
    }

    /**
     * A line that carries a verdict.
     *
     * @param string      $label  Human readable metric name.
     * @param string|null $value  Pre-formatted value.
     * @param ProbeStatus $status Severity of the value.
     * @param string|null $hint   Remediation or context shown under the value.
     */
    public static function flagged(string $label, ?string $value, ProbeStatus $status, ?string $hint = null): self
    {
        return new self($label, $value, $status, $hint);
    }

    /**
     * Copy with a named route attached to the value.
     *
     * @param string $route Route name to link to.
     */
    public function withRoute(string $route): self
    {
        return new self($this->label, $this->value, $this->status, $this->hint, $route, $this->requires, $this->sensitive);
    }

    /**
     * Copy that only readers holding this ability may see.
     *
     * @param string $ability Gate ability the reader must hold.
     */
    public function requires(string $ability): self
    {
        return new self($this->label, $this->value, $this->status, $this->hint, $this->route, $ability, $this->sensitive);
    }

    /**
     * Copy marked as never exportable.
     */
    public function sensitive(): self
    {
        return new self($this->label, $this->value, $this->status, $this->hint, $this->route, $this->requires, true);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'status' => $this->status?->value,
            'hint' => $this->hint,
            'route' => $this->route,
            'requires' => $this->requires,
            'sensitive' => $this->sensitive,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $status = isset($data['status']) && is_string($data['status'])
            ? ProbeStatus::tryFrom($data['status'])
            : null;

        return new self(
            (string) $data['label'],
            isset($data['value']) ? (string) $data['value'] : null,
            $status,
            isset($data['hint']) ? (string) $data['hint'] : null,
            isset($data['route']) ? (string) $data['route'] : null,
            isset($data['requires']) ? (string) $data['requires'] : null,
            (bool) ($data['sensitive'] ?? false),
        );
    }
}
