<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Number;
use JsonException;
use PDO;

/**
 * PHP runtime limits and the extensions the installed dependencies require.
 *
 * The required-extension list is read from composer.lock rather than a hand kept
 * array, so it follows the real dependency contract instead of drifting from it.
 */
final class PhpRuntimeProbe implements SystemProbe
{
    /**
     * Allowlisted runtime settings safe to display.
     *
     * @var array<string, string>
     */
    private const INI_SETTINGS = [
        'Memory limit' => 'memory_limit',
        'Max execution time' => 'max_execution_time',
        'Post max size' => 'post_max_size',
        'Upload max filesize' => 'upload_max_filesize',
        'Max file uploads' => 'max_file_uploads',
    ];

    public function key(): string
    {
        return 'runtime';
    }

    public function label(): string
    {
        return 'PHP Runtime';
    }

    public function description(): string
    {
        return 'Interpreter version, limits, and the extensions the installed dependencies require.';
    }

    public function icon(): string
    {
        return 'cog-6-tooth';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Light;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        $required = $this->requiredExtensions();
        $missing = array_values(array_filter(
            $required,
            static fn (string $extension): bool => ! extension_loaded($extension),
        ));

        $items = [
            ProbeItem::text('PHP version', PHP_VERSION, 'SAPI: '.PHP_SAPI),
            ProbeItem::text('Operating system', trim(php_uname('s').' '.php_uname('r').' '.php_uname('m'))),
            ProbeItem::flagged(
                'Required extensions',
                Number::forHumans(count($required)).' required',
                $missing === [] ? ProbeStatus::Ok : ProbeStatus::Fail,
                $missing === [] ? null : 'Missing: '.implode(', ', $missing),
            ),
            ProbeItem::flagged(
                'Image driver',
                extension_loaded('gd') ? 'GD' : (extension_loaded('imagick') ? 'Imagick' : null),
                (extension_loaded('gd') || extension_loaded('imagick')) ? ProbeStatus::Ok : ProbeStatus::Fail,
                'Intervention Image needs GD or Imagick.',
            ),
            ProbeItem::text('PDO drivers', PDO::getAvailableDrivers() === [] ? 'none' : implode(', ', PDO::getAvailableDrivers())),
            ProbeItem::text('Loaded extensions', Number::forHumans(count(get_loaded_extensions()))),
        ];

        foreach (self::INI_SETTINGS as $label => $setting) {
            $items[] = ProbeItem::text($label, $this->iniValue($setting));
        }

        $items[] = ProbeItem::text('Runtime timezone', date_default_timezone_get());

        return $items;
    }

    /**
     * The `ext-*` requirements of every installed production dependency.
     *
     * @return list<string>
     */
    private function requiredExtensions(): array
    {
        $path = base_path('composer.lock');

        if (! File::exists($path)) {
            return [];
        }

        try {
            $lock = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }

        $extensions = [];

        foreach ($lock['packages'] ?? [] as $package) {
            foreach (array_keys($package['require'] ?? []) as $requirement) {
                if (is_string($requirement) && str_starts_with($requirement, 'ext-')) {
                    $extensions[substr($requirement, 4)] = true;
                }
            }
        }

        $names = array_keys($extensions);
        sort($names);

        return $names;
    }

    /**
     * @param string $setting Allowlisted ini key.
     */
    private function iniValue(string $setting): string
    {
        $value = ini_get($setting);

        if ($value === false || $value === '') {
            return 'N/A';
        }

        if ($setting === 'memory_limit' && $value === '-1') {
            return 'Unlimited';
        }

        if ($setting === 'max_execution_time') {
            return $value.'s';
        }

        return $value;
    }
}
