<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Content\BuildAboutSuggestionContextAction;
use App\Http\Requests\Admin\AboutSuggestionRequest;
use App\Services\Ai\AiSuggestionException;
use App\Services\CopySuggestionService;
use Illuminate\Http\JsonResponse;

/**
 * Answer the About Us form's request for copy ideas, as JSON only.
 *
 * Deliberately thin: the action owns the brief, the service owns the rules, and
 * this class only maps the one allowed failure into a response the Alpine panel
 * can print. No page content is ever written to the database here.
 */
final class AboutSuggestionController
{
    /**
     * @param BuildAboutSuggestionContextAction $context     Builds the bounded brief.
     * @param CopySuggestionService             $suggestions Produces and validates copy lines.
     */
    public function __construct(
        private readonly BuildAboutSuggestionContextAction $context,
        private readonly CopySuggestionService $suggestions,
    ) {}

    /**
     * Return a list of suggested copy lines for the requested About Us field.
     *
     * @param AboutSuggestionRequest $request The validated form payload.
     *
     * @return JsonResponse Suggestions, or a readable message with an empty list.
     */
    public function __invoke(AboutSuggestionRequest $request): JsonResponse
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
