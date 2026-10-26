<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\HeroStyle;
use Highblossom\ContentBlocks\Support\MediaUrl;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Reads the cinematic hero out of the company settings, and writes it back.
 *
 * This is the single place that knows the shape of a slide. The admin repeater, the public
 * component and the save-time asset pipeline all go through it, so the three can never drift
 * onto separate copies of the same contract: a key added here appears in the stored row, and a
 * key that is not whitelisted in {@see self::persist()} never reaches the disk at all.
 *
 * Stored rows carry two kinds of value. The typed ones (`word`, `background`, `alt` …) are the
 * admin's; the derived block (`width`, `height`, `dominant`, `variants`) is recomputed from the
 * photograph on every save, so a crafted `_derived` key in the request is discarded rather than
 * echoed into a `srcset`. Paying for that derivation once, at save, is what lets a page view
 * build its markup without touching the filesystem — on object storage that would be a head
 * request per slide per load.
 */
final class HeroSlides
{
    /**
     * How many slides the carousel will run.
     */
    public const MAX_SLIDES = 6;

    /**
     * How many stat counters the hero will paint.
     */
    public const MAX_STATS = 3;

    /**
     * The keys an admin actually types, and the only ones accepted from a request.
     *
     * @var list<string>
     */
    private const TYPED_KEYS = [
        'word', 'background', 'cutout', 'alt', 'headline', 'tagline', 'button_label', 'button_url',
    ];

    /**
     * How long a slide holds before the carousel moves on, and how long the move takes.
     *
     * Both live here because the progress bar is drawn in CSS from the interval while Alpine
     * waits for that bar to finish: one number, two consumers, and no chance for the bar to
     * disagree with the timer it is supposed to be showing.
     */
    public const AUTOPLAY_MS = 7000;

    /**
     * The crossfade between slides.
     */
    public const CROSSFADE_MS = 650;

    /**
     * The one `sizes` the whole ladder is chosen against: the card is full-bleed with a
     * page gutter until it reaches the 1400px shell width.
     */
    public const SIZES = '(min-width: 1440px) 1400px, (min-width: 768px) 94vw, 100vw';

    /**
     * Which hero the homepage opens with.
     */
    public static function style(Collection|array $settings): HeroStyle
    {
        return HeroStyle::normalize(self::value($settings, 'home_hero_style'));
    }

