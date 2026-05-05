<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ImageUploadRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;

final class ImageUploadController
{
    public function __construct(
        private readonly ImageUploadService $imageUploadService,
    ) {}

    public function upload(ImageUploadRequest $request): JsonResponse
    {
        try {
            $folder = $request->input('folder', 'uploads');
            $result = $this->imageUploadService->upload($request->file('image'), $folder);

            return response()->json([
                'success' => true,
                'path' => $result['path'],
                'url' => $result['url'],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
