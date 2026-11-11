<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Content\BuildSeoMetaSuggestionContextAction;
use App\Http\Requests\Admin\SeoMetaSuggestionRequest;
use App\Services\Ai\AiSuggestionException;
use App\Services\CopySuggestionService;
use Illuminate\Http\JsonResponse;

/**
 * Answer an SEO form's request for meta title or description ideas, as JSON.
 *
 * Deliberately thin: the action owns the brief, the service owns the rules, and
 * this class only maps the one allowed failure into a response the Alpine panel
 * can print. No SEO value is ever written to the database here.
 */
final class SeoMetaSuggestionController
{
    /**
     * @param BuildSeoMetaSuggestionContextAction $context     Builds the bounded brief.
     * @param CopySuggestionService               $suggestions Produces and validates meta lines.
     */
    public function __construct(
        private readonly BuildSeoMetaSuggestionContextAction $context,
        private readonly CopySuggestionService $suggestions,
    ) {}

    /**
     * Return a list of suggested meta lines for the requested field.
     *
     * @param SeoMetaSuggestionRequest $request The validated SEO payload.
     *
     * @return JsonResponse Suggestions, or a readable message with an empty list.
     */
    public function __invoke(SeoMetaSuggestionRequest $request): JsonResponse
    {
        try {
            $context = ($this->context)($request->validated());
            $suggestions = $this->suggestions->suggest($context);
        } catch (AiSuggestionException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'suggestions' => [],
            ], $exception->httpStatus);
        }

        return response()->json(['suggestions' => $suggestions]);
    }
}
