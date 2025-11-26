<?php

use App\Http\Controllers\CibestFormController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('cibest', function () {
        return Inertia::render('cibest');
    })->name('cibest');
    Route::post('cibest/upload', 
        [CibestFormController::class, 'uploadCibest']
    )->name('cibest-upload');

    Route::get('baznas', function () {
        return Inertia::render('baznas');
    })->name('baznas');
});

require __DIR__.'/settings.php';
