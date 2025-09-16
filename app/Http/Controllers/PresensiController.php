<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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

        // Hitung total hari kerja dari awal bulan sampai hari ini (Senin - Jumat)
        $startOfMonth = $today->copy()->startOfMonth();

        $period = CarbonPeriod::create($startOfMonth, $today);

        $totalHari = 0;
        foreach ($period as $date) {
            if (in_array($date->dayOfWeek, [Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY, Carbon::FRIDAY])) {
                $totalHari++;
            }
        }

        $hariHadir = Presensi::where('petugas_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth, $today])
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

    // ✅ Cek apakah petugas dijadwalkan hari ini
    $terjadwalHariIni = \App\Models\Jadwal::where('user_id', $user->id)
        ->where('tanggal', $today)
        ->exists();

    if (!$terjadwalHariIni) {
        return redirect()->route('presensi.index')->with('error', 'Anda tidak dijadwalkan hari ini. Presensi tidak diperbolehkan.');
    }

    // Cek apakah sudah presensi hari ini
    $sudahCheckIn = Presensi::where('petugas_id', $user->id)->where('tanggal', $today)->exists();

    if ($sudahCheckIn) {
        return redirect()->route('presensi.index')->with('error', 'Anda sudah melakukan check-in hari ini.');
    }

    // Lakukan check-in
    Presensi::create([
        'petugas_id' => $user->id,
        'tanggal' => $today,
        'waktu_datang' => now(),
    ]);

    return redirect()->route('presensi.index')->with('success', 'Berhasil Check In. Selamat bekerja!');
}

public function checkOut(Request $request)
{
    $user = Auth::user();
    $today = Carbon::today();

    // ✅ Cek apakah petugas dijadwalkan hari ini
    $terjadwalHariIni = \App\Models\Jadwal::where('user_id', $user->id)
        ->where('tanggal', $today)
        ->exists();

    if (!$terjadwalHariIni) {
        return redirect()->route('presensi.index')->with('error', 'Anda tidak dijadwalkan hari ini. Presensi tidak diperbolehkan.');
    }

    // Update presensi jika belum check-out
    $updated = Presensi::where('petugas_id', $user->id)
        ->whereDate('tanggal', $today)
        ->whereNull('waktu_pulang')
        ->update([
            'waktu_pulang' => now()
        ]);

    if ($updated) {
        return redirect()->route('presensi.index')->with('success', 'Berhasil Check Out. Terima kasih!');
    }

    return redirect()->route('presensi.index')->with('error', 'Gagal melakukan check-out atau Anda belum check-in.');
}
}
