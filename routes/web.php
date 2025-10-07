<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardKepalaController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\SurveySkdController;
use App\Http\Controllers\SurveyInternalController;

// == Route untuk Halaman Publik (Sistem Antrian) ==
Route::get('/', [AntrianController::class, 'index'])->name('antrian.index');
Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');

// == Route Buku Tamu (Publik) ==
Route::get('/buku-tamu', [BukuTamuController::class, 'create'])->name('bukutamu.create');
Route::post('/buku-tamu', [BukuTamuController::class, 'store'])->name('bukutamu.store');

Route::get('/antrian/bukutamu-pst', [BukuTamuController::class, 'createPST'])->name('bukutamu.pst');
Route::get('/antrian/bukutamu-nonpst', [BukuTamuController::class, 'createNonPST'])->name('bukutamu.nonpst');

// == Route untuk Riwayat (Bisa tanpa login) ==
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
Route::get('/riwayat/export', [RiwayatController::class, 'exportCsv'])->name('riwayat.export');

// == Route untuk Otentikasi (login, register, logout, dll) ==
require __DIR__ . '/auth.php';

// == Route yang Memerlukan Login ==
// Route umum untuk semua user yang login
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/check-out', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
});

// Route khusus untuk petugas PST yang harus login dan memiliki role petugas
Route::middleware(['auth', 'isPetugas'])->group(function () {
    // Presensi
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/check-in', [PresensiController::class, 'checkIn'])->name('presensi.checkin');
    Route::post('/presensi/check-out', [PresensiController::class, 'checkOut'])->name('presensi.checkout');

    // Antrian khusus petugas
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::post('/antrian/{id}/batal', [AntrianController::class, 'batal'])->name('antrian.batal');

    // Proses Pelayanan Petugas
    Route::get('/pelayanan', [PelayananController::class, 'index'])->name('pelayanan.index');

    // Langkah 1: Input data pengunjung
    Route::get('/pelayanan/mulai/{antrian_id}', [PelayananController::class, 'createStep1'])->name('pelayanan.langkah1.create');
    Route::post('/pelayanan/mulai', [PelayananController::class, 'storeStep1'])->name('pelayanan.langkah1.store');

    // Langkah 2: Input hasil pelayanan
    Route::get('/pelayanan/{pelayanan}/hasil', [PelayananController::class, 'createStep2'])->name('pelayanan.langkah2.create');
    Route::post('/pelayanan/{pelayanan}/hasil', [PelayananController::class, 'storeStep2'])->name('pelayanan.langkah2.store');

    // Halaman Terima Kasih (setelah selesai pelayanan)
    Route::get('/pelayanan/{pelayanan}/terimakasih', [PelayananController::class, 'terimakasih'])->name('pelayanan.terimakasih');

    // Fitur Lanjutan & Detail
    Route::get('/pelayanan/{id}/lanjut', [PelayananController::class, 'lanjutkan'])->name('pelayanan.lanjut');
    Route::get('/pelayanan/{id}/detail', [PelayananController::class, 'detail'])->name('pelayanan.detail');
    
    // Edit data pengunjung (langkah 1)
    Route::get('/pelayanan/{pelayanan}/edit-step1', [PelayananController::class, 'editStep1'])->name('pelayanan.langkah1.edit');
    Route::put('/pelayanan/{pelayanan}/update-step1', [PelayananController::class, 'updateStep1'])->name('pelayanan.langkah1.update');

    // Edit hasil pelayanan (langkah 2)
    Route::get('/pelayanan/{pelayanan}/edit-step2', [PelayananController::class, 'editStep2'])->name('pelayanan.langkah2.edit');
    Route::put('/pelayanan/{pelayanan}/update-step2', [PelayananController::class, 'updateStep2'])->name('pelayanan.langkah2.update');

    // Dashboard Umum - arahkan berdasarkan role
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }
        // Jika role lain atau default:
        return redirect('/');
    })->name('dashboard');
});

// == Route Dashboard berdasarkan Role ==

// Route khusus admin yang memerlukan login dan role admin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/export-survei', [DashboardAdminController::class, 'exportSurvei'])->name('admin.dashboard.exportSurvei');

    // Halaman admin untuk daftar user petugas PST
    Route::get('/petugas', [UserManagementController::class, 'index'])->name('admin.petugas.index');
    Route::delete('/petugas/{id}', [UserManagementController::class, 'destroy'])->name('admin.petugas.destroy');

    // Register user baru khusus admin
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('admin.register.store');

    // Jadwal routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/jadwal/generate', [JadwalController::class, 'generateForm'])->name('admin.jadwal.generate.form');
    Route::post('/jadwal/generate', [JadwalController::class, 'generateJadwal'])->name('admin.jadwal.generate');

    // CRUD operations for individual jadwal
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');

    // Export CSV jadwal
    Route::get('/jadwal/export-csv', [JadwalController::class, 'exportCsv'])->name('admin.jadwal.export.csv');

    // API endpoint for calendar events
    Route::get('/jadwal/events', [JadwalController::class, 'getEvents'])->name('admin.jadwal.events');
});

// Dashboard Petugas PST (hanya perlu login dan role petugas)
Route::middleware(['auth', 'isPetugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');
    Route::get('/jadwal', [JadwalController::class, 'indexPetugas'])->name('petugas.jadwal.index');
    Route::get('/jadwal/events', [JadwalController::class, 'eventsPetugas'])->name('petugas.jadwal.events');
});

// Dashboard Kepala (middleware khusus kepala)
Route::get('/dashboardKepala', [DashboardKepalaController::class, 'index'])
    ->middleware('isKepala')
    ->name('dashboard.kepala');

// Rute Survei Internal - bagian dari alur pelayanan
Route::get('/survei-internal/{id}', [SurveyInternalController::class, 'show'])->name('survei.internal.show');
Route::post('/survei-internal/{id}', [SurveyInternalController::class, 'store'])->name('survei.internal.store');
