<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| AI-assisted authoring suggestions
|--------------------------------------------------------------------------
|
| One switch owns the whole feature. With `enabled` false (the default) no
| draft text can leave the server and the suggestion endpoints answer with a
| readable 422 instead of an error. Credentials live in the environment only.
|
| This file is the single source of truth for AI settings. The `ai` blocks in
| config/seo.php and config/trace-replay.php are legacy and are not read here.
|
*/

return [
    'enabled' => (bool) env('AI_SUGGESTIONS_ENABLED', false),
    'provider' => (string) env('AI_SUGGESTIONS_PROVIDER', 'openai-compatible'),
    'key' => (string) env('AI_SUGGESTIONS_KEY', ''),
    'base_url' => (string) env('AI_SUGGESTIONS_BASE_URL', 'https://api.openai.com/v1'),
    'model' => (string) env('AI_SUGGESTIONS_MODEL', 'gpt-4o-mini'),
    // Total budget for one completion. Hosted models rarely answer inside 3s:
    // a small free-tier model measured ~2.9s locally, so a 3s cap turned a
    // working provider into a generic 503 on every other click. Keep this well
    // above first-token latency; the connect timeout is capped separately.
    'timeout' => (int) env('AI_SUGGESTIONS_TIMEOUT', 20),
    // Ceiling only - models that finish early are billed for what they write.
    // Reasoning models spend most of this budget on an internal `reasoning`
    // field; at 300 they hit `finish_reason: length` with `content: null` and
    // no suggestions at all. 700 leaves room for both thinking and the answer.
    'max_tokens' => (int) env('AI_SUGGESTIONS_MAX_TOKENS', 700),
    'temperature' => (float) env('AI_SUGGESTIONS_TEMPERATURE', 0.7),
    'rate_limit' => (int) env('AI_SUGGESTIONS_RATE_LIMIT', 10),
    'cache_ttl' => (int) env('AI_SUGGESTIONS_CACHE_TTL', 300),

    // Hard ceiling on how much draft prose is put into a prompt. Applied on the
    // server, never read from the request.
    'context_max_chars' => 2000,

    'titles' => [
        'count' => 8,
    ],

    'usernames' => [
        'count' => 6,
        'pool' => 18,
    ],

    // Excerpt and SEO meta suggestions share one batch size; the browser never
    // sends a count, so this is the single place operators change it.
    'copy' => [
        'count' => 4,
    ],

    // Voice presets ("personas") for copy suggestions. The company setting
    // `ai_tone` seeds the panel default; a writer may override it per request.
    // Guidance is appended to the kind's system prompt, so it steers voice
    // without touching the kind's shape rules. The offline client ignores it.
    // Distilled from the copywriting / CRO / SEO audit disciplines: one
    // sentence each, no few-shot examples (they would leak into every batch).
    'tones' => [
        'professional' => [
            'label' => 'Professional',
            'guidance' => 'Write in a polished, factual business voice: clear, confident and courteous, with plain words and no slang, hype or exclamation marks.',
        ],
        'friendly' => [
            'label' => 'Friendly',
            'guidance' => 'Write in a warm, conversational voice, like a helpful local expert: short sentences, everyday words and gentle encouragement, without slang or hype.',
        ],
        'seo' => [
            'label' => 'SEO-focused',
            'guidance' => 'Write for search visibility: lead with the main subject keyword, work any listed keywords in naturally and front-load the important words, without keyword stuffing or clickbait.',
        ],
    ],

    // Rule-based nudges. No prompt is sent for these: the scanner measures
    // local signals and phrases them from templates. Thresholds are product
    // behaviour, not operator tuning, so they are not env keys.
    'nudges' => [
        'max_active' => 3,
        'snooze_days' => 3,
        'quote_stale_hours' => 48,
        'draft_stale_days' => 7,
        'blog_quiet_days' => 30,
        'booking_window_days' => 7,
    ],
];
