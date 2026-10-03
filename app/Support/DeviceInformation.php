<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\DeviceType;
use Jenssegers\Agent\Agent;

/**
 * Immutable device summary parsed from a session user agent string.
 *
 * Wraps the third-party parser so the detection library is swappable from one
 * file, and so parsing happens once at write time rather than on every render.
 */
final class DeviceInformation
{
    /**
     * Parser platform labels that are shown to users under a cleaner name.
     */
    private const PLATFORM_ALIASES = [
        'OS X' => 'macOS',
        'AndroidOS' => 'Android',
        'Chrome OS' => 'ChromeOS',
    ];

    private function __construct(
        public readonly DeviceType $deviceType,
        public readonly ?string $platform,
        public readonly ?string $browser,
        public readonly ?string $browserVersion,
    ) {}

    /**
     * Parse the given user agent string, tolerating a missing one.
     */
    public static function fromUserAgent(?string $userAgent): self
    {
        if (blank($userAgent)) {
            return new self(DeviceType::UNKNOWN, null, null, null);
        }

        $agent = new Agent;
        $agent->setUserAgent($userAgent);

        $deviceType = self::resolveDeviceType($agent);

        // Crawlers report a generic marketing browser, so surface the bot name instead.
        $browser = $deviceType === DeviceType::BOT
            ? self::clean($agent->robot()) ?? 'Bot'
            : self::clean($agent->browser());

        $version = $browser === null ? null : self::clean($agent->version($browser));

        return new self(
            $deviceType,
            self::normalisePlatform(self::clean($agent->platform())),
            $browser,
            $version,
        );
    }

    /**
     * Classify the device, checking tablet before mobile because tablets also
     * report themselves as mobile devices.
     */
    private static function resolveDeviceType(Agent $agent): DeviceType
    {
        return match (true) {
            $agent->isRobot() => DeviceType::BOT,
            $agent->isTablet() => DeviceType::TABLET,
            $agent->isMobile() => DeviceType::MOBILE,
            default => DeviceType::DESKTOP,
        };
    }

    /**
     * Map known parser labels onto their user-facing names.
     */
    private static function normalisePlatform(?string $platform): ?string
    {
        return $platform === null ? null : (self::PLATFORM_ALIASES[$platform] ?? $platform);
    }

    /**
     * Normalise a parser return value, which may be `false` or an empty string.
     */
    private static function clean(string|bool|null $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    /**
     * Render a short human-readable device description, such as "Chrome on macOS".
     */
    public function summary(): string
    {
        if ($this->deviceType === DeviceType::UNKNOWN) {
            return 'Unknown device';
        }

        return match (true) {
            $this->browser !== null && $this->platform !== null => "{$this->browser} on {$this->platform}",
            $this->browser !== null => $this->browser,
            $this->platform !== null => $this->platform,
            default => $this->deviceType->label(),
        };
    }

    /**
     * Get the parsed columns ready for persistence.
     *
     * @return array{device_type: string, platform: ?string, browser: ?string, browser_version: ?string}
     */
    public function toColumnArray(): array
    {
        return [
            'device_type' => $this->deviceType->value,
            'platform' => $this->platform,
            'browser' => $this->browser,
            'browser_version' => $this->browserVersion,
        ];
    }
}
