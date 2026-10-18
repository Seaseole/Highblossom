<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concerns\HasSortOrder;
use App\Rules\SortOrderIsUnique;
use App\Support\SortOrderRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Answers "is this sort order still free?" for the admin sort-order field.
 *
 * The field checks on blur and again on submit; this endpoint only ever reports,
 * it never writes, so the submit-time FormRequest rule remains the authority.
 */
final class SortOrderController extends Controller
{
    /**
     * Report whether the submitted number is free within the module's scope.
     */
    public function availability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'module' => ['required', 'string', Rule::in(SortOrderRegistry::keys())],
            'value' => ['required', 'integer', 'min:1'],
            'current' => ['nullable', 'integer', 'min:1'],
            'scope' => ['nullable', 'array'],
            'scope.*' => ['nullable', 'string', 'max:255'],
        ]);

        $module = $data['module'];
        abort_unless($request->user()?->can(SortOrderRegistry::ability($module)), 403);

        /** @var HasSortOrder $modelClass */
        $modelClass = SortOrderRegistry::model($module);
        $instance = new $modelClass;
        $scopeColumns = $instance->sortOrderScopeColumns();
        $scopeValues = array_filter(
            Arr::only((array) ($data['scope'] ?? []), $scopeColumns),
            fn ($value) => $value !== null && $value !== '',
        );

        // Without every scope column the question cannot be answered; report nothing
        // rather than guess a scope and warn about a number that is actually free.
        if (count($scopeValues) !== count($scopeColumns)) {
            return response()->json([
                'available' => true,
                'message' => null,
                'next' => null,
            ]);
        }

        $clash = $modelClass::findBySortOrder(
            (int) $data['value'],
            $scopeValues,
            isset($data['current']) ? (int) $data['current'] : null,
        );

        return response()->json([
            'available' => $clash === null,
            'message' => $clash === null ? null : SortOrderIsUnique::messageFor($clash, (int) $data['value']),
            'next' => $modelClass::nextSortOrder($scopeValues),
        ]);
    }
}
