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
 *
 * The poster frame is extracted from the PHP upload temp file before the video
 * is stored, because the public disk may be an object store with no readable
 * local path. Nothing here ever assumes a filesystem location for stored media.
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
     * @return array{path: string, thumbnail: string|null} Disk-relative paths
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
        $thumbnailPath = 'videos/thumbnails/thumb_'.pathinfo($filename, PATHINFO_FILENAME).'.jpg';

        // Must run before storeAs(): storing moves the upload temp file away.
        $poster = $this->extractPoster($file);

        $storedPath = $file->storeAs('videos', $filename, 'public');

        if ($storedPath === false) {
            throw new \RuntimeException('Failed to store uploaded file.');
        }

        if ($poster === null) {
            return [
                'path' => $storedPath,
                'thumbnail' => null,
            ];
        }

        Storage::disk('public')->put($thumbnailPath, $poster);

        return [
            'path' => $storedPath,
            'thumbnail' => $thumbnailPath,
        ];
    }

    /**
     * Extract a poster frame from the upload while it is still a local file.
     *
     * @return string|null Raw JPEG bytes, or null when extraction fails
     */
    private function extractPoster(UploadedFile $file): ?string
    {
        $source = $file->getRealPath();

        if ($source === false || $source === '') {
            return null;
        }

        $target = sys_get_temp_dir().'/highblossom-poster-'.Str::random(12).'.jpg';

        try {
            $ffmpeg = FFMpeg::create();
            $frame = $ffmpeg->open($source)->frame(TimeCode::fromSeconds(1));
            $frame->save($target, 85);

            $contents = file_get_contents($target);

            return $contents === false ? null : $contents;
        } catch (\Exception $e) {
            Log::error('FFmpeg thumbnail generation failed: '.$e->getMessage());

            return null;
        } finally {
            if (is_file($target)) {
                @unlink($target);
            }
        }
    }
}
