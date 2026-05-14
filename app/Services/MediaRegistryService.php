<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Content\Models\MediaRegistry;
use App\Domains\Content\Models\MediaUsage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

final class MediaRegistryService
{
    public function register(string $path, string $originalName, int $fileSize, Model $model, string $attribute): MediaRegistry
    {
        $registry = MediaRegistry::firstOrCreate(['path' => $path], [
            'original_name' => $originalName,
            'file_size' => $fileSize,
        ]);

        MediaUsage::updateOrCreate([
            'media_registry_id' => $registry->id,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'attribute' => $attribute,
        ]);

        return $registry;
    }

    public function unregister(Model $model, string $attribute): void
    {
        MediaUsage::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->where('attribute', $attribute)
            ->delete();
    }

    public function forceDelete(int $mediaRegistryId): bool
    {
        $registry = MediaRegistry::find($mediaRegistryId);

        if (!$registry) {
            return false;
        }

        if ($registry->usages()->count() > 0) {
            return false;
        }

        Storage::disk('public')->delete($registry->path);
        return $registry->delete();
    }
}
