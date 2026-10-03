<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ApplicationVersion;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

/**
 * Owns the application's semantic version registry: reading the current
 * release, suggesting bumps, and recording new releases (append-only).
 */
final class ApplicationVersionService
{
    public const CACHE_KEY = 'application-version.current';

    public const BASELINE = '1.2.0';

    /** @var list<string> */
    public const BUMP_TYPES = ['major', 'minor', 'patch'];

    /** @var list<string> */
    public const NOTE_TYPES = ['added', 'changed', 'fixed', 'security'];

    public const SEMVER_PATTERN = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/';

    /**
     * Determine whether the given string is a valid MAJOR.MINOR.PATCH version.
     */
    public static function isValidVersion(string $version): bool
    {
        return (bool) preg_match(self::SEMVER_PATTERN, trim($version));
    }

    /**
     * The newest recorded release. Only the primary key is cached so no model
     * object ever crosses the cache serializer.
     */
    public function current(): ?ApplicationVersion
    {
        $id = Cache::remember(self::CACHE_KEY, now()->addHour(), function (): ?int {
            return ApplicationVersion::query()->newestFirst()->value('id');
        });

        return $id === null ? null : ApplicationVersion::find($id);
    }

    /**
     * The next suggested version for the given bump type (major, minor or patch).
     */
    public function next(string $bump): string
    {
        [$major, $minor, $patch] = $this->currentParts();

        return match ($bump) {
            'major' => ($major + 1).'.0.0',
            'minor' => $major.'.'.($minor + 1).'.0',
            default => $major.'.'.$minor.'.'.($patch + 1),
        };
    }

    /**
     * All three suggested next versions keyed by bump type.
     *
     * @return array<string, string>
     */
    public function nextOptions(): array
    {
        return [
            'patch' => $this->next('patch'),
            'minor' => $this->next('minor'),
            'major' => $this->next('major'),
        ];
    }

    /**
     * Record a new release. Versions must be valid semver and strictly newer
     * than the current release so the registry stays forward-only.
     *
     * @param array{version: string, summary: string, notes?: list<array{type: string, text: string}>|null, released_at: string} $data
     *
     * @throws ValidationException
     */
    public function record(array $data, User $author): ApplicationVersion
    {
        $version = trim($data['version']);

        if (! self::isValidVersion($version)) {
            throw ValidationException::withMessages([
                'version' => 'Versions must follow semantic versioning, e.g. 1.2.0.',
            ]);
        }

        $parts = array_map('intval', explode('.', $version));

        if ($this->compare($parts, $this->currentParts()) !== 1) {
            throw ValidationException::withMessages([
                'version' => 'The version must be newer than the current release ('.$this->versionString($this->currentParts()).').',
            ]);
        }

        $record = ApplicationVersion::create([
            'version' => $version,
            'major' => $parts[0],
            'minor' => $parts[1],
            'patch' => $parts[2],
            'summary' => $data['summary'],
            'notes' => $data['notes'] ?? [],
            'released_at' => $data['released_at'],
            'created_by' => $author->id,
        ]);

        Cache::forget(self::CACHE_KEY);

        return $record;
    }

    /**
     * The numeric parts of the current release, falling back to the baseline.
     *
     * @return array{int, int, int}
     */
    private function currentParts(): array
    {
        $current = $this->current();

        if (! $current) {
            return array_map('intval', explode('.', self::BASELINE));
        }

        return [$current->major, $current->minor, $current->patch];
    }

    /**
     * Compare two version tuples. Returns 1, 0 or -1 like the spaceship operator.
     *
     * @param array{int, int, int} $left
     * @param array{int, int, int} $right
     */
    private function compare(array $left, array $right): int
    {
        return $left <=> $right;
    }

    /**
     * Render a version tuple as a dotted string.
     *
     * @param array{int, int, int} $parts
     */
    private function versionString(array $parts): string
    {
        return implode('.', $parts);
    }
}
