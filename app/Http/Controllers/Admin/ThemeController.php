<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\Theme;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Handle user theme preference storage (light/dark).
 */
final class ThemeController extends Controller
{
    /**
     * Store or update the authenticated user's theme preference.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme' => ['required', Rule::enum(Theme::class)],
        ]);

        if (auth()->check()) {
            auth()->user()->update([
                'theme' => $validated['theme'],
            ]);
        }

        return response()->json([
            'success' => true,
            'theme' => $validated['theme'],
        ]);
    }
}
