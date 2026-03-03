<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\LinkController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Breeze espera estas rotas (navigation.blade.php)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Seu layout "dashboard" (opcional manter, mas eu deixei também)
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('dashboard.profile.edit');
    Route::put('/dashboard/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');

    // Links do vendedor
    Route::get('/dashboard/links', [LinkController::class, 'index'])->name('dashboard.links.index');
    Route::post('/dashboard/links', [LinkController::class, 'store'])->name('dashboard.links.store');
    Route::delete('/dashboard/links/{link}', [LinkController::class, 'destroy'])->name('dashboard.links.destroy');
});

require __DIR__.'/auth.php';
