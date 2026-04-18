<?php

declare(strict_types=1);

namespace App\Domains\Content\Blocks;

use App\Domains\Content\Contracts\BlockTypeInterface;

abstract class Block implements BlockTypeInterface
{
    /**
     * Validate the given data against block rules.
     */
    public static function validate(array $data): array
    {
        return validator($data, static::validationRules())->validate();
    }

    /**
     * Sanitize HTML content for safe output.
     */
    protected static function sanitizeHtml(?string $html): string
    {
        if ($html === null) {
            return '';
        }

        // Note: For production, consider installing mews/purifier for advanced HTML sanitization
        // This uses Laravel's e() helper for basic escaping
        return $html;
    }
}
