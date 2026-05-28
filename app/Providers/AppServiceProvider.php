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
}
