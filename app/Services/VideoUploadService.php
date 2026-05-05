<?php

declare(strict_types=1);

namespace App\Services;

use FFMpeg\FFMpeg;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

final class VideoUploadService
{
    public function upload(UploadedFile $file): array
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = public_path('videos/' . $filename);

        $this->ensureDirectoryExists(dirname($destination));

        if (!move_uploaded_file($file->getPathname(), $destination)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        $path = 'videos/' . $filename;
        $thumbnailPath = $this->generateThumbnail($destination, $filename);

        return [
            'path' => $path,
            'thumbnail' => $thumbnailPath,
        ];
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    private function generateThumbnail(string $videoPath, string $filename): ?string
    {
        try {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($videoPath);

            $thumbnailDir = public_path('videos/thumbnails');
            $this->ensureDirectoryExists($thumbnailDir);

            $thumbnailFilename = 'thumb_' . pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
            $thumbnailPath = $thumbnailDir . '/' . $thumbnailFilename;

            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
            $frame->save($thumbnailPath, 85);

            return 'videos/thumbnails/' . $thumbnailFilename;
        } catch (\Exception $e) {
            Log::error('FFmpeg thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
}