    /**
     * Whether the slides advance on their own.
     */
    public static function autoplay(Collection|array $settings): bool
    {
        return filter_var(self::value($settings, 'home_hero_autoplay', true), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * The slides as the component wants them: paths resolved to URLs, empty rows dropped.
     *
     * A row with no readable photograph is discarded rather than rendered, because a slide
     * without a picture is the one thing this layout cannot fall back to gracefully — the
     * scrim, the cutout alignment and the dominant-colour placeholder all hang off that file.
     *
     * @return list<array<string, mixed>>
     */
    public static function visible(Collection|array $settings): array
    {
        $rows = collect((array) self::value($settings, 'home_hero_slides', []))
            ->filter(fn ($row): bool => is_array($row) && filled($row['background'] ?? null))
            ->take(self::MAX_SLIDES);

        return $rows
            ->map(fn (array $row): array => self::present($row))
            ->filter(fn (?array $slide): bool => $slide !== null)
            ->values()
            ->all();
    }

    /**
     * The stat counters, with the number to count up to split out when the value is one.
     *
     * Nothing is invented here: an empty setting renders no counters at all, and a value the
     * admin typed as text ("Lifetime") is shown as text instead of animated to zero.
     *
     * @return list<array<string, mixed>>
     */
    public static function stats(Collection|array $settings): array
    {
        return collect((array) self::value($settings, 'home_hero_stats', []))
            ->filter(fn ($stat): bool => is_array($stat) && filled($stat['label'] ?? null) && filled($stat['value'] ?? null))
            ->take(self::MAX_STATS)
            ->map(fn (array $stat): array => self::presentStat($stat))
            ->values()
            ->all();
    }

    /**
     * The primary call to action, per slide first and the site defaults underneath it.
     *
     * @return array{label: string, url: string}
     */
    public static function button(Collection|array $settings, array $slide = []): array
    {
        $label = trim((string) ($slide['button_label'] ?? ''))
            ?: trim((string) self::value($settings, 'home_hero_button_label', ''))
            ?: __('site.home.hero_get_quote');

        $url = trim((string) ($slide['button_url'] ?? ''))
            ?: trim((string) self::value($settings, 'home_hero_button_url', ''))
            ?: route('quote');

        return ['label' => $label, 'url' => $url];
    }

    /**
     * The whole headline for the hero, defaulting to the copy the classic hero already uses.
     *
     * A cinematic slide can go without a headline of its own and still be a valid slide.
     */
    public static function fallbackHeadline(): string
    {
        return (string) __('site.home.hero_headline_fallback');
    }

    /**
     * The sub-headline a slide falls back to when its own tagline is blank.
     */
    public static function fallbackTagline(): string
    {
        return (string) __('site.home.hero_subheadline');
    }

    /**
     * Normalise the rows from a settings save into the shape that gets persisted.
     *
     * Every derived value is recomputed here from the stored photograph, so this is the only
     * path that can put a `srcset` candidate on the disk: the request cannot.
     *
     * @param iterable<mixed> $rows
     *
     * @return list<array<string, mixed>>
     */
    public static function persist(iterable $rows): array
    {
        return collect($rows)
            ->filter(fn ($row): bool => is_array($row) && collect(Arr::only($row, self::TYPED_KEYS))->filter(fn ($value) => filled($value))->isNotEmpty())
            ->map(fn (array $row): array => self::derive(Arr::only($row, self::TYPED_KEYS)))
            ->filter(fn (array $row): bool => filled($row['background']))
            ->take(self::MAX_SLIDES)
            ->values()
            ->all();
    }

    /**
     * Trim, default and derive one stored slide.
     *
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    public static function derive(array $row): array
    {
        $slide = array_map(
            static fn ($value): string => trim((string) $value),
            Arr::only($row, self::TYPED_KEYS) + array_fill_keys(self::TYPED_KEYS, '')
        );

        // A cutout that is no longer within tolerance of its background has been replaced by a
        // different photograph, and would put the subject off-centre against the layer behind
        // it. Dropping the layer keeps the slide; the admin is told by the form instead.
        if ($slide['cutout'] !== '' && ! self::aspectsMatch($slide['background'], $slide['cutout'])) {
            $slide['cutout'] = '';
        }

        // Generation happens here, at the one moment the deck is known to have changed: the
        // renderer only ever names files this step produced, so a page view costs no disk calls.
        HeroAssets::sync($slide['background']);

        if ($slide['cutout'] !== '') {
            HeroAssets::sync($slide['cutout']);
        }

        $size = HeroAssets::size($slide['background']) ?? [1920, 1080];

        return $slide + [
            'width' => $size[0],
            'height' => $size[1],
            'dominant' => HeroAssets::dominantColor($slide['background']),
            'variants' => HeroAssets::variants($slide['background'], $size[0]),
            'cutout_variants' => $slide['cutout'] !== '' ? HeroAssets::variants($slide['cutout']) : [],
        ];
    }

    /**
     * Whether a stored slide is renderable, before any URL is built.
     */
    public static function usable(mixed $row): bool
    {
        return is_array($row) && filled($row['background'] ?? null);
    }

    /**
     * Turn one stored row into the array the component reads.
     *
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>|null
     */
    private static function present(array $row): ?array
    {
        $background = trim((string) ($row['background'] ?? ''));

        if ($background === '') {
            return null;
        }

        $slide = array_map(
            static fn ($value): string => trim((string) $value),
            Arr::only($row, self::TYPED_KEYS) + array_fill_keys(self::TYPED_KEYS, '')
        );

        $cutout = trim((string) ($row['cutout'] ?? ''));
        $thumb = (string) Arr::get($row, 'variants.'.HeroAssets::WIDTHS[0], $background);

        return $slide + [
            'background_src' => MediaUrl::asset($background),
            'background_srcset' => self::srcset($row, $background),
            'thumb_src' => MediaUrl::asset($thumb),
            'cutout_src' => $cutout !== '' ? MediaUrl::asset($cutout) : null,
            'cutout_srcset' => $cutout !== '' ? self::srcset($row, $cutout, 'cutout_variants') : null,
            'width' => (int) ($row['width'] ?? 1920),
            'height' => (int) ($row['height'] ?? 1080),
            'dominant' => self::color($row['dominant'] ?? null),
        ];
    }

    /**
     * A `srcset` for one stored image: the derived widths when they were generated, and the
     * source itself underneath, so a row saved before the ladder existed still paints.
     *
     * @param array<string, mixed> $row
     * @param array-key            $key
     */
    private static function srcset(array $row, string $source, string $key = 'variants'): string
    {
        $candidates = [];

        foreach ((array) ($row[$key] ?? []) as $width => $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }

            $candidates[(int) $width] = MediaUrl::asset($path);
        }

        if ($candidates === []) {
            $candidates[(int) ($row['width'] ?? 1920)] = MediaUrl::asset($source);
        }

        ksort($candidates);

        return implode(', ', array_map(
            static fn (string $url, int $width): string => $url.' '.$width.'w',
            $candidates,
            array_keys($candidates)
        ));
    }

    /**
     * The stored placeholder colour, or null when it is not a colour we will trust in a style.
     *
     * It reaches an inline `background`, so it is re-parsed rather than echoed.
     */
    private static function color(mixed $value): ?string
    {
        return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1 ? $value : null;
    }

    /**
     * @param array<string, mixed> $stat
     *
     * @return array<string, mixed>
     */
    private static function presentStat(array $stat): array
    {
        $value = trim((string) $stat['value']);
        $label = trim((string) $stat['label']);
        $suffix = trim((string) ($stat['suffix'] ?? ''));
        $number = self::number($value);

        return [
            'value' => $value,
            'label' => $label,
            'suffix' => $suffix,
            'count_to' => $number['to'],
            'decimals' => $number['decimals'],
            'grouped' => $number['grouped'],
        ];
    }

    /**
     * Split a counter's target out of the text an admin typed.
     *
     * `2,500+` counts to 2500 with a comma every three digits and the `+` kept as the suffix,
     * `4.8` counts in tenths, and `Lifetime` counts to nothing — it is simply displayed.
     *
     * @return array{to: ?float, decimals: int, grouped: bool}
     */
    private static function number(string $value): array
    {
        $trimmed = rtrim($value, '+');

        if (! is_numeric(str_replace(',', '', $trimmed))) {
            return ['to' => null, 'decimals' => 0, 'grouped' => false];
        }

        $plain = str_replace(',', '', $trimmed);

        return [
            'to' => (float) $plain,
            'decimals' => str_contains($plain, '.') ? strlen(explode('.', $plain)[1]) : 0,
            'grouped' => str_contains($value, ','),
        ];
    }

    /**
     * Whether a cutout is close enough in shape to the photograph it has to sit on.
     */
    private static function aspectsMatch(string $background, string $cutout): bool
    {
        $bg = HeroAssets::aspectRatio($background);
        $fg = HeroAssets::aspectRatio($cutout);

        if ($bg === null || $fg === null || $bg <= 0) {
            // Unreadable geometry is not a mismatch: the upload rules already rejected files
            // we cannot open, so this is a disk hiccup and the layer stays.
            return true;
        }

        return abs($bg - $fg) / $bg <= HeroAssets::ASPECT_TOLERANCE;
    }

    /**
     * Read one key out of either settings bag shape.
     */
    private static function value(Collection|array $settings, string $key, mixed $default = null): mixed
    {
        return $settings instanceof Collection
            ? $settings->get($key, $default)
            : Arr::get($settings, $key, $default);
    }
}
