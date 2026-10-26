<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\HeroStyle;
use App\Models\CompanySetting;
use App\Support\HeroAssets;
use App\Support\HeroSlides;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Rebuilds the derived widths the cinematic hero serves, without touching a slide's copy.
 *
 * The variants are generated when the settings are saved, so they exist on whatever disk the
 * admin saved them to. A deploy to a different environment — or a bucket that was synced from
 * code rather than from storage — therefore has settings that point at files it never received,
 * and the homepage would show an empty frame the moment the carousel moved on. This command is
 * the repair: run it where the hero is served.
 *
 * It is safe to run twice. {@see HeroAssets::sync()} only encodes a width that is genuinely
 * missing, and `--prune` only deletes files inside the hero's own folder that no stored slide
 * names any more.
 */
#[Description('Regenerate the responsive image variants used by the cinematic hero')]
#[Signature('hero:variants {--prune : Delete derived widths no stored slide references any more}')]
final class HeroVariantsCommand extends Command
{
    /**
     * Execute the command.
     */
    public function handle(): int
    {
        if (HeroStyle::normalize(CompanySetting::get('home_hero_style')) !== HeroStyle::Cinematic) {
            $this->info('The homepage is not using the cinematic hero, so there are no variants to build.');

            return self::SUCCESS;
        }

        $slides = collect((array) CompanySetting::get('home_hero_slides', []))
            ->filter(fn ($slide): bool => HeroSlides::usable($slide))
            ->values();

        if ($slides->isEmpty()) {
            $this->warn('The cinematic hero is selected but holds no slide with a photograph. Nothing to generate.');

            return self::SUCCESS;
        }

        foreach ($slides as $index => $slide) {
            $layers = ['background' => $slide['background'], 'foreground' => $slide['cutout'] ?? ''];

            foreach ($layers as $label => $source) {
                if (! is_string($source) || $source === '') {
                    continue;
                }

                $this->line(sprintf(
                    'slide %d %s: %d width(s) ready',
                    $index + 1,
                    $label,
                    count(HeroAssets::sync($source))
                ));
            }
        }

        if ($this->option('prune')) {
            $sources = $slides->flatMap(fn (array $slide): array => array_filter([$slide['background'], $slide['cutout'] ?? '']));

            HeroAssets::prune($sources->all());

            $this->info('Removed derived files no slide references.');
        }

        return self::SUCCESS;
    }
}
