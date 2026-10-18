<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\BlogSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Returns the blog command palette payload for a term, or recent posts without one.
 */
final class BlogSearchController extends Controller
{
    /**
     * Longest term the palette will search; longer input is rejected, not truncated.
     */
    private const MAX_QUERY_LENGTH = 80;

    /**
     * Respond to a predictive blog search request.
     *
     * @param Request $request Current request, carrying the optional `q` term
     */
    public function search(Request $request, BlogSearchService $search): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:'.self::MAX_QUERY_LENGTH],
        ], [
            'q.max' => 'Search terms are limited to '.self::MAX_QUERY_LENGTH.' characters.',
        ]);

        return response()->json($search->search($validated['q'] ?? null));
    }
}
