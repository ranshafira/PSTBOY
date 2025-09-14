<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\JadwalController;


// == Route untuk Halaman Publik (Sistem Antrian) ==
Route::get('/', [AntrianController::class, 'index'])->name('antrian.index');
Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');

// == Route Buku Tamu (Publik) ==
Route::get('/buku-tamu', [BukuTamuController::class, 'create'])->name('bukutamu.create');
Route::post('/buku-tamu', [BukuTamuController::class, 'store'])->name('bukutamu.store');

// == Route untuk Riwayat (Bisa tanpa login) ==
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
Route::get('/riwayat/export', [RiwayatController::class, 'exportCsv'])->name('riwayat.export');

// == Route untuk Otentikasi (login, register, logout, dll) ==
require __DIR__.'/auth.php';

// == Route yang Memerlukan Login ==
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

    // Presensi
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/check-in', [PresensiController::class, 'checkIn'])->name('presensi.checkin');
    Route::post('/presensi/check-out', [PresensiController::class, 'checkOut'])->name('presensi.checkout');

    // Antrian
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::post('/antrian/{id}/batal', [AntrianController::class, 'batal'])->name('antrian.batal');

    // Pelayanan
    Route::get('/pelayanan', [\App\Http\Controllers\PelayananController::class, 'index'])->name('pelayanan.index');
    Route::get('/pelayanan/{id}', [\App\Http\Controllers\PelayananController::class, 'show'])->name('pelayanan.show');
    Route::post('/pelayanan/{id}/mulai', [\App\Http\Controllers\PelayananController::class, 'start'])->name('pelayanan.start');
    Route::get('/pelayanan/{id}/detail', [\App\Http\Controllers\PelayananController::class, 'detail'])->name('pelayanan.detail');
    Route::get('/pelayanan/{id}/lanjut', [PelayananController::class, 'lanjutkan'])->name('pelayanan.lanjut');
    Route::post('/pelayanan/{id}/start', [\App\Http\Controllers\PelayananController::class, 'storeStart'])
    ->name('pelayanan.storeStart');
 
    // Identitas dalam Pelayanan
    Route::get('/pelayanan/{id}/identitas', [\App\Http\Controllers\PelayananController::class, 'identitas'])->name('pelayanan.identitas');
    Route::post('/pelayanan/{id}/identitas', [\App\Http\Controllers\PelayananController::class, 'storeIdentitas'])->name('pelayanan.storeIdentitas');
    // Hasil pelayanan
    Route::get('/pelayanan/{id}/hasil', [PelayananController::class, 'hasil'])->name('pelayanan.hasil');
    Route::post('/pelayanan/{id}/hasil', [PelayananController::class, 'storeHasil'])->name('pelayanan.storeHasil');

    // Menampilkan halaman Selesai Pelayanan
    Route::get('/pelayanan/{id}/selesai', [PelayananController::class, 'selesai'])->name('pelayanan.selesai');
    // Mencatat waktu selesai saat tombol ditekan
    Route::post('/pelayanan/{id}/selesaikan', [PelayananController::class, 'finish'])->name('pelayanan.finish');

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
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Halaman admin untuk daftar user petugas PST
    Route::get('/admin/petugas', [UserManagementController::class, 'index'])->name('admin.petugas.index');
    Route::delete('/admin/petugas/{id}', [UserManagementController::class, 'destroy'])->name('admin.petugas.destroy');

    // Eksport survei
    Route::get('/admin/dashboard/export-survei', [DashboardAdminController::class, 'exportSurvei'])->name('admin.dashboard.exportSurvei');

    // Register user baru khusus admin
    Route::get('/admin/register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('/admin/register', [RegisteredUserController::class, 'store'])->name('admin.register.store');

    // Jadwal routes - Updated with proper middleware and structure
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    // Main jadwal routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/jadwal/generate', [JadwalController::class, 'generateForm'])->name('admin.jadwal.generate.form');
    Route::post('/jadwal/generate', [JadwalController::class, 'generateJadwal'])->name('admin.jadwal.generate');
    
    // CRUD operations for individual jadwal
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    
    // API endpoint for calendar events
    Route::get('/jadwal/events', [JadwalController::class, 'getEvents'])->name('admin.jadwal.events');
});



}); // <== Ini penutup untuk group middleware(['auth', 'isAdmin'])

// Dashboard Petugas PST (hanya perlu login)
Route::middleware(['auth'])->group(function () {
    Route::get('/petugas/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');
});
// ROUTE BARU UNTUK SURVEI (Tidak perlu login)
Route::get('/survei', [SurveyController::class, 'entry'])->name('survei.entry');
Route::post('/survei/cari', [SurveyController::class, 'find'])->name('survei.find');
Route::get('/survei/{token}', [SurveyController::class, 'show'])->name('survei.show');
Route::post('/survei/{token}', [SurveyController::class, 'store'])->name('survei.store');
