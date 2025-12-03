<?php

use App\Http\Controllers\CibestFormController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::controller(CibestFormController::class)->group(function () {
        Route::prefix('cibest')->group(function () {
            Route::get('/', 'cibestIndex')->name('cibest');
            Route::post('upload', 'uploadCibest')->name('cibest-upload');
        });

        Route::prefix('baznas')->group(function () {
            Route::get('/', 'baznasIndex')->name('baznas');
            Route::post('upload', 'uploadBaznas')->name('baznas-upload');
        });
    });

    

});

require __DIR__.'/settings.php';
