<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Data untuk card "Status Hari Ini"
        $presensiHariIni = Presensi::where('petugas_id', $user->id)
                                  ->whereDate('tanggal', $today)
                                  ->first();

        // 2. Data untuk card "Riwayat Absensi"
        $riwayatAbsensi = Presensi::where('petugas_id', $user->id)
                                  ->orderBy('tanggal', 'desc')
                                  ->take(10)
                                  ->get();
        
        // 3. Data untuk "Statistik Kehadiran" (30 hari terakhir)
        $totalHariKerja = 30; 
        $jumlahHadir = Presensi::where('petugas_id', $user->id)
                               ->where('tanggal', '>=', Carbon::now()->subDays(30))
                               ->count();
        $tingkatKehadiran = ($totalHariKerja > 0) ? ($jumlahHadir / $totalHariKerja) * 100 : 0;

        $statistik = [
            'tingkat_kehadiran' => round($tingkatKehadiran),
            'hari_hadir' => $jumlahHadir,
            'total_hari' => $totalHariKerja,
            'tidak_hadir' => $totalHariKerja - $jumlahHadir,
        ];

        return view('absensi.index', [
            'presensiHariIni' => $presensiHariIni,
            'riwayatAbsensi' => $riwayatAbsensi,
            'statistik' => $statistik,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah sudah check-in hari ini
        $sudahCheckIn = Presensi::where('petugas_id', $user->id)
                                ->whereDate('tanggal', $today)->exists();

        if ($sudahCheckIn) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Simpan data check-in
        Presensi::create([
            'petugas_id' => $user->id,
            'tanggal' => $today,
            'waktu_datang' => Carbon::now(),
        ]);

        return back()->with('success', 'Berhasil check-in.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Cari data presensi hari ini
        $presensiHariIni = Presensi::where('petugas_id', $user->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        if (!$presensiHariIni || $presensiHariIni->waktu_pulang) {
            return back()->with('error', 'Aksi tidak valid.');
        }

        // Update data check-out
        $presensiHariIni->update([
            'waktu_pulang' => Carbon::now()
        ]);

        return back()->with('success', 'Berhasil check-out.');
    }
}