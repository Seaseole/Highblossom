<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Content\BuildTitleSuggestionContextAction;
use App\Http\Requests\Admin\TitleSuggestionRequest;
use App\Services\Ai\AiSuggestionException;
use App\Services\PostTitleSuggestionService;
use Illuminate\Http\JsonResponse;

/**
 * Answer the post editor's request for headline ideas, as JSON only.
 *
 * Deliberately thin: the action owns the brief, the service owns the rules, and
 * this class only maps the one allowed failure into a response the Alpine panel
 * can print. No title is ever written to the database here.
 */
final class TitleSuggestionController
{
    /**
     * @param BuildTitleSuggestionContextAction $context Builds the bounded brief.
     * @param PostTitleSuggestionService        $titles  Produces and validates headlines.
     */
    public function __construct(
        private readonly BuildTitleSuggestionContextAction $context,
        private readonly PostTitleSuggestionService $titles,
    ) {}

    /**
     * Return a list of suggested titles for the submitted draft.
     *
     * @param TitleSuggestionRequest $request The validated draft payload.
     *
     * @return JsonResponse Titles, or a readable message with an empty list.
     */
    public function __invoke(TitleSuggestionRequest $request): JsonResponse
    {
        try {
            $context = ($this->context)($request->validated());
            $titles = $this->titles->suggest($context);
        } catch (AiSuggestionException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'titles' => [],
            ], $exception->httpStatus);
        }

        return response()->json(['titles' => $titles]);
    }
}
