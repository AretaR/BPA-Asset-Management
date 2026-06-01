<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    public function show(string $path)
    {
        $disk = Storage::disk('public');
        
        // Clean the path
        $path = ltrim($path, '/');
        
        if (!$disk->exists($path)) {
            Log::warning('Media file not found', [
                'path' => $path,
                'full_path' => $disk->path($path),
                'disk_root' => $disk->path(''),
            ]);
            abort(404, "File not found: {$path}");
        }

        try {
            $mimeType = $disk->mimeType($path);
            $content = $disk->get($path);
            $size = $disk->size($path);

            return response($content)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', $size)
                ->header('Cache-Control', 'public, max-age=31536000');
        } catch (\Exception $e) {
            Log::error('Error serving media file', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            abort(500, 'Error serving file');
        }
    }
}
