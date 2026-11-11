<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Services\Ai\AiSuggestionException;
use App\Services\Ai\DataTransferObjects\CopySuggestionContext;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\SuggestionBroker;
use Illuminate\Support\Str;

/**
 * Produce a short list of usable copy lines from a draft.
 *
 * Model output is untrusted input, so the last word always belongs to this
 * class: strip markup, collapse whitespace, cap at the field's limit, remove
 * duplicates and the current value, and never return more than the configured
 * count. Deterministic local candidates are merged into the live answer as
 * well, so a thin completion never leaves the button empty.
 */
final class CopySuggestionService
{
    /**
     * The shape of each answer is fixed here rather than left to the model.
     *
     * @var array<string, string>
     */
    private const SYSTEM_PROMPTS = [
        'excerpt' => <<<'PROMPT'
You write short excerpts for a company blog. Return only a plain list, one excerpt per line, with no numbering, bullets, quotes, prefixes or explanation. Each excerpt must be a single line of at most 300 characters, written in the language named in the brief, factual and inviting, summarising the draft text when one is provided. No HTML, emoji or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'meta_title' => <<<'PROMPT'
You write SEO meta titles for a company website. Return only a plain list, one title per line, with no numbering, bullets, quotes, prefixes or explanation. Each title must be a single line of at most 60 characters, naming the page subject and optionally the site name after a separator. Factual tone, no HTML, emoji, clickbait or keyword stuffing. Do not repeat the current text listed in the brief.
PROMPT,
        'meta_description' => <<<'PROMPT'
You write SEO meta descriptions for a company website. Return only a plain list, one description per line, with no numbering, bullets, quotes, prefixes or explanation. Each description must be a single line between 120 and 160 characters, summarising the page subject in a factual, inviting tone and working in the listed keywords naturally. No HTML, emoji, clickbait or keyword stuffing. Do not repeat the current text listed in the brief.
PROMPT,
        'block_paragraph' => <<<'PROMPT'
You write short paragraphs for a company website. Return only a plain list, one paragraph per line, with no numbering, bullets, quotes, prefixes or explanation. Each paragraph must be a single line of one to three sentences and at most 600 characters, written in the language named in the brief, factual and inviting, suited to the placement named in the brief and grounded in the draft text when one is provided. No HTML, emoji or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'block_heading' => <<<'PROMPT'
You write headings for a company website. Return only a plain list, one heading per line, with no numbering, bullets, quotes, prefixes or explanation. Each heading must be a single line of at most 120 characters with no trailing full stop, naming the subject plainly and suited to the placement named in the brief. Factual tone, no HTML, emoji or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'block_text' => <<<'PROMPT'
You write short supporting text for a company website. Return only a plain list, one text per line, with no numbering, bullets, quotes, prefixes or explanation. Each text must be a single line of one or two sentences and at most 300 characters, written in the language named in the brief, factual and inviting, suited to the placement named in the brief. No HTML, emoji or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'block_button' => <<<'PROMPT'
You write button labels for a company website. Return only a plain list, one label per line, with no numbering, bullets, quotes, prefixes or explanation. Each label must be a single line of one to four words and at most 40 characters, starting with a verb and naming the action plainly. No HTML, emoji, punctuation or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'about_body' => <<<'PROMPT'
You write the body introduction for a company's About Us page. Return only a plain list, one introduction per line, with no numbering, bullets, quotes, prefixes or explanation. Each introduction must be a single line of two to four sentences and at most 1200 characters, written in the language named in the brief, factual, warm and professional, grounded in the draft text when one is provided. No HTML, emoji or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
        'about_statement' => <<<'PROMPT'
You write mission and vision statements for a company's About Us page. Return only a plain list, one statement per line, with no numbering, bullets, quotes, prefixes or explanation. Each statement must be a single line of one to three sentences and at most 600 characters, written in the language named in the brief, concrete and credible, suited to the placement named in the brief. No HTML, emoji, buzzwords or clickbait. Do not repeat the current text listed in the brief.
PROMPT,
    ];

    /**
     * Hard length ceilings applied after the model answers. Meta descriptions
     * are capped at the optimal SERP length even though the column allows 300.
     *
     * @var array<string, int>
     */
    private const MAX_LENGTHS = [
        'excerpt' => Post::EXCERPT_MAX,
        'meta_title' => 70,
        'meta_description' => 160,
        'block_paragraph' => 600,
        'block_heading' => 120,
        'block_text' => 300,
        'block_button' => 40,
        'about_body' => 1200,
        'about_statement' => 600,
    ];

    /**
     * @param SuggestionBroker $broker The shared flag, cache and failure gate.
     */
    public function __construct(private readonly SuggestionBroker $broker) {}

