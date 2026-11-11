<?php

declare(strict_types=1);

namespace App\Services\Ai\DataTransferObjects;

use Illuminate\Support\Str;

/**
 * The server-built brief for a batch of copy suggestions.
 *
 * Every field is resolved on the server: the subject is a post title, a page
 * label looked up from the route registry, or the site name — never a
 * client-supplied label. Free text is stripped of markup and redacted before it
 * can be put into a prompt. Nothing here is persisted.
 */
final class CopySuggestionContext
{
    /** The copy kinds this brief can describe. */
    public const KINDS = [
        'excerpt',
        'meta_title',
        'meta_description',
        'block_paragraph',
        'block_heading',
        'block_text',
        'block_button',
        'about_body',
        'about_statement',
    ];

    /**
     * @param string $kind      One of self::KINDS.
     * @param string $subject   What the copy is about, redacted.
     * @param string $body      Flattened draft prose for grounding, capped.
     * @param string $current   The field's current value, redacted and uncapped
     *                          so exclusion compares the whole value; only the
     *                          prompt rendering truncates it.
     * @param string $keywords  Comma-separated SEO keywords, redacted.
     * @param string $siteName  Site name used for tone and meta titles.
     * @param string $locale    Language the copy should be written in.
     * @param string $placement Where the copy will appear, e.g. "alert message".
     * @param string $tone      Voice preset key from config('ai.tones'), or '' for none.
     */
    public function __construct(
        public readonly string $kind,
        public readonly string $subject,
        public readonly string $body,
        public readonly string $current,
        public readonly string $keywords,
        public readonly string $siteName,
        public readonly string $locale,
        public readonly string $placement = '',
        public readonly string $tone = '',
    ) {}

    /**
     * Render the brief as a bounded, plain-text user prompt.
     *
     * @return string The prompt lines, with long values truncated for the model.
     */
    public function prompt(): string
    {
        $lines = [
            'Write '.max(1, (int) config('ai.copy.count', 4)).' distinct '.$this->noun().' options.',
            'Site: '.$this->siteName,
            'Language: '.$this->locale,
            'Subject: '.($this->subject !== '' ? $this->subject : '(none yet)'),
        ];

        if ($this->keywords !== '') {
            $lines[] = 'Keywords: '.$this->keywords;
        }

        if ($this->placement !== '') {
            $lines[] = 'Placement: '.$this->placement;
        }

        $lines[] = 'Current text: '.($this->current !== '' ? Str::substr($this->current, 0, 500) : '(none yet)');

        if ($this->usesBody()) {
            $lines[] = 'Draft text: '.($this->body !== '' ? $this->body : '(none yet)');
        }

        return implode("\n", $lines);
    }

    /**
     * Whether the brief carries draft prose for the model to ground itself in.
     *
     * @return bool True when the kind grounds its copy in draft text.
     */
    private function usesBody(): bool
    {
        return $this->kind === 'excerpt'
            || str_starts_with($this->kind, 'block_')
            || str_starts_with($this->kind, 'about_');
    }

    /**
     * The human-readable name of the copy kind, for the prompt.
     *
     * @return string The noun phrase used in the prompt's opening line.
     */
    private function noun(): string
    {
        return match ($this->kind) {
            'excerpt' => 'blog excerpt',
            'meta_title' => 'SEO meta title',
            'meta_description' => 'SEO meta description',
            'block_paragraph' => 'website paragraph',
            'block_heading' => 'heading',
            'block_text' => 'short text',
            'block_button' => 'button label',
            'about_body' => 'About Us page body',
            'about_statement' => 'statement',
            default => 'copy',
        };
    }
}
