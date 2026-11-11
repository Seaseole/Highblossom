<?php

declare(strict_types=1);

namespace App\Actions\Content;

use App\Concerns\PreparesAiPromptText;
use App\Services\Ai\DataTransferObjects\CopySuggestionContext;
use Illuminate\Support\Str;

/**
 * Turn the About Us form's current field values into a bounded, redacted brief
 * for body, mission and vision suggestions.
 *
 * The subject is the page title and subtitle; the draft prose is gathered from
 * the *other* two fields so the model can stay consistent with what the page
 * already says without being asked to repeat the field being suggested.
 */
final class BuildAboutSuggestionContextAction
{
    use PreparesAiPromptText;

    /**
     * The copy kind and placement line per suggestible field.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const FIELD_KINDS = [
        'body' => ['about_body', 'About Us page body'],
        'mission' => ['about_statement', 'mission statement'],
        'vision' => ['about_statement', 'vision statement'],
    ];

    /**
     * Build the context from validated request data.
     *
     * @param array<string, mixed> $data Validated about us suggestion payload.
     *
     * @return CopySuggestionContext The bounded brief.
     */
    public function __invoke(array $data): CopySuggestionContext
    {
        $field = (string) $data['field'];
        [$kind, $placement] = self::FIELD_KINDS[$field];

        $subject = $this->clean((string) ($data['title'] ?? ''));
        $subtitle = $this->clean((string) ($data['subtitle'] ?? ''));

        if ($subtitle !== '') {
            $subject = trim($subject.' — '.$subtitle);
        }

        $prose = [];

        foreach (['body', 'mission', 'vision'] as $name) {
            if ($name === $field) {
                continue;
            }

            $text = $this->clean((string) ($data[$name] ?? ''));

            if ($text !== '') {
                $prose[] = $text;
            }
        }

        return new CopySuggestionContext(
            kind: $kind,
            subject: $subject,
            body: Str::substr(implode(' ', $prose), 0, max(0, (int) config('ai.context_max_chars', 2000))),
            current: $this->clean((string) ($data[$field] ?? '')),
            keywords: '',
            siteName: $this->clean((string) config('seo.site_name', config('app.name'))),
            locale: app()->getLocale(),
            placement: $placement,
            tone: (string) ($data['tone'] ?? ''),
        );
    }
}
