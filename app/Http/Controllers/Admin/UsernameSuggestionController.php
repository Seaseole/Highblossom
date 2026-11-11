<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UsernameSuggestionRequest;
use App\Services\Ai\AiSuggestionException;
use App\Services\UsernameSuggestionService;
use Illuminate\Http\JsonResponse;

/**
 * Answer the staff and profile forms with a short list of available usernames.
 *
 * JSON only, and read-only: no user row is written here. The service owns the
 * availability guarantee; this class only maps the one allowed failure into a
 * message the Alpine panel can print.
 */
final class UsernameSuggestionController
{
    /**
     * @param UsernameSuggestionService $usernames Produces and verifies candidates.
     */
    public function __construct(private readonly UsernameSuggestionService $usernames) {}

    /**
     * Return available username ideas for the submitted form values.
     *
     * @param UsernameSuggestionRequest $request The validated payload.
     *
     * @return JsonResponse Usernames, or a readable message with an empty list.
     */
    public function __invoke(UsernameSuggestionRequest $request): JsonResponse
    {
        $input = $request->suggestionInput();

        try {
            $usernames = $this->usernames->suggest(
                $input['name'],
                $input['email'],
                $input['count'],
                $input['ignore_user_id'],
            );
        } catch (AiSuggestionException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'usernames' => [],
            ], $exception->httpStatus);
        }

        return response()->json(['usernames' => $usernames]);
    }
}