    /**
     * Suggest copy lines for the given brief.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string> Between zero and the configured count of lines.
     *
     * @throws AiSuggestionException When suggestions are off or the provider fails.
     */
    public function suggest(CopySuggestionContext $context): array
    {
        $count = max(1, (int) config('ai.copy.count', 4));
        $fallback = $this->localCandidates($context, $count);

        // Long-copy kinds outgrow the shared budget: four 1200-char About Us
        // bodies need ~1600 tokens before any reasoning overhead. Estimate the
        // answer's size from the kind's ceiling (~3 chars/token) so the batch
        // is not truncated mid-list; it stays a ceiling, not a target.
        $cap = self::MAX_LENGTHS[$context->kind] ?? Post::EXCERPT_MAX;
        $estimatedTokens = (int) ceil($cap * $count / 3);

        // Voice presets steer tone only; an unknown or empty key leaves the
        // kind's system prompt untouched. Array lookup, not a config path, so
        // a stray dot in the key can never traverse the config tree.
        $system = self::SYSTEM_PROMPTS[$context->kind] ?? self::SYSTEM_PROMPTS['excerpt'];
        $tones = (array) config('ai.tones', []);
        $guidance = $tones[$context->tone]['guidance'] ?? '';

        if (is_string($guidance) && $guidance !== '') {
            $system .= "\n\n".$guidance;
        }

        $request = new SuggestionRequest(
            system: $system,
            prompt: $context->prompt(),
            model: (string) config('ai.model'),
            maxTokens: max(1, (int) config('ai.max_tokens', 700), $estimatedTokens),
            fallbackCandidates: $fallback,
            temperature: (float) config('ai.temperature', 0.7),
        );

        $candidates = array_merge($this->broker->suggest($request)->all(), $fallback);

        return $this->postProcess($candidates, $context, $count);
    }

    /**
     * Deterministic ideas used by the offline client and as a live fallback.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     * @param int                   $count   How many to generate.
     *
     * @return list<string> Unfiltered candidate strings.
     */
    private function localCandidates(CopySuggestionContext $context, int $count): array
    {
        $ideas = match ($context->kind) {
            'meta_title' => $this->metaTitleIdeas($context),
            'meta_description' => $this->metaDescriptionIdeas($context),
            'block_paragraph' => $this->blockParagraphIdeas($context),
            'block_heading' => $this->blockHeadingIdeas($context),
            'block_text' => $this->blockTextIdeas($context),
            'block_button' => $this->blockButtonIdeas(),
            'about_body' => $this->aboutBodyIdeas($context),
            'about_statement' => $this->aboutStatementIdeas($context),
            default => $this->excerptIdeas($context),
        };

        return array_slice($ideas, 0, max(1, min($count + 1, count($ideas))));
    }

    /**
     * Excerpt ideas: the draft's opening sentences, then title-based templates.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function excerptIdeas(CopySuggestionContext $context): array
    {
        $ideas = [];

        if ($context->body !== '') {
            $ideas[] = $this->openingSentences($context->body, self::MAX_LENGTHS['excerpt']);
        }

        $seed = $context->subject !== '' ? $context->subject : 'this topic';
        $lower = Str::lower($seed);

        $ideas[] = 'A practical guide to '.$lower.'.';
        $ideas[] = 'What to know about '.$lower.' before you start.';
        $ideas[] = 'Discover '.$lower.': key points explained simply.';

        return $ideas;
    }

    /**
     * Meta title ideas built from the subject and the configured separator.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function metaTitleIdeas(CopySuggestionContext $context): array
    {
        $site = $context->siteName;
        $separator = ' '.(string) config('seo.separator', '|').' ';

        if ($context->subject === '' || $context->subject === $site) {
            return [
                $site,
                $site.' — Official Site',
                'Welcome to '.$site,
            ];
        }

        return [
            $context->subject.$separator.$site,
            $context->subject.' - '.$site,
            $context->subject,
            $site.$separator.$context->subject,
        ];
    }

    /**
     * Meta description ideas built from the subject, site and first keyword.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function metaDescriptionIdeas(CopySuggestionContext $context): array
    {
        $subject = $context->subject !== '' ? $context->subject : $context->siteName;
        $site = $context->siteName;

        $ideas = [
            'Discover '.$subject.' at '.$site.'. Find out more today.',
            'Looking for '.Str::lower($subject).'? '.$site.' can help. Get in touch today.',
            $subject.' — trusted information and services from '.$site.'.',
        ];

        $keyword = trim(explode(',', $context->keywords)[0]);

        if ($keyword !== '') {
            $ideas[] = 'Explore '.$keyword.' with '.$site.'. Learn more about '.$subject.' and get in touch with our team today.';
        }

        return $ideas;
    }

    /**
     * Paragraph ideas: the draft's opening sentences, then subject templates.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function blockParagraphIdeas(CopySuggestionContext $context): array
    {
        $ideas = [];

        if ($context->body !== '') {
            $ideas[] = $this->openingSentences($context->body, self::MAX_LENGTHS['block_paragraph']);
        }

        $seed = $context->subject !== '' ? $context->subject : 'our work';
        $lower = Str::lower($seed);

        $ideas[] = 'Learn more about '.$lower.' and how '.$context->siteName.' can help.';
        $ideas[] = 'Discover '.$lower.': clear answers and practical guidance from '.$context->siteName.'.';
        $ideas[] = 'Everything you need to know about '.$lower.', explained simply.';

        return $ideas;
    }

    /**
     * Heading ideas built from the subject.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function blockHeadingIdeas(CopySuggestionContext $context): array
    {
        if ($context->subject === '') {
            return ['What we do', 'Why it matters', 'How we can help', 'The essentials'];
        }

        return [
            $context->subject,
            'About '.$context->subject,
            $context->subject.': what to know',
            'Understanding '.$context->subject,
        ];
    }

    /**
     * Short supporting text ideas for CTA descriptions and alert messages.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function blockTextIdeas(CopySuggestionContext $context): array
    {
        $seed = $context->subject !== '' ? $context->subject : 'our services';
        $lower = Str::lower($seed);

        return [
            'Find out more about '.$lower.' and how we can help.',
            'Get clear, practical answers about '.$lower.' from our team.',
            'Have a question about '.$lower.'? Get in touch and we will walk you through it.',
        ];
    }

    /**
     * Button label ideas: short verb phrases for the site's main actions.
     *
     * @return list<string>
     */
    private function blockButtonIdeas(): array
    {
        return ['Get in touch', 'Request a quote', 'Learn more', 'Book an appointment'];
    }

