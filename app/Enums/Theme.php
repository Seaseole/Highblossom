<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum representing available UI themes.
 */
enum Theme: string
{
    case Light = 'light';
    case Dark = 'dark';
    case Auto = 'auto';
}
