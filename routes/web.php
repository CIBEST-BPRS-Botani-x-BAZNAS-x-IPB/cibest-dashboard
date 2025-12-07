<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CibestFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportJobController;
use App\Http\Controllers\PovertyStandardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index'])->name('home');

// Pending verification page
Route::get('/verification/pending', function () {
    return Inertia::render('verification/pending');
})->middleware('auth')->name('verification.pending');

Route::middleware(['auth', 'verified', 'admin.verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::controller(CibestFormController::class)->group(function () {
        Route::prefix('bprs')->group(function () {
            Route::get('/', 'cibestIndex')->name('cibest');
            Route::post('upload', 'uploadCibest')->name('cibest-upload');
        });

        Route::prefix('baznas')->group(function () {
            Route::get('/', 'baznasIndex')->name('baznas');
            Route::post('upload', 'uploadBaznas')->name('baznas-upload');
        });
    });
    
    Route::controller(ImportJobController::class)->prefix('import-jobs')->group(function () {
        Route::get('/', 'getImportJobs')->name('import-jobs');
        Route::get('/{importJob}', 'getImportJobDetail')->name('import-jobs-detail');
        Route::delete('/{importJob}', 'deleteImportJob')->name('import-jobs-destroy');
    });

    Route::controller(PovertyStandardController::class)->prefix('poverty-standards')->group(function () {
        Route::get('/', 'index')->name('poverty-standards');
        Route::post('/', 'store')->name('poverty-standards-store');
        Route::put('/{povertyStandard}', 'update')->name('poverty-standards-update');
        Route::delete('/{povertyStandard}', 'destroy')->name('poverty-standards-destroy');
    });

    // Admin routes - requires admin role
    Route::middleware('admin.role')->prefix('admin')->name('admin.')->group(function () {
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{user}', 'show')->name('show');
            Route::post('/{user}/approve', 'approve')->name('approve');
            Route::post('/{user}/reject', 'reject')->name('reject');
        });
    });
});

require __DIR__.'/settings.php';
