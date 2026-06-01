<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure storage link exists
        $this->ensureStorageLink();

        Route::fallback(function () {
            $path = request()->path();
            if (str_starts_with($path, 'storage/')) {
                $filePath = Storage::disk('public')->path(substr($path, 8));
                if (file_exists($filePath)) {
                    return response()->file($filePath);
                }
            }
            abort(404);
        });
    }

    /**
     * Ensure the storage link exists.
     */
    protected function ensureStorageLink(): void
    {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');

        if (!file_exists($publicPath) && is_dir($storagePath)) {
            try {
                symlink($storagePath, $publicPath);
            } catch (\Exception $e) {
                // Log error but don't break the application
                \Log::error('Failed to create storage link: ' . $e->getMessage());
            }
        }
    }
}
