<?php

declare(strict_types=1);

namespace App\Support\SystemOverview;

/**
 * Severity of one diagnostic line, or of a whole section.
 *
 * Sections carry no verdict of their own: their status is the worst status of
 * the lines inside them, so a problem can never be hidden by a passing sibling.
 */
enum ProbeStatus: string
{
    case Ok = 'ok';
    case Info = 'info';
    case Warn = 'warn';
    case Fail = 'fail';
    case Pending = 'pending';

    /**
     * Ordering weight; a section takes the highest weight of its lines.
     */
    public function weight(): int
    {
        return match ($this) {
            self::Ok => 0,
            self::Info => 1,
            self::Pending => 2,
            self::Warn => 3,
            self::Fail => 4,
        };
    }

    /**
     * The more severe of two statuses.
     *
     * @param ProbeStatus $other Status to compare against.
     */
    public function worseThan(ProbeStatus $other): ProbeStatus
    {
        return $this->weight() >= $other->weight() ? $this : $other;
    }

    /**
     * Tailwind classes for the severity dot.
     */
    public function dotClasses(): string
    {
        return match ($this) {
            self::Ok => 'bg-emerald-500',
            self::Info => 'bg-gray-400',
            self::Warn => 'bg-amber-500',
            self::Fail => 'bg-red-500',
            self::Pending => 'bg-blue-500/60',
        };
    }
}
