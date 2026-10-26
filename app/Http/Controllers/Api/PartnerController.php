<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use App\Models\Partner;
use App\Support\SortOrderRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Manage business partners for the app.
 *
 * Mirrors the web admin's validation and logo processing (webp with a plain-store
 * fallback). The logo is optional here so the mobile client can manage the text
 * record now and attach a logo later. Gated by `can:manage settings`.
 */
final class PartnerController extends Controller
{
    /**
     * List partners ordered by position.
     */
    public function index(): AnonymousResourceCollection
    {
        return PartnerResource::collection(Partner::orderBy('order')->orderBy('id')->get());
    }

    /**
     * Show one partner.
     */
    public function show(Partner $partner): PartnerResource
    {
        return new PartnerResource($partner);
    }

    /**
     * Create a partner.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // logo_path is NOT NULL on the table, so a logo is required to create.
            'logo' => ['required', 'image', 'max:2048'],
            'website_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'order' => SortOrderRules::rules(Partner::class, $request->all()),
        ]);

        $partner = Partner::create([
            'name' => $validated['name'],
            'logo_path' => $this->storeLogo($request, null),
            'website_url' => $validated['website_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'order' => $validated['order'] ?? null,
        ]);

        return response()->json(['data' => new PartnerResource($partner)], 201);
    }

    /**
     * Update a partner.
     */
    public function update(Request $request, Partner $partner): PartnerResource
    {
        SortOrderRules::normalize($request, Partner::class, $partner);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'website_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'order' => SortOrderRules::rules(Partner::class, $request->all(), $partner),
        ]);

        if ($request->hasFile('logo')) {
            $partner->logo_path = $this->storeLogo($request, $partner->logo_path);
        }

        $partner->update([
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
            'is_active' => $request->boolean('is_active', $partner->is_active),
            'order' => $validated['order'],
        ]);

        return new PartnerResource($partner->fresh());
    }

    /**
     * Delete a partner and their logo.
     */
    public function destroy(Partner $partner): JsonResponse
    {
        if ($partner->logo_path) {
            Storage::disk('public')->delete($partner->logo_path);
        }
        $partner->delete();

        return response()->json(['message' => 'Partner removed.']);
    }

    /**
     * Store an uploaded logo as webp, replacing any previous file, with a
     * plain-store fallback if image processing is unavailable.
     */
    private function storeLogo(Request $request, ?string $previousPath): string
    {
        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        try {
            return Image::fromUpload($request->file('logo'))
                ->contain(width: 800, height: 800)
                ->toWebp()
                ->quality(82)
                ->store('partners', 'public');
        } catch (ImageException $e) {
            Log::error('Failed to process partner logo: '.$e->getMessage());

            return $request->file('logo')->store('partners', 'public');
        }
    }
}
