<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Generates the full browser icon set from one uploaded image and writes it
 * directly into the public folder root (favicon.ico, apple-touch-icon.png,
 * the PNG sizes and the manifest icons), replacing the bundled brand files.
 *
 * Public-root files are rebuilt from git on every deploy, so the set
 * self-heals: a `favicon-version.txt` marker records the active version hash
 * and {@see ensure()} rewrites the files from the stored master (which lives
 * on the persistent public disk) as soon as the marker drifts. Removing the
 * custom favicon restores the pristine copies kept in resources/brand-icons.
 */
final class FaviconSetGenerator
{
    /** @var list<int> Standalone PNG sizes rendered from the source */
    private const PNG_SIZES = [48, 96, 180, 192, 512];

    /** @var list<int> Sizes embedded in the multi-resolution .ico container */
    private const ICO_SIZES = [16, 32, 48];

    /** @var array<string, string> Public-root filename => render recipe */
    private const ROOT_FILES = [
        'favicon.ico' => 'ico',
        'favicon-48x48.png' => '48',
        'favicon-96x96.png' => '96',
        'apple-touch-icon.png' => '180',
        'icon-192.png' => '192',
        'icon-512.png' => '512',
        'icon-512-maskable.png' => 'maskable',
    ];

    /** Marker file recording the version hash currently written to the root. */
    private const MARKER = 'favicon-version.txt';

    /**
     * Render the icon set from raw source bytes and write it to the root.
     *
     * @param string $bytes Encoded source image (png/webp/jpeg/gif)
     *
     * @return string|null Version hash on success, null when generation failed
     */
    public function generate(string $bytes): ?string
    {
        $version = substr(sha1($bytes), 0, 12);

        return $this->write($bytes, $version) ? $version : null;
    }

    /**
     * Reconcile the public root with the stored favicon_set.
     *
     * Fast path is a single marker read. When a custom set is active but the
     * root no longer matches (typical right after a deploy rebuilt public/),
     * the files are rewritten from the persistent master. When no set is
     * active but custom files are still on disk, the bundled icons are
     * restored.
     *
     * @param array<string, mixed> $set        favicon_set metadata, e.g. ['v' => 'abc123']
     * @param string|null          $masterPath Public-disk path of the stored master image
     */
    public function ensure(array $set, ?string $masterPath): void
    {
        $version = $set['v'] ?? null;

        if (filled($version)) {
            if ($this->currentVersion() !== $version && filled($masterPath)) {
                try {
                    $bytes = Storage::disk('public')->get($masterPath);

                    if ($bytes !== false) {
                        $this->write($bytes, (string) $version);
                    }
                } catch (Throwable $e) {
                    Log::error('Favicon set self-heal failed: '.$e->getMessage());
                }
            }

            return;
        }

        if ($this->currentVersion() !== null) {
            $this->restore();
        }
    }

    /**
     * Restore the bundled brand icons to the public root and clear the marker.
     */
    public function restore(): void
    {
        $root = $this->rootPath();
        $source = resource_path('brand-icons');

        foreach (array_keys(self::ROOT_FILES) as $file) {
            $pristine = $source.'/'.$file;

            if (is_file($pristine)) {
                @copy($pristine, $root.'/'.$file);
            }
        }

        @unlink($root.'/'.$this->markerName());
    }

    /**
     * Current version hash written to the public root, or null for bundled icons.
     */
    public function currentVersion(): ?string
    {
        $marker = @file_get_contents($this->rootPath().'/'.$this->markerName());

        return $marker === false ? null : trim($marker);
    }

    /**
     * Render every root file from the source bytes and stamp the marker.
     *
     * @param string $bytes   Encoded source image
     * @param string $version Version hash to record in the marker
     */
    private function write(string $bytes, string $version): bool
    {
        $root = $this->rootPath();

        try {
            /** @var array<int, string> $pngs */
            $pngs = [];

            foreach ([...self::ICO_SIZES, ...self::PNG_SIZES] as $size) {
                if (! isset($pngs[$size])) {
                    $pngs[$size] = Image::fromBytes($bytes)->cover($size, $size)->toPng()->toBytes();
                }
            }

            foreach (self::ROOT_FILES as $file => $recipe) {
                $contents = match (true) {
                    $recipe === 'ico' => $this->buildIco(array_intersect_key($pngs, array_flip(self::ICO_SIZES))),
                    $recipe === 'maskable' => Image::fromBytes($bytes)->contain(512, 512, '#ffffff')->toPng()->toBytes(),
                    default => $pngs[(int) $recipe],
                };

                if (@file_put_contents($root.'/'.$file, $contents) === false) {
                    throw new RuntimeException("Unable to write {$file} to the public root.");
                }
            }

            @file_put_contents($root.'/'.$this->markerName(), $version);

            return true;
        } catch (Throwable $e) {
            Log::error('Favicon set generation failed: '.$e->getMessage());
            $this->restore();

            return false;
        }
    }

    /**
     * Directory the icon set is written to (public root unless overridden).
     */
    private function rootPath(): string
    {
        return (string) config('icons.output_path', public_path());
    }

    /**
     * Marker filename, overridable for tests.
     */
    private function markerName(): string
    {
        return (string) config('icons.marker', self::MARKER);
    }

    /**
     * Assemble a PNG-compressed, multi-size ICO container.
     *
     * @param array<int, string> $pngs PNG bytes keyed by pixel size
     */
    private function buildIco(array $pngs): string
    {
        $entries = '';
        $payload = '';
        $offset = 6 + (16 * count($pngs));

        foreach ($pngs as $size => $png) {
            $entries .= pack('C4v2V2', $size, $size, 0, 0, 1, 32, strlen($png), $offset);
            $payload .= $png;
            $offset += strlen($png);
        }

        return pack('v3', 0, 1, count($pngs)).$entries.$payload;
    }
}