    /**
     * About Us body ideas: the draft's opening sentences, then site templates.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function aboutBodyIdeas(CopySuggestionContext $context): array
    {
        $ideas = [];

        if ($context->body !== '') {
            $ideas[] = $this->openingSentences($context->body, self::MAX_LENGTHS['about_body']);
        }

        $site = $context->siteName;

        $ideas[] = 'At '.$site.', we focus on quality workmanship and honest advice. Our team brings hands-on experience to every job, and we treat every customer the way we would want to be treated.';
        $ideas[] = $site.' was built on a simple idea: do the job properly and stand behind it. From the first enquiry to the finished work, we keep things clear, careful and personal.';
        $ideas[] = 'We are a hands-on team that cares about the details. At '.$site.', every customer gets straight answers, careful work and a result we are proud to put our name on.';

        return $ideas;
    }

    /**
     * Mission and vision statement ideas.
     *
     * @param CopySuggestionContext $context The bounded, redacted brief.
     *
     * @return list<string>
     */
    private function aboutStatementIdeas(CopySuggestionContext $context): array
    {
        $site = $context->siteName;

        return [
            'To deliver dependable, high-quality service that earns our customers\' trust every day.',
            'To be the name our community trusts first for quality, care and honest advice.',
            'To make every '.$site.' experience straightforward: clear communication, careful work and results that last.',
        ];
    }

    /**
     * Take whole sentences from the start of the draft until the cap is hit.
     *
     * @param string $body Redacted draft prose.
     * @param int    $cap  Maximum excerpt length.
     *
     * @return string The opening prose, never longer than the cap.
     */
    private function openingSentences(string $body, int $cap): string
    {
        $sentences = preg_split('/(?<=[.!?])\s+/u', $body) ?: [];
        $opening = '';

        foreach ($sentences as $sentence) {
            if (mb_strlen($opening) + mb_strlen($sentence) + 1 > $cap) {
                break;
            }

            $opening = trim($opening.' '.$sentence);
        }

        return $opening !== '' ? $opening : Str::substr($body, 0, $cap);
    }

    /**
     * Turn raw lines into a clean, de-duplicated list of valid copy lines.
     *
     * @param list<string>          $candidates Raw provider and local candidates.
     * @param CopySuggestionContext $context    The bounded, redacted brief.
     * @param int                   $count      Maximum number of lines to return.
     *
     * @return list<string> Validated lines, never longer than the kind's ceiling.
     */
    private function postProcess(array $candidates, CopySuggestionContext $context, int $count): array
    {
        $cap = self::MAX_LENGTHS[$context->kind] ?? Post::EXCERPT_MAX;
        $current = mb_strtolower(trim($context->current));
        $seen = [];
        $lines = [];

        foreach ($candidates as $candidate) {
            if (! is_string($candidate)) {
                continue;
            }

            $line = $this->normalise($candidate, $cap);
            $key = mb_strtolower($line);

            if ($line === '' || $key === $current || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $lines[] = $line;

            if (count($lines) >= $count) {
                break;
            }
        }

        return $lines;
    }

    /**
     * Clean one candidate line and enforce the field's length ceiling.
     *
     * @param string $value Raw candidate text.
     * @param int    $cap   Maximum length for this copy kind.
     *
     * @return string A safe line of at most $cap characters.
     */
    private function normalise(string $value, int $cap): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        $value = trim($value, " \t\n\r\0\x0B\"'`");
        $value = preg_replace('/^(?:\d+[.)]|[-*\x{2022}])\s*/u', '', $value) ?? $value;

        return trim(Str::substr(trim($value), 0, $cap));
    }
}
