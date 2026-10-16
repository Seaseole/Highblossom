<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

use Illuminate\Support\Carbon;

/**
 * The system overview as a machine-readable report, with the lines a probe marked
 * sensitive left out.
 *
 * Redaction happens here rather than in the cache: a section warmed by someone who
 * may see a value is stored under one key with no user dimension, so the filter has
 * to run every time the payload is built. Light sections are measured, heavy ones are
 * taken from cache, so an export never walks the file system or parses a lock file.
 */
final class SystemOverviewExport
{
    public function __construct(
        private readonly ProbeRunner $runner,
    ) {}

    /**
     * The report, ready to encode.
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $sections = $this->runner->runAll();

        return [
            'generated_at' => Carbon::now()->toIso8601String(),
            'application' => (string) config('app.name'),
            'environment' => (string) config('app.env'),
            'withheld_lines' => $this->withheld($sections),
            'sections' => array_map(
                static fn (ProbeResult $section): array => self::section($section),
                $sections,
            ),
        ];
    }

    /**
     * A timestamped file name for the download.
     */
    public function fileName(): string
    {
        return 'system-overview-'.Carbon::now()->format('Y-m-d-His').'.json';
    }

    /**
     * @param list<ProbeResult> $sections
     */
    private function withheld(array $sections): int
    {
        return array_sum(array_map(
            static fn (ProbeResult $section): int => count($section->items) - count($section->exportableItems()),
            $sections,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private static function section(ProbeResult $section): array
    {
        return [
            'key' => $section->key,
            'label' => $section->label,
            'description' => $section->description,
            'status' => $section->status->value,
            'measured_at' => $section->warmAt === null
                ? null
                : Carbon::createFromTimestamp($section->warmAt)->toIso8601String(),
            'elapsed_ms' => $section->elapsedMs,
            'error' => $section->error,
            'items' => array_map(
                static fn (ProbeItem $item): array => [
                    'label' => $item->label,
                    'value' => $item->value,
                    'status' => $item->status?->value,
                    'hint' => $item->hint,
                ],
                $section->exportableItems(),
            ),
        ];
    }
}
