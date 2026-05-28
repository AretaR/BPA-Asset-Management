<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// ─── Public QR Code View (no auth required) ───────────────────────────────────
Route::get('qr/{uuid}', [QRCodeController::class, 'publicView'])->name('assets.qr-public')->middleware('throttle:15,1');

// ─── Legacy / unprotected barcode lookup (kept for backward compatibility) ────
Route::get('assets/barcode/{code}', [AssetController::class, 'barcode'])->name('assets.barcode');
Route::get('assets/{asset}/popup', [AssetController::class, 'popup'])->name('assets.popup');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Assets ───────────────────────────────────────────────────────────────
    Route::post('assets/scan', [AssetController::class, 'scan'])->name('assets.scan');
    Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');
    Route::resource('assets', AssetController::class);
    Route::post('assets/{asset}/checkout', [AssetController::class, 'checkout'])->name('assets.checkout');
    Route::post('assets/{asset}/checkin', [AssetController::class, 'checkin'])->name('assets.checkin');

    // ─── Barcodes ─────────────────────────────────────────────────────────────
    Route::prefix('barcodes')->name('barcodes.')->group(function () {
        Route::get('{asset}/show',       [BarcodeController::class, 'show'])      ->name('show');
        Route::get('{asset}/download',   [BarcodeController::class, 'download'])  ->name('download');
        Route::get('{asset}/svg',        [BarcodeController::class, 'svg'])       ->name('svg');
        Route::get('{asset}/print',      [BarcodeController::class, 'print'])     ->name('print');
        Route::post('{asset}/regenerate',[BarcodeController::class, 'regenerate'])->name('regenerate');
    });

    // ─── QR Codes ─────────────────────────────────────────────────────────────
    Route::prefix('qrcodes')->name('qrcodes.')->group(function () {
        Route::get('{asset}/download',   [QRCodeController::class, 'download'])  ->name('download');
        Route::get('{asset}/image',      [QRCodeController::class, 'image'])     ->name('image');
        Route::get('{asset}/print',      [QRCodeController::class, 'print'])     ->name('print');
        Route::post('{asset}/regenerate',[QRCodeController::class, 'regenerate'])->name('regenerate');
    });

    // ─── Scanner ──────────────────────────────────────────────────────────────
    Route::prefix('scanner')->name('scanner.')->group(function () {
        Route::get('/',        [ScannerController::class, 'index'])  ->name('index');
        Route::get('history',  [ScannerController::class, 'history'])->name('history');
        Route::post('lookup',  [ScannerController::class, 'lookup']) ->name('lookup');
        Route::get('search',   [ScannerController::class, 'search']) ->name('search');
    });

    // ─── Categories / Departments / Users ─────────────────────────────────────
    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');

    // ─── Reports ──────────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('assets-by-department', [ReportController::class, 'assetsByDepartment'])->name('assets_by_department');
        Route::get('assets-by-status', [ReportController::class, 'assetsByStatus'])->name('assets_by_status');
        Route::get('assets-value', [ReportController::class, 'assetsValue'])->name('assets_value');
        Route::get('activity-logs', [ReportController::class, 'activityLogs'])->name('activity_logs');
    });

    // ─── Settings ─────────────────────────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('general', [SettingController::class, 'general'])->name('general');
        Route::get('email', [SettingController::class, 'email'])->name('email');
        Route::post('/', [SettingController::class, 'update'])->name('update');
    });
});
