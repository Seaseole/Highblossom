<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

final class ImageUploadController
{
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('image'),
            ], 422);
        }

        $file = $request->file('image');
        
        if (!$file || !$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file upload.',
            ], 422);
        }
        
        // Use native PHP move_uploaded_file for better Windows compatibility
        $filename = time() . '_' . $file->getClientOriginalName();
        $destination = storage_path('app/public/uploads/' . $filename);
        
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
        
        $path = 'uploads/' . $filename;
        
        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::url($path),
        ]);
    }
}
