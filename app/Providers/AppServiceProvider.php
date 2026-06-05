<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\User;
use App\Observers\AssetObserver;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFive();

        if (env('AWS_BUCKET')) {
            $this->useS3ForPublicDisk();
        } else {
            $this->ensureStorageLink();
        }

        Asset::observe(AssetObserver::class);
        User::observe(UserObserver::class);

        Route::fallback(function () {
            $path = request()->path();
            if (str_starts_with($path, 'storage/')) {
                $disk = Storage::disk('public');
                if (method_exists($disk, 'path')) {
                    $filePath = $disk->path(substr($path, 8));
                    if (file_exists($filePath)) {
                        return response()->file($filePath);
                    }
                }
            }
            abort(404);
        });
    }

    protected function useS3ForPublicDisk(): void
    {
        $s3Config = config('filesystems.disks.s3');
        $s3Config['visibility'] = 'public';
        config(['filesystems.disks.public' => $s3Config]);
    }

    protected function ensureStorageLink(): void
    {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');

        if (!file_exists($publicPath) && is_dir($storagePath)) {
            try {
                symlink($storagePath, $publicPath);
            } catch (\Exception $e) {
                \Log::error('Failed to create storage link: ' . $e->getMessage());
            }
        }
    }
}
