<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\EmailHealthController;

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

// ─── Public popup view ────────────────────────────────────────────────────────
Route::get('assets/{asset}/popup', [AssetController::class, 'popup'])->name('assets.popup');
Route::get('media/{path}', [MediaController::class, 'show'])->where('path', '.*')->name('media.public');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Assets ───────────────────────────────────────────────────────────────
    Route::post('assets/scan', [AssetController::class, 'scan'])->name('assets.scan');
    Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');
    Route::resource('assets', AssetController::class);
    Route::post('assets/{asset}/checkout', [AssetController::class, 'checkout'])->name('assets.checkout');
    Route::post('assets/{asset}/checkin', [AssetController::class, 'checkin'])->name('assets.checkin');

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
    })->middleware('permission:scanner.access');

    // ─── Categories / Departments ────────────────────────────────────────────
    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);

    // ─── Users (admin only) ──────────────────────────────────────────────────
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
    })->middleware('permission:reports.view');

    // ─── Settings ────────────────────────────────────────────────────────────
    Route::middleware('permission:settings.manage')->group(function () {
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::get('general', [SettingController::class, 'general'])->name('general');
            Route::get('email', [SettingController::class, 'email'])->name('email');
            Route::post('/', [SettingController::class, 'update'])->name('update');
        });
    });

    // ─── Email Logs ──────────────────────────────────────────────────────────
    Route::middleware('permission:settings.manage')->group(function () {
        Route::prefix('email-logs')->name('email-logs.')->group(function () {
            Route::get('/', [EmailLogController::class, 'index'])->name('index');
            Route::get('{emailLog}', [EmailLogController::class, 'show'])->name('show');
            Route::delete('{emailLog}', [EmailLogController::class, 'destroy'])->name('destroy');
            Route::post('clear', [EmailLogController::class, 'clear'])->name('clear');
        });

        Route::prefix('email-health')->name('email-health.')->group(function () {
            Route::get('/', [EmailHealthController::class, 'index'])->name('index');
            Route::post('test', [EmailHealthController::class, 'test'])->name('test');
        });
    });

    // ─── RBAC Management (super admin only) ──────────────────────────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
        Route::resource('permissions', PermissionController::class)->except('show');
    });
});
