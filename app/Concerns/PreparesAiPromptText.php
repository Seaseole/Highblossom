<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\Post;
use Illuminate\Support\Str;

/**
 * Shared prompt hygiene for AI suggestion context builders.
 *
 * Every brief that leaves the server passes through the same two steps: block
 * JSON is flattened and capped, and free text is stripped of markup and
 * control characters with contact details redacted. Keeping both in one trait
 * means a new suggestion surface cannot quietly drift from the redaction rules
 * the title endpoint already follows.
 */
trait PreparesAiPromptText
{
    /**
     * Flatten the block JSON into capped prose.
     *
     * @param mixed $content Raw JSON string from the editor.
     *
     * @return string Redacted prose, or an empty string for unusable input.
     */
    private function body(mixed $content): string
    {
        if (! is_string($content) || $content === '') {
            return '';
        }

        $blocks = json_decode($content, true);

        if (! is_array($blocks)) {
            return '';
        }

        $prose = (new Post(['content' => $blocks]))->plainText();

        return $this->clean(Str::substr($prose, 0, max(0, (int) config('ai.context_max_chars', 2000))));
    }

    /**
     * Strip markup and control characters, collapse whitespace and redact
     * contact details before any of this text is put into a prompt.
     *
     * @param string $value Raw editor text.
     *
     * @return string Safe, single-line text.
     */
    private function clean(string $value): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/iu', '[email redacted]', $value) ?? $value;
        $value = preg_replace('/(?:\+?\d[\d\s().-]{7,}\d)/u', '[phone redacted]', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }
}
