<?php

use Illuminate\Support\Facades\Route;
use App\Models\JenisLayanan; // PENTING: Untuk memperbaiki error di dashboard
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;

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

// == Route untuk Halaman Publik (Sistem Antrian - dari Anda) ==
Route::get('/', [AntrianController::class, 'index'])->name('antrian.index');
Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');


// == Route untuk Halaman yang Membutuhkan Login (dari Teman Anda) ==

// Dashboard (Sudah diperbaiki untuk mengirim data $jenisLayanan)
Route::get('/dashboard', function () {
    $jenisLayanan = JenisLayanan::all();
    return view('dashboard', compact('jenisLayanan'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup route yang memerlukan login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/check-out', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
});

// Route untuk otentikasi (login, register, dll)
require __DIR__.'/auth.php';