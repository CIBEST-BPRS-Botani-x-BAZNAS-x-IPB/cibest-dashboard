<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CibestFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportJobController;
use App\Http\Controllers\PovertyStandardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/about', function () {
    return Inertia::render('CibestInfo', [
        'description' => 'Dashboard Model CIBEST merupakan instrumen visual dan analitik yang dikembangkan untuk menyajikan pengukuran dampak keuangan syariah secara multidimensional berbasis nilai-nilai Islam. Dashboard ini berangkat dari model CIBEST yang diperkenalkan oleh Beik dan Arsyianti (2015), yang memadukan dimensi kesejahteraan material dan spiritual dalam satu kerangka pengukuran holistik. Melalui pendekatan ini, kondisi rumah tangga dipetakan ke dalam empat kuadran kesejahteraan, yaitu kesejahteraan absolut, kesejahteraan material saja, kesejahteraan spiritual saja, dan kemiskinan absolut.

Secara fungsional, dashboard dirancang untuk memvisualisasikan indeks kesejahteraan material–spiritual secara intuitif, kuantitatif, dan mudah diinterpretasikan oleh para pemangku kepentingan. Data yang ditampilkan memungkinkan pengguna untuk menilai perubahan tingkat kesejahteraan mustahik atau penerima manfaat sebelum dan sesudah intervensi keuangan syariah. Dashboard ini dikembangkan sebagai versi beta yang tidak hanya berfokus pada zakat, tetapi juga disiapkan untuk mengakomodasi perluasan cakupan ke instrumen keuangan sosial syariah lainnya (infak, sedekah, wakaf produktif) serta keuangan syariah komersial seperti pembiayaan mikro, pembiayaan UMKM, dan layanan fintech syariah.

Dari sisi sistem, Dashboard Model CIBEST didukung oleh fondasi arsitektur data yang mengintegrasikan data primer dan sekunder, serta indikator multidimensi yang selaras dengan prinsip maqashid syariah. Dashboard ini menghasilkan keluaran utama berupa visualisasi indeks kesejahteraan material–spiritual dan laporan analisis awal (early validation report) yang dapat digunakan sebagai dasar evaluasi, perbaikan program, dan pengambilan keputusan. Secara strategis, dashboard ini berperan sebagai alat bantu transparansi dan akuntabilitas bagi lembaga zakat dan wakaf, instrumen evaluasi efektivitas pembiayaan bagi lembaga keuangan syariah, serta sumber informasi berbasis bukti bagi pemerintah dan regulator.

Beik, I. S., & Arsyianti, L. D. (2015). Construction of CIBEST model as measurement of poverty and welfare indices from Islamic perspective. Al-Iqtishad: Jurnal Ilmu Ekonomi Syariah, 7(1), 87-104.',
        'provinces' => [],
        'cibestIndexes' => [
            ['no' => 1, 'standard' => 'Miskin Ekstrem', 'index' => 0.00],
            ['no' => 2, 'standard' => 'Garis Kemiskinan', 'index' => 0.00],
            ['no' => 3, 'standard' => 'UMP', 'index' => 0.00],
            ['no' => 4, 'standard' => 'Had Kifayah', 'index' => 0.00],
            ['no' => 5, 'standard' => 'Nishab Zakat', 'index' => 0.00],
        ],
        'povertyStandards' => [
            ['no' => 1, 'standard' => 'Miskin Ekstrem', 'monthlyIncome' => 1657717, 'yearlyIncome' => 19892604],
            ['no' => 2, 'standard' => 'Garis Kemiskinan', 'monthlyIncome' => 2592657, 'yearlyIncome' => 31111884],
            ['no' => 3, 'standard' => 'UMP', 'monthlyIncome' => 2922769, 'yearlyIncome' => 35073228],
            ['no' => 4, 'standard' => 'Had Kifayah', 'monthlyIncome' => 4226553, 'yearlyIncome' => 50718636],
            ['no' => 5, 'standard' => 'Nishab Zakat', 'monthlyIncome' => 6828806, 'yearlyIncome' => 81945672],
        ],
        'povertyIndicators' => [
            ['indicator' => 'Headcount Index (H)', 'before' => 0.39, 'after' => 0.33, 'change' => -0.06],
            ['indicator' => 'Income Gap (I)', 'before' => 0.15, 'after' => 0.11, 'change' => -0.04],
            ['indicator' => 'Poverty Gap (P1)', 'before' => 0.08, 'after' => 0.05, 'change' => -0.03],
            ['indicator' => 'Index Sen (P2)', 'before' => 0.37, 'after' => 0.19, 'change' => -0.18],
            ['indicator' => 'Index FGT (P3)', 'before' => 0.12, 'after' => 0.05, 'change' => -0.07],
        ],
    ]);
})->name('about');

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
