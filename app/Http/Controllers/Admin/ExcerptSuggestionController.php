<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Content\BuildExcerptSuggestionContextAction;
use App\Http\Requests\Admin\ExcerptSuggestionRequest;
use App\Services\Ai\AiSuggestionException;
use App\Services\CopySuggestionService;
use Illuminate\Http\JsonResponse;

/**
 * Answer the post editor's request for excerpt ideas, as JSON only.
 *
 * Deliberately thin: the action owns the brief, the service owns the rules, and
 * this class only maps the one allowed failure into a response the Alpine panel
 * can print. No excerpt is ever written to the database here.
 */
final class ExcerptSuggestionController
{
    /**
     * @param BuildExcerptSuggestionContextAction $context     Builds the bounded brief.
     * @param CopySuggestionService               $suggestions Produces and validates excerpts.
     */
    public function __construct(
        private readonly BuildExcerptSuggestionContextAction $context,
        private readonly CopySuggestionService $suggestions,
    ) {}

    /**
     * Return a list of suggested excerpts for the submitted draft.
     *
     * @param ExcerptSuggestionRequest $request The validated draft payload.
     *
     * @return JsonResponse Excerpts, or a readable message with an empty list.
     */
    public function __invoke(ExcerptSuggestionRequest $request): JsonResponse
    {
        try {
            $context = ($this->context)($request->validated());
            $excerpts = $this->suggestions->suggest($context);
        } catch (AiSuggestionException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'excerpts' => [],
            ], $exception->httpStatus);
        }

        return response()->json(['excerpts' => $excerpts]);
    }
}
