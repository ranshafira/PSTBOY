<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\BukuTamu;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAntrianHariIni = \App\Models\Antrian::whereDate('created_at', today())->count();
        $antrianPerLayanan = \App\Models\JenisLayanan::withCount(['antrian' => function($q) {
            $q->whereDate('created_at', today());
        }])->get();

        $sudahDilayani = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'selesai')->count();

        $sisaAntrian = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'pending')->count();

        $antrianBerjalan = \App\Models\Antrian::where('status', 'dipanggil')->first();

        $riwayatAntrian = \App\Models\Antrian::with('jenisLayanan')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item){
                return (object)[
                    'nomor_antrian' => $item->nomor_antrian,
                    'nama' => $item->nama,
                    'nama_layanan' => $item->jenisLayanan->nama_layanan,
                    'status' => $item->status,
                    'waktu' => $item->created_at,
                ];
            });

        $riwayatBukuTamu = \App\Models\BukuTamu::whereDate('waktu_kunjungan', today())
            ->orderBy('waktu_kunjungan', 'desc')
            ->get()
            ->map(function($tamu){
                return (object)[
                    'nomor_antrian' => '-',
                    'nama' => $tamu->nama_tamu,
                    'nama_layanan' => 'Buku Tamu',
                    'status' => 'selesai',
                    'waktu' => $tamu->waktu_kunjungan,
                ];
            });

        $riwayatGabungan = $riwayatAntrian->concat($riwayatBukuTamu)
            ->sortByDesc('waktu');


        $trendHarian = \App\Models\Antrian::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereMonth('created_at', now()->month)
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $bukuTamuCount = BukuTamu::whereDate('waktu_kunjungan', today())->count();

        return view('dashboard', compact(
            'totalAntrianHariIni',
            'antrianPerLayanan',
            'sudahDilayani',
            'sisaAntrian',
            'antrianBerjalan',
            'riwayatAntrian',
            'trendHarian',
            'bukuTamuCount',
            'riwayatGabungan'
        ));
    }
}
