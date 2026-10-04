<?php

declare(strict_types=1);

namespace App\Services;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores uploaded videos on the public disk under generated file names.
 */
final class VideoUploadService
{
    /**
     * File extensions the video pipeline accepts.
     *
     * @var array<int, string>
     */
    private const ALLOWED_EXTENSIONS = ['mp4', 'webm', 'mov', 'avi'];

    /**
     * Store the uploaded video and generate a poster frame.
     *
     * @return array{path: string, thumbnail: string|null}
     *
     * @throws \RuntimeException When the extension is unsupported or storage fails
     */
    public function upload(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \RuntimeException('Unsupported video file type.');
        }

        $filename = time().'_'.Str::random(16).'.'.$extension;
        $storedPath = $file->storeAs('videos', $filename, 'public');

        if ($storedPath === false) {
            throw new \RuntimeException('Failed to store uploaded file.');
        }

        $thumbnailPath = $this->generateThumbnail($storedPath, $filename);

        return [
            'path' => 'storage/'.$storedPath,
            'thumbnail' => $thumbnailPath === null ? null : 'storage/'.$thumbnailPath,
        ];
    }

    /**
     * Extract a poster frame from a stored video.
     *
     * @return string|null Public-disk relative path, or null when extraction fails
     */
    private function generateThumbnail(string $storedPath, string $filename): ?string
    {
        try {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open(Storage::disk('public')->path($storedPath));

            $thumbnailRelative = 'videos/thumbnails/thumb_'.pathinfo($filename, PATHINFO_FILENAME).'.jpg';
            $thumbnailAbsolute = Storage::disk('public')->path($thumbnailRelative);

            $directory = dirname($thumbnailAbsolute);

            if (! is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $frame = $video->frame(TimeCode::fromSeconds(1));
            $frame->save($thumbnailAbsolute, 85);

            return $thumbnailRelative;
        } catch (\Exception $e) {
            Log::error('FFmpeg thumbnail generation failed: '.$e->getMessage());

            return null;
        }
    }
}
