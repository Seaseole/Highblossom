<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\VideoUploadRequest;
use App\Services\VideoUploadService;
use Illuminate\Http\JsonResponse;

final class VideoUploadController
{
    public function __construct(
        private readonly VideoUploadService $videoUploadService,
    ) {}

    public function upload(VideoUploadRequest $request): JsonResponse
    {
        try {
            $result = $this->videoUploadService->upload($request->file('video'));

            return response()->json([
                'success' => true,
                'path' => $result['path'],
                'url' => asset($result['path']),
                'thumbnail' => $result['thumbnail'] ? asset($result['thumbnail']) : null,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
