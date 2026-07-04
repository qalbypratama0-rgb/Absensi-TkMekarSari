<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Public UI for Kiosk Scanner
Route::view('/scanner', 'scanner')->name('scanner');

// Admin Panel Routes (Protected by Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kelas', KelasController::class)->except(['create', 'edit', 'show']);
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', SiswaController::class)->except(['create', 'edit', 'show']);

    Route::get('/generate-qr', [QrController::class, 'generateQrIndex'])->name('generate-qr.index');
    Route::get('/generate-qr/download/{siswa}', [QrController::class, 'downloadQr'])->name('generate-qr.download');
    Route::get('/cetak-lanyard', [QrController::class, 'cetakLanyardIndex'])->name('cetak-lanyard.index');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::patch('/laporan/update-status/{absensi}', [LaporanController::class, 'updateStatus'])->name('laporan.updateStatus');

    Route::get('/input-manual', function () {
        return view('absensi.manual');
    })->name('absensi.manual');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
