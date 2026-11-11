<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Services\Ai\Contracts\SuggestionClient;
use App\Services\Ai\DataTransferObjects\SuggestionRequest;
use App\Services\Ai\DataTransferObjects\SuggestionResult;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Chat-completions client for OpenAI and any API that speaks the same shape.
 *
 * The model is asked for plain, unnumbered lines rather than JSON: a completion
 * with a stray comma or a trailing explanation is otherwise enough to lose the
 * whole batch. Nothing from the response is logged, and every failure collapses
 * into one human-readable exception so credentials and provider text stay out
 * of the admin panel.
 */
final class OpenAiCompatibleClient implements SuggestionClient
{
    /**
     * Produce a batch of raw candidate lines for the given request.
     *
     * @param SuggestionRequest $request The already-redacted, capped prompt.
     *
     * @return SuggestionResult Unfiltered candidates for the domain service.
     *
     * @throws AiSuggestionException When the key is missing, the call times out,
     *                               the provider fails, or the response has no
     *                               recognisable chat-completions message.
     */
    public function suggest(SuggestionRequest $request): SuggestionResult
    {
        $key = trim((string) config('ai.key'));

        if ($key === '') {
            throw AiSuggestionException::unavailable(cause: 'missing_key');
        }

        $timeout = max(1, (int) config('ai.timeout'));

        try {
            $response = Http::withToken($key)
                ->acceptJson()
                ->timeout($timeout)
                // Establishing a connection should fail fast; the long budget is
                // for the model's first token, not for a dead host.
                ->connectTimeout(min(5, $timeout))
                ->post(rtrim((string) config('ai.base_url'), '/').'/chat/completions', [
                    'model' => $request->model,
                    'temperature' => $request->temperature,
                    'max_tokens' => $request->maxTokens,
                    'messages' => [
                        ['role' => 'system', 'content' => $request->system],
                        ['role' => 'user', 'content' => $request->prompt],
                    ],
                ]);
        } catch (ConnectionException $exception) {
            $message = Str::lower($exception->getMessage());
            $cause = str_contains($message, 'timed out') || str_contains($message, 'timeout')
                ? 'connection_timeout'
                : 'connection_error';

            throw AiSuggestionException::unavailable($exception, $cause);
        }

        if (! $response->successful()) {
            throw AiSuggestionException::unavailable(cause: 'provider_http_'.$response->status());
        }

        $message = $response->json('choices.0.message');

        if (! is_array($message)) {
            throw AiSuggestionException::unavailable(cause: 'malformed_completion');
        }

        $content = $message['content'] ?? null;

        if (! is_string($content) || trim($content) === '') {
            // A well-formed 200 with no answer is a WEAK completion, not an
            // outage. Reasoning models do exactly this when max_tokens is spent
            // on their `reasoning` field (content=null, finish_reason=length).
            // Return no candidates so the domain service's deterministic local
            // fallbacks still fill the list instead of showing a generic 503.
            Log::warning('AI suggestion returned no usable content', [
                'cause' => $this->emptyContentCause($message, $response->json('choices.0.finish_reason')),
            ]);

            return new SuggestionResult([]);
        }

        return new SuggestionResult($this->parseLines(Str::limit($content, 4000, '')));
    }

    /**
     * Label why a well-formed completion carried no answer.
     *
     * @param array<string, mixed> $message      The chat-completions message.
     * @param mixed                $finishReason Provider-reported stop reason.
     *
     * @return string A fixed, log-only label; never provider text.
     */
    private function emptyContentCause(array $message, mixed $finishReason): string
    {
        if ($finishReason === 'length') {
            return 'completion_truncated';
        }

        if (isset($message['reasoning']) || isset($message['reasoning_details'])) {
            return 'reasoning_only_completion';
        }

        return 'empty_completion';
    }

    /**
     * Turn a free-form completion into one candidate per line.
     *
     * @param string $content The completion, already length-capped.
     *
     * @return list<string> Cleaned candidate lines, at most forty of them.
     */
    private function parseLines(string $content): array
    {
        $lines = preg_split('/\R/u', $content) ?: [];
        $candidates = [];

        foreach ($lines as $line) {
            $line = preg_replace('/^(?:\d+[.)]|[-*\x{2022}])\s*/u', '', trim($line)) ?? '';
            $line = trim($line, " \t\n\r\0\x0B\"'`");

            if ($line === '') {
                continue;
            }

            $candidates[] = $line;

            if (count($candidates) >= 40) {
                break;
            }
        }

        return $candidates;
    }
}
