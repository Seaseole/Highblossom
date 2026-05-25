<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class ImageUploadService
{
    public function upload(UploadedFile $file, string $folder = 'uploads'): array
    {
        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('Invalid file upload.');
        }

        // Use native PHP move_uploaded_file for better Windows compatibility
        $filename = time().'_'.$file->getClientOriginalName();
        $destination = storage_path('app/public/'.$folder.'/'.$filename);

        $this->ensureDirectoryExists(dirname($destination));

        if (! move_uploaded_file($file->getPathname(), $destination)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        $path = $folder.'/'.$filename;

        return [
            'path' => $path,
            'url' => Storage::url($path),
        ];
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
