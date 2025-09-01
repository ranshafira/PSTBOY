<?php

use Illuminate\Support\Facades\Route;
use App\Models\JenisLayanan; 
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;

// == Route untuk Halaman Publik (Sistem Antrian - dari Anda) ==
Route::get('/', [AntrianController::class, 'index'])->name('antrian.index');
Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');


// == Route untuk Halaman yang Membutuhkan Login (dari Teman Anda) ==

// Dashboard (Sudah diperbaiki untuk mengirim data $jenisLayanan)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Grup route yang memerlukan login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/check-out', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
});


Route::post('/antrian/{nomor}/panggil', [\App\Http\Controllers\AntrianController::class, 'panggil'])
    ->middleware(['auth'])
    ->name('antrian.panggil');
    
Route::post('/antrian/{nomor}/batal', [AntrianController::class, 'batal'])->name('antrian.batal');

// Route Pelayanan
Route::middleware('auth')->group(function () {
    Route::get('/pelayanan/{nomor}', [\App\Http\Controllers\PelayananController::class, 'show'])
        ->name('pelayanan.show');

    Route::post('/pelayanan/{nomor}/mulai', [\App\Http\Controllers\PelayananController::class, 'start'])
        ->name('pelayanan.start');

    Route::get('/pelayanan/{id}/detail', [\App\Http\Controllers\PelayananController::class, 'detail'])
        ->name('pelayanan.detail');

// identitas
    Route::get('/pelayanan/{id}/identitas', [\App\Http\Controllers\PelayananController::class, 'identitas'])
        ->name('pelayanan.identitas');

    Route::post('/pelayanan/{id}/identitas', [\App\Http\Controllers\PelayananController::class, 'storeIdentitas'])
        ->name('pelayanan.storeIdentitas');

});




// == Route untuk Buku Tamu ==
Route::get('/buku-tamu', [BukuTamuController::class, 'create'])->name('bukutamu.create');
Route::post('/buku-tamu', [BukuTamuController::class, 'store'])->name('bukutamu.store');

Route::middleware('auth')->group(function () {
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/check-in', [PresensiController::class, 'checkIn'])->name('presensi.checkin');
    Route::post('/presensi/check-out', [PresensiController::class, 'checkOut'])->name('presensi.checkout');
});

Route::get('/riwayat', function () {
    return view('riwayat.index');
})->name('riwayat.index');

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // form
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password'); // form
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update'); // submit form
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword'); // submit form
});

// Route untuk otentikasi (login, register, dll)
require __DIR__.'/auth.php';


