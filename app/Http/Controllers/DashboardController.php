<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\BukuTamu;
use App\Models\Pelayanan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalAntrianHariIni = \App\Models\Antrian::whereDate('created_at', today())
            ->whereIn('status', ['menunggu', 'lewati', 'dipanggil', 'sedang_dilayani'])
            ->count();

        //antrian media 
        $data = Pelayanan::whereDate('created_at', today())
            ->get()
            ->groupBy('media_layanan')
            ->map(fn($group) => $group->count()); 

         $layananTetap = [
            'langsung' => 'Pelayanan Langsung',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
        ];

        $mediaLayananHariIni = collect($layananTetap)->map(function ($nama, $key) use ($data) {
            return (object)[
                'mediaLayanan' => $nama,
                'antrian' => $data[$key] ?? 0,
            ];
        });
    
        $sudahDilayani = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'selesai')->count();

        $sisaAntrian = \App\Models\Antrian::whereDate('created_at', today())
            ->where('status', 'pending')->count();

        $antrianBerjalan = \App\Models\Antrian::whereIn('status', ['dipanggil', 'sedang_dilayani'])
            ->whereDate('created_at', today())
            ->first();

        $riwayatAntrian = \App\Models\Antrian::with('jenisLayanan', 'pelayanan')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item){
                return (object)[
                    'id' => $item->id,
                    'nomor_antrian' => $item->nomor_antrian,
                    'nama' => $item->pelayanan->nama_pengunjung ?? '-',
                    'nama_layanan' => $item->jenisLayanan->nama_layanan ?? '-',
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
                    'nomor_antrian' => 'NON-PST',
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
        
        $bulan = $request->query('bulan') ?? now()->month;

        // Ambil jenis layanan
        $filterJenisLayanan = [1, 2, 3];
        $jenisLayananTerpilih = \App\Models\JenisLayanan::whereIn('id', $filterJenisLayanan)
            ->pluck('nama_layanan', 'id');

        // Ambil data pelayanan bulan ini
        $pelayananBulanIni = \App\Models\Pelayanan::with('jenisLayanan')
            ->whereNotNull('jenis_layanan_id')
            ->whereMonth('created_at', $bulan)
            ->whereIn('jenis_layanan_id', $filterJenisLayanan)
            ->get()
            ->groupBy(fn($item) => $item->jenisLayanan->nama_layanan)
            ->map(fn($group) => $group->count());

        // Gabungkan semua jenis layanan supaya yang kosong tetap 0
        $menurutJenisLayanan = $jenisLayananTerpilih->mapWithKeys(function($nama) use ($pelayananBulanIni) {
            return [$nama => $pelayananBulanIni->get($nama, 0)];
        });

        // Ambil data per media_layanan bulan ini
        $mediaLayanan = \App\Models\Pelayanan::whereMonth('created_at', $bulan)
            ->get()
            ->groupBy('media_layanan')
            ->map(fn($group) => $group->count());

        // Hanya ambil 3 kategori
        $pieLayanan = collect([
            'Layanan Langsung' => $mediaLayanan['langsung'] ?? 0,
            'WhatsApp' => $mediaLayanan['whatsapp'] ?? 0,
            'Email' => $mediaLayanan['email'] ?? 0,
        ]);

        // Hitung persentase
        $total = $pieLayanan->sum();
        $pieLayananPersen = $pieLayanan->map(fn($count) => $total ? round($count / $total * 100, 1) : 0);

        return view('dashboard', compact(
            'totalAntrianHariIni',
            'mediaLayananHariIni',
            'menurutJenisLayanan',
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
