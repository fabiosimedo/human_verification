<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController as AccountProfileController;
use App\Http\Controllers\Dashboard\ProfileController as DashboardProfileController;
use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\Dashboard\UserMediaController;
use App\Http\Controllers\Public\PreviewCardController;
use App\Http\Middleware\EnsureProfileIsComplete;

Route::view('/', 'welcome')->name('landing');

Route::get('/p/{slug}', [PreviewCardController::class, 'publicShow'])
    ->name('public.profile.show');

Route::get('/preview-card', [PreviewCardController::class, 'show'])
    ->middleware('auth')
    ->name('preview.card');

Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Perfil da conta (Breeze)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [AccountProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AccountProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AccountProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Perfil público / card
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/profile', [DashboardProfileController::class, 'edit'])->name('dashboard.profile.edit');
    Route::put('/dashboard/profile', [DashboardProfileController::class, 'update'])->name('dashboard.profile.update');

    /*
    |--------------------------------------------------------------------------
    | Dashboard protegido por completude do card
    |--------------------------------------------------------------------------
    */
    Route::middleware(EnsureProfileIsComplete::class)->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.index');
        })->name('dashboard');

        Route::get('/dashboard/links', [LinkController::class, 'index'])->name('dashboard.links.index');
        Route::post('/dashboard/links', [LinkController::class, 'store'])->name('dashboard.links.store');
        Route::patch('/dashboard/links/{link}', [LinkController::class, 'update'])->name('dashboard.links.update');
        Route::patch('/dashboard/links/{link}/toggle', [LinkController::class, 'toggle'])->name('dashboard.links.toggle');
        Route::delete('/dashboard/links/{link}', [LinkController::class, 'destroy'])->name('dashboard.links.destroy');

        Route::get('/dashboard/media', [UserMediaController::class, 'index'])->name('dashboard.media.index');
        Route::post('/dashboard/media', [UserMediaController::class, 'store'])->name('dashboard.media.store');
        Route::post('/dashboard/media/{media}/primary', [UserMediaController::class, 'setPrimary'])->name('dashboard.media.primary');
        Route::delete('/dashboard/media/{media}', [UserMediaController::class, 'destroy'])->name('dashboard.media.destroy');
    });
});

require __DIR__ . '/auth.php';