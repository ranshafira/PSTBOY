<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\BukuTamu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    /**
     * Display the queue screen
     */
    public function display()
    {
        $today = Carbon::today();

        // Ambil antrian aktif untuk HARI INI
        $antrianAktif = Antrian::with(['jenisLayanan', 'pelayanan'])
            ->whereDate('created_at', $today)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->latest('updated_at')
            ->first();

        // Ambil 5 antrian berikutnya
        $antrianBerikutnya = Antrian::with('jenisLayanan')
            ->whereDate('created_at', $today)
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        // Statistik harian
        $totalAntrianHariIni = Antrian::whereDate('created_at', $today)->count();
        $sudahDilayani = Antrian::whereDate('created_at', $today)
            ->where('status', 'selesai')->count();
        $sedangAntri = Antrian::whereDate('created_at', $today)
            ->whereIn('status', ['menunggu', 'dipanggil'])->count();
        $bukuTamuCount = BukuTamu::whereDate('waktu_kunjungan', $today)->count();

        // Breakdown layanan
        $layananBreakdown = JenisLayanan::leftJoin('antrian', function ($join) use ($today) {
            $join->on('jenis_layanan.id', '=', 'antrian.jenis_layanan_id')
                ->whereDate('antrian.created_at', $today)
                ->whereIn('antrian.status', ['menunggu', 'dipanggil', 'sedang_dilayani']);
        })
            ->select(
                'jenis_layanan.nama_layanan',
                'jenis_layanan.kode_antrian',
                DB::raw('COUNT(antrian.id) as total')
            )
            ->groupBy('jenis_layanan.id', 'jenis_layanan.nama_layanan', 'jenis_layanan.kode_antrian')
            ->get();

        // Semua antrian hari ini
        $allQueues = Antrian::with('jenisLayanan')
            ->whereDate('created_at', $today)
            ->whereIn('status', ['menunggu', 'dipanggil', 'sedang_dilayani'])
            ->orderBy('created_at')
            ->get();

        return view('display.antrian', compact(
            'antrianAktif',
            'antrianBerikutnya',
            'totalAntrianHariIni',
            'sudahDilayani',
            'sedangAntri',
            'bukuTamuCount',
            'layananBreakdown',
            'allQueues'
        ));
    }

    /**
     * API endpoint for real-time updates
     */
    public function apiData()
    {
        $today = Carbon::today();

        $antrianAktif = Antrian::with(['jenisLayanan', 'pelayanan'])
            ->whereDate('created_at', $today)
            ->whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->latest('updated_at')
            ->first();

        $antrianBerikutnya = Antrian::with('jenisLayanan')
            ->whereDate('created_at', $today)
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        $totalAntrianHariIni = Antrian::whereDate('created_at', $today)->count();
        $sudahDilayani = Antrian::whereDate('created_at', $today)
            ->where('status', 'selesai')->count();
        $sedangAntri = Antrian::whereDate('created_at', $today)
            ->whereIn('status', ['menunggu', 'dipanggil'])->count();
        $bukuTamuCount = BukuTamu::whereDate('waktu_kunjungan', $today)->count();

        $layananBreakdown = JenisLayanan::leftJoin('antrian', function ($join) use ($today) {
            $join->on('jenis_layanan.id', '=', 'antrian.jenis_layanan_id')
                ->whereDate('antrian.created_at', $today)
                ->whereIn('antrian.status', ['menunggu', 'dipanggil', 'sedang_dilayani']);
        })
            ->select(
                'jenis_layanan.nama_layanan',
                'jenis_layanan.kode_antrian',
                DB::raw('COUNT(antrian.id) as total')
            )
            ->groupBy('jenis_layanan.id', 'jenis_layanan.nama_layanan', 'jenis_layanan.kode_antrian')
            ->get();

        return response()->json([
            'antrianAktif' => $antrianAktif,
            'antrianBerikutnya' => $antrianBerikutnya,
            'statistik' => [
                'totalAntrianHariIni' => $totalAntrianHariIni,
                'sudahDilayani' => $sudahDilayani,
                'sedangAntri' => $sedangAntri,
                'bukuTamuCount' => $bukuTamuCount
            ],
            'layananBreakdown' => $layananBreakdown,
            'timestamp' => now()
        ]);
    }

    /**
     * Fullscreen display mode
     */
    public function fullscreen()
    {
        return $this->display();
    }
}
