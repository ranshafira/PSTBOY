<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Jadwal;
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

        // Hitung total hari kerja (Senin - Jumat) dari awal bulan
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
    $now = Carbon::now();

    $currentShift = null;

    // Tentukan shift berdasarkan waktu sekarang
    if ($now->between(Carbon::today()->setTime(7, 30), Carbon::today()->setTime(8, 30))) {
        $currentShift = 'pagi';
    } elseif ($now->between(Carbon::today()->setTime(11, 0), Carbon::today()->setTime(12, 0))) {
        $currentShift = 'siang';
    }

    if ($currentShift === null) {
        return redirect()->route('presensi.index')->with('error', 'Check-in hanya dapat dilakukan pada jam shift yang ditentukan.');
    }

    // Cek apakah user terjadwal untuk shift ini hari ini
    $jadwalHariIni = Jadwal::where('user_id', $user->id)
                           ->where('tanggal', $today)
                           ->where('shift', $currentShift)
                           ->first();

    if (!$jadwalHariIni) {
        return redirect()->route('presensi.index')->with('error', 'Anda tidak terjadwal untuk shift ' . ucfirst($currentShift) . ' hari ini.');
    }

    // Cek apakah sudah check-in untuk shift ini
    $sudahCheckIn = Presensi::where('petugas_id', $user->id)
                            ->where('tanggal', $today)
                            ->where('shift', $currentShift)
                            ->exists();

    if ($sudahCheckIn) {
        return redirect()->route('presensi.index')->with('error', 'Anda sudah melakukan check-in untuk shift ini hari ini.');
    }

    Presensi::create([
        'petugas_id' => $user->id,
        'tanggal' => $today,
        'waktu_datang' => now(),
        'shift' => $currentShift,
    ]);

    return redirect()->route('presensi.index')->with('success', 'Berhasil Check In untuk shift ' . ucfirst($currentShift) . '. Selamat bekerja!');
}


    public function checkOut(Request $request)
{
    $user = Auth::user();
    $today = Carbon::today();
    $now = Carbon::now();

    // Ambil presensi yang belum check-out
    $presensi = Presensi::where('petugas_id', $user->id)
                        ->where('tanggal', $today)
                        ->whereNull('waktu_pulang')
                        ->first();

    if (!$presensi) {
        return redirect()->route('presensi.index')->with('error', 'Anda belum check-in atau sudah check-out.');
    }

    // Validasi waktu check-out sesuai shift
    if ($presensi->shift === 'pagi') {
        if (!$now->between(Carbon::today()->setTime(11, 0), Carbon::today()->setTime(12, 0))) {
            return redirect()->route('presensi.index')->with('error', 'Waktu check-out untuk shift pagi adalah antara 11:00 - 12:00.');
        }
    } elseif ($presensi->shift === 'siang') {
        if (!$now->between(Carbon::today()->setTime(15, 0), Carbon::today()->setTime(16, 0))) {
            return redirect()->route('presensi.index')->with('error', 'Waktu check-out untuk shift siang adalah antara 15:00 - 16:00.');
        }
    }

    $presensi->waktu_pulang = now();
    $presensi->save();

    return redirect()->route('presensi.index')->with('success', 'Berhasil Check Out untuk shift ' . ucfirst($presensi->shift) . '. Terima kasih!');
}

}
