<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $presensiHariIni = Presensi::where('petugas_id', $user->id)
                                   ->where('tanggal', $today)
                                   ->first();

        $riwayatPresensi = Presensi::where('petugas_id', $user->id)
                                   ->orderBy('tanggal', 'desc')
                                   ->take(10)
                                   ->get();

        $totalHari = 30;
        $hariHadir = Presensi::where('petugas_id', $user->id)
                               ->where('tanggal', '>=', $today->copy()->subDays($totalHari - 1))
                               ->count();

        $statistik = [
            'total_hari' => $totalHari,
            'hadir' => $hariHadir,
            'tidak_hadir' => $totalHari - $hariHadir,
            'persentase' => $totalHari > 0 ? ($hariHadir / $totalHari) * 100 : 0,
        ];

        return view('presensi.index', compact('presensiHariIni', 'riwayatPresensi', 'statistik'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $sudahCheckIn = Presensi::where('petugas_id', $user->id)->where('tanggal', $today)->exists();

        if ($sudahCheckIn) {
            return redirect()->route('presensi.index')->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        Presensi::create([
            'petugas_id' => $user->id,
            'tanggal' => $today,
            'waktu_datang' => now(), // DB akan otomatis mengambil bagian Waktu (time) saja
        ]);

        return redirect()->route('presensi.index')->with('success', 'Berhasil Check In. Selamat bekerja!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $updated = Presensi::where('petugas_id', $user->id)
            ->whereDate('tanggal', $today)
            ->whereNull('waktu_pulang')
            ->update([
                'waktu_pulang' => now()
            ]);

        if ($updated) {
            return redirect()->route('presensi.index')->with('success', 'Berhasil Check Out. Terima kasih!');
        }

        return redirect()->route('presensi.index')->with('error', 'Gagal melakukan check-out.');
    }

}