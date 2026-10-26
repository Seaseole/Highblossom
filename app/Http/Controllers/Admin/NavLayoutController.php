<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AdminNavLayout;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Store the signed-in account's admin navigation layout.
 *
 * Written as a sibling of `ThemeController` rather than folded into it, because the two
 * preferences are decided by different things: a theme is a colour the browser applies with one
 * class, and a layout is which navigation the panel is drawn with. The contract is deliberately
 * identical — validate, persist on the account, answer with JSON — so the bar can POST either
 * one and never have to reload a page to find out what it chose.
 *
 * There is no ability check beyond the admin group's own `can:access admin panel`: this writes
 * one person's own preference, which is the same kind of thing the theme already lets them do.
 */
final class NavLayoutController extends Controller
{
    /**
     * Store or update the authenticated user's navigation layout.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'admin_nav_layout' => ['required', Rule::enum(AdminNavLayout::class)],
        ]);

        if (auth()->check()) {
            auth()->user()->update([
                'admin_nav_layout' => $validated['admin_nav_layout'],
            ]);
        }

        return response()->json([
            'success' => true,
            'admin_nav_layout' => $validated['admin_nav_layout'],
        ]);
    }
}
