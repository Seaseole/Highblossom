<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use FFMpeg\FFMpeg;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final class VideoUploadController
{
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'video' => 'required|mimes:mp4,webm,mov,avi|max:30720', // 30MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('video'),
            ], 422);
        }

        $file = $request->file('video');

        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file upload.',
            ], 422);
        }

        // Use native PHP move_uploaded_file for better Windows compatibility
        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = public_path('videos/' . $filename);

        // Ensure directory exists
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        if (!move_uploaded_file($file->getPathname(), $destination)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move uploaded file.',
            ], 500);
        }

        $path = 'videos/' . $filename;
        $thumbnailPath = null;

        // Generate thumbnail using FFmpeg
        try {
            $thumbnailPath = $this->generateThumbnail($destination, $filename);
        } catch (\Exception $e) {
            Log::warning('Failed to generate video thumbnail: ' . $e->getMessage());
            // Continue without thumbnail - not a critical failure
        }

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset($path),
            'thumbnail' => $thumbnailPath ? asset($thumbnailPath) : null,
        ]);
    }

    protected function generateThumbnail(string $videoPath, string $filename): ?string
    {
        try {
            // Try to detect FFmpeg binaries automatically
            // On Windows, this requires FFmpeg to be in PATH or installed at common locations
            $ffmpeg = FFMpeg::create();

            $video = $ffmpeg->open($videoPath);

            // Create thumbnail directory
            $thumbnailDir = public_path('videos/thumbnails');
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Generate thumbnail filename
            $thumbnailFilename = 'thumb_' . pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
            $thumbnailPath = $thumbnailDir . '/' . $thumbnailFilename;

            // Extract frame at 1 second mark
            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));

            // Save as JPEG with 85% quality
            $frame->save($thumbnailPath, 85);

            return 'videos/thumbnails/' . $thumbnailFilename;
        } catch (\Exception $e) {
            Log::error('FFmpeg thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
