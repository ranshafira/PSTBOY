<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\BukuTamu;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalAntrianHariIni = \App\Models\Antrian::whereDate('created_at', today())
            ->whereIn('status', ['menunggu', 'lewati', 'dipanggil', 'sedang_dilayani'])
            ->count();

        $antrianPerLayanan = \App\Models\JenisLayanan::withCount(['antrian' => function($q) {
            $q->whereDate('created_at', today());
        }])->get();

        $sudahDilayani = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'selesai')->count();

        $sisaAntrian = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'pending')->count();

        $antrianBerjalan = \App\Models\Antrian::whereIn('status', ['dipanggil', 'sedang_dilayani'])->first();

        $riwayatAntrian = \App\Models\Antrian::with('jenisLayanan')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item){
                return (object)[
                    'id' => $item->id,
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
                    'id' => $tamu->id,
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
        
        // pie chart
        $bulan = $request->query('bulan') ?? now()->month;

        // Ambil semua layanan
        $layananSemua = \App\Models\JenisLayanan::all();

        // Ambil jumlah antrian per layanan bulan ini
        $antrianPerLayananBulan = \App\Models\Antrian::whereMonth('created_at', $bulan)
            ->get()
            ->groupBy(fn($item) => $item->jenisLayanan->nama_layanan ?? 'Layanan Lain')
            ->map(fn($group) => $group->count());

        // Siapkan array final untuk pie chart
        $pieLayanan = $layananSemua->pluck('nama_layanan')->mapWithKeys(function($nama) use ($antrianPerLayananBulan) {
            return [$nama => $antrianPerLayananBulan[$nama] ?? 0];
        });

        // Tambahkan Buku Tamu
        $bukuTamuCountBulan = \App\Models\BukuTamu::whereMonth('waktu_kunjungan', $bulan)->count();
        $pieLayanan->put('Buku Tamu', $bukuTamuCountBulan);

        // Hitung persentase
        $total = $pieLayanan->sum();
        $pieLayananPersen = $pieLayanan->map(fn($count) => $total ? round($count / $total * 100, 1) : 0);


        return view('dashboard', compact(
            'totalAntrianHariIni',
            'antrianPerLayanan',
            'sudahDilayani',
            'sisaAntrian',
            'antrianBerjalan',
            'riwayatAntrian',
            'trendHarian',
            'bukuTamuCount',
            'riwayatGabungan',
            'pieLayananPersen',
        ));
    }
}
