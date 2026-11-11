<?php

declare(strict_types=1);

namespace App\Services\Ai\DataTransferObjects;

/**
 * The server-built brief for a batch of post-title suggestions.
 *
 * Every field is resolved on the server: taxonomy names come from the database
 * rather than from client-supplied labels, the body is flattened from the block
 * JSON and capped, and free text is stripped of markup and redacted before it
 * can be put in a prompt. Nothing here is persisted.
 */
final class TitleSuggestionContext
{
    /**
     * @param string       $workingTitle The title currently in the editor.
     * @param string       $excerpt      The draft excerpt, redacted.
     * @param string       $body         Flattened prose from the content blocks, capped.
     * @param list<string> $categories   Selected category names.
     * @param list<string> $tags         Selected tag names.
     * @param string       $siteName     Site name used for tone.
     * @param string       $locale       Language the headlines should be written in.
     * @param list<string> $recentTitles Headlines the model should not repeat.
     */
    public function __construct(
        public readonly string $workingTitle,
        public readonly string $excerpt,
        public readonly string $body,
        public readonly array $categories,
        public readonly array $tags,
        public readonly string $siteName,
        public readonly string $locale,
        public readonly array $recentTitles,
    ) {}

    /**
     * Render the brief as a bounded, plain-text user prompt.
     */
    public function prompt(): string
    {
        $lines = [
            'Write '.max(1, (int) config('ai.titles.count', 5)).' distinct blog headline options.',
            'Site: '.$this->siteName,
            'Language: '.$this->locale,
            'Working title: '.($this->workingTitle !== '' ? $this->workingTitle : '(none yet)'),
            'Excerpt: '.($this->excerpt !== '' ? $this->excerpt : '(none yet)'),
            'Categories: '.($this->categories !== [] ? implode(', ', $this->categories) : '(none)'),
            'Tags: '.($this->tags !== [] ? implode(', ', $this->tags) : '(none)'),
            'Draft text: '.($this->body !== '' ? $this->body : '(none yet)'),
        ];

        if ($this->recentTitles !== []) {
            $lines[] = 'Do not reuse these existing headlines: '.implode(' | ', $this->recentTitles);
        }

        return implode("\n", $lines);
    }
}
