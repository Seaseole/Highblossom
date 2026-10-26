<?php

declare(strict_types=1);

namespace App\Support;

use App\Console\Commands\HeroVariantsCommand;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * The derived image files the cinematic hero paints with.
 *
 * A slide stores one photograph — the file the media library already holds — and this class
 * owns everything generated from it: the WebP widths the `srcset` offers, and the resized
 * foreground cutout that has to keep both its alpha channel and its exact aspect ratio.
 * Nothing here crops. `cover()` would slice a cutout's subject out of frame the moment the
 * source is a different shape from the target box, and the whole illusion of the layered hero
 * depends on the cutout landing on the same pixels it was erased from.
 *
 * Derived paths are deterministic (`hero/{name}-{source-hash}-{width}.webp`), so a render can
 * build a `srcset` straight from the stored slide without asking the disk whether each file
 * exists — which matters because the public disk is object storage in production, where a
 * per-candidate head request would be four round trips per slide per page view. The trade is
 * that variants must be generated where they are served: {@see HeroVariantsCommand}
 * rebuilds any that a deploy or a bucket sync left behind.
 *
 * A source that is already narrower than a requested width is not upscaled: that slot points
 * back at the source file itself, so a 1200px upload serves 1200px at the top of the ladder
 * rather than a soft 1920px blow-up.
 */
final class HeroAssets
{
    /**
     * Folder on the public disk holding every derived hero file, and nothing else.
     */
    public const FOLDER = 'hero';

    /**
     * The widths the responsive ladder offers, small to large.
     *
     * @var list<int>
     */
    public const WIDTHS = [800, 1280, 1920];

    /**
     * How far a cutout may drift from its background's aspect ratio before it is rejected,
     * as a fraction of the ratio. Two percent is a rounding difference in an editor; anything
     * past that is a different crop, and the subject would sit off the photograph behind it.
     */
    public const ASPECT_TOLERANCE = 0.02;

    /**
     * Encode quality for derived WebP files, matching every other pipeline in the app.
     */
    private const QUALITY = 82;

    /**
     * Disk the hero paints from.
     */
    private const DISK = 'public';

    /**
     * The deterministic path for one width of one source.
     */
    public static function variantPath(string $source, int $width): string
    {
        $name = pathinfo($source, PATHINFO_FILENAME) ?: 'hero';

        return self::FOLDER.'/'.preg_replace('/[^A-Za-z0-9._-]/', '', $name)
            .'-'.substr(md5($source), 0, 8)
            .'-'.$width.'.webp';
    }

    /**
     * Every width of a source, keyed by that width.
     *
     * Slots at or below the source's own width resolve to a derived file; anything wider than
     * the source resolves to the source, because there is nothing to gain from upscaling it.
     *
     * @return array<int, string>
     */
    public static function variants(string $source, ?int $sourceWidth = null): array
    {
        $sourceWidth ??= self::width($source);

        if ($sourceWidth === null) {
            return [];
        }

        return collect(self::WIDTHS)
            ->mapWithKeys(fn (int $width): array => [
                $width => $width < $sourceWidth ? self::variantPath($source, $width) : $source,
            ])
            ->all();
    }

    /**
     * Create any missing width of a source, returning the paths written or already present.
     *
     * Idempotent and cheap on a warm disk: every slot is a single `exists()` before an encode,
     * so saving the settings page twice does not re-compress the photographs. A source that
     * cannot be read yields nothing rather than an exception — a hero asset is never a reason
     * to fail a settings save that also carried working hours or a logo.
     *
     * Widths at or beyond the source's own width are not encoded at all: {@see self::variants()}
     * points those slots back at the source, so generating them would only be a second copy of
     * the same photograph, softer for having been re-encoded.
     *
     * @return list<string>
     */
    public static function sync(?string $source): array
    {
        if (! self::isUsable($source)) {
            return [];
        }

        $natural = self::width($source);

        if ($natural === null) {
            return [];
        }

        $written = [];

        foreach (self::WIDTHS as $target) {
            if ($target >= $natural) {
                continue;
            }

            $path = self::variantPath($source, $target);

            if (Storage::disk(self::DISK)->exists($path)) {
                $written[] = $path;

                continue;
            }

            if (self::renderVariant($source, $target, $path)) {
                $written[] = $path;
            }
        }

        return $written;
    }

    /**
     * Delete derived files no longer referenced by a source.
     *
     * Only {@see self::FOLDER} is swept, and only against the sources still in the settings:
     * the media library originals are never touched here, because other screens share them.
     *
     * @param iterable<string> $sources
     */
    public static function prune(iterable $sources): void
    {
        $keep = collect($sources)
            ->filter(fn ($source): bool => is_string($source) && $source !== '')
            ->flatMap(fn (string $source): array => array_map(
                fn (int $width): string => basename(self::variantPath($source, $width)),
                self::WIDTHS
            ))
            ->all();

        $disk = Storage::disk(self::DISK);

        // A folder that was never written to has nothing to sweep, and creating one just to
        // list it would leave an empty directory in the bucket on a first-ever save.
        if (! $disk->directoryExists(self::FOLDER)) {
            return;
        }

        foreach ($disk->files(self::FOLDER) as $file) {
            if (! in_array(basename($file), $keep, true)) {
                $disk->delete($file);
            }
        }
    }

    /**
     * The stored image's aspect ratio (width divided by height), or null when it cannot be read.
     */
    public static function aspectRatio(?string $path): ?float
    {
        $size = self::size($path);

        return $size === null ? null : $size[0] / max(1, $size[1]);
    }

    /**
     * Width and height of a stored image, or null when it cannot be read.
     *
     * @return array{int, int}|null
     */
    public static function size(?string $path): ?array
    {
        if (! self::isUsable($path)) {
            return null;
        }

        try {
            $image = Image::fromStorage($path, self::DISK);

            return [$image->width(), $image->height()];
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * A hex colour to paint behind the first slide while its photograph decodes.
     *
     * The placeholder has to be in the HTML, so it is derived once at save time and stored on
     * the slide rather than computed per render.
     */
    public static function dominantColor(?string $path): ?string
    {
        if (! self::isUsable($path)) {
            return null;
        }

        try {
            return Image::fromStorage($path, self::DISK)->dominantColor();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Width of a stored image, or null when it cannot be read.
     */
    private static function width(?string $path): ?int
    {
        return self::size($path)[0] ?? null;
    }

    /**
     * Encode one width of a source to one deterministic path.
     */
    private static function renderVariant(string $source, int $width, string $path): bool
    {
        try {
            return Image::fromStorage($source, self::DISK)
                ->orient()
                ->scale(width: $width)
                ->toWebp()
                ->quality(self::QUALITY)
                ->storeAs(self::FOLDER, basename($path), self::DISK) !== false;
        } catch (\Throwable $e) {
            Log::warning('Hero variant skipped for '.$source.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Whether a stored value is worth opening: a path, on our disk, that is actually there.
     */
    private static function isUsable(?string $path): bool
    {
        if (! is_string($path) || $path === '' || str_starts_with($path, 'http')) {
            return false;
        }

        return Storage::disk(self::DISK)->exists($path);
    }
}
