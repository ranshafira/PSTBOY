<?php
namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\BukuTamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->tab ?? 'pelayanan';

        if ($tab == 'pelayanan') {
            $riwayat = Pelayanan::with(['petugas', 'jenisLayanan', 'antrian', 'surveyKepuasan'])
                ->when($request->q, function($query, $q) {
                    $query->where('nama_pengunjung', 'like', "%{$q}%")
                          ->orWhere('no_hp', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                          ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"));
                })
                ->when($request->status, function($query, $status) {
                    if($status == 'Proses') {
                        $query->whereNull('status_penyelesaian');
                    } else {
                        $query->where('status_penyelesaian', $status);
                    }
                })
                ->when($request->jenis_layanan, fn($query, $id) => $query->where('jenis_layanan_id', $id))
                ->when($request->periode, function($query, $periode) {
                    if($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
                    elseif($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
                })
                ->orderBy('waktu_mulai_sesi', 'desc')
                ->paginate(10);
        } else { 
            $riwayat = BukuTamu::when($request->q, fn($q1) => 
                            $q1->where('nama_tamu', 'like', "%{$request->q}%")
                               ->orWhere('instansi_tamu', 'like', "%{$request->q}%")
                               ->orWhere('kontak_tamu', 'like', "%{$request->q}%")
                               ->orWhere('keperluan', 'like', "%{$request->q}%"))
                        ->when($request->periode, function($query, $periode) {
                            if($periode == 'hari_ini') $query->whereDate('waktu_kunjungan', today());
                            elseif($periode == 'minggu_ini') $query->whereBetween('waktu_kunjungan', [now()->startOfWeek(), now()->endOfWeek()]);
                            elseif($periode == 'bulan_ini') $query->whereMonth('waktu_kunjungan', now()->month);
                        })
                        ->orderBy('waktu_kunjungan', 'desc')
                        ->paginate(10);
        }

        return view('riwayat.index', compact('riwayat', 'tab'));
    }

    public function exportCsv(Request $request)
    {
        $tab = $request->tab ?? 'pelayanan';

        if ($tab == 'pelayanan') {
            $riwayat = Pelayanan::with(['petugas','jenisLayanan','antrian','surveyKepuasan'])
                ->when($request->q, function($query, $q) {
                    $query->where('nama_pengunjung', 'like', "%{$q}%")
                          ->orWhere('no_hp', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                          ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"));
                })
                ->when($request->status, function($query, $status) {
                    if($status == 'Proses') {
                        $query->whereNull('status_penyelesaian');
                    } else {
                        $query->where('status_penyelesaian', $status);
                    }
                })
                ->when($request->jenis_layanan, fn($query, $id) => $query->where('jenis_layanan_id', $id))
                ->when($request->periode, function($query, $periode) {
                    if($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
                    elseif($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
                })
                ->orderBy('waktu_mulai_sesi', 'desc')
                ->get(); 

            $columns = [
                'No. Antrian', 'Nama Pengunjung', 'Instansi', 'No. HP', 'Email',
                'Jenis Kelamin', 'Pendidikan',
                'Jenis Layanan', 'Tanggal', 'Durasi', 'Status', 'Token Survei','Kepuasan'
            ];
            $filename = 'riwayat_pelayanan.csv';

        } else { 
            $riwayat = BukuTamu::when($request->q, fn($q1) => 
                            $q1->where('nama_tamu', 'like', "%{$request->q}%")
                               ->orWhere('instansi_tamu', 'like', "%{$request->q}%")
                               ->orWhere('kontak_tamu', 'like', "%{$request->q}%")
                               ->orWhere('keperluan', 'like', "%{$request->q}%"))
                        ->when($request->periode, function($query, $periode) {
                            if($periode == 'hari_ini') $query->whereDate('waktu_kunjungan', today());
                            elseif($periode == 'minggu_ini') $query->whereBetween('waktu_kunjungan', [now()->startOfWeek(), now()->endOfWeek()]);
                            elseif($periode == 'bulan_ini') $query->whereMonth('waktu_kunjungan', now()->month);
                        })
                        ->orderBy('waktu_kunjungan', 'desc')
                        ->get();

            $columns = ['ID', 'Nama Tamu', 'Instansi', 'Kontak', 'Keperluan', 'Waktu Kunjungan'];
            $filename = 'riwayat_bukutamu.csv';
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($riwayat, $columns, $tab) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($riwayat as $item) {
                if ($tab == 'pelayanan') {
                    $durasi = $item->waktu_selesai_sesi 
                        ? \Carbon\Carbon::parse($item->waktu_mulai_sesi)->diffInHours($item->waktu_selesai_sesi) . ' jam ' .
                          (\Carbon\Carbon::parse($item->waktu_mulai_sesi)->diffInMinutes($item->waktu_selesai_sesi) % 60) . ' menit'
                        : '-';
                    $skor = $item->surveyKepuasan->skor_kepuasan ?? null;
                    $rataRata = $skor ? round(array_sum($skor)/count($skor),1) : 'Belum Mengisi';

                    fputcsv($file, [
                        $item->antrian->nomor_antrian ?? '-',
                        $item->nama_pengunjung,
                        $item->instansi_pengunjung,
                        $item->no_hp,
                        $item->email,
                        $item->jenis_kelamin,
                        $item->pendidikan,
                        $item->jenisLayanan->nama_layanan ?? '-',
                        $item->waktu_mulai_sesi->format('d-m-Y'),
                        $durasi,
                        $item->status_penyelesaian,
                        $item->survey_token,
                        $rataRata
                    ]);
                } else {
                    fputcsv($file, [
                        $item->id,
                        $item->nama_tamu,
                        $item->instansi_tamu,
                        $item->kontak_tamu,
                        $item->keperluan,
                        \Carbon\Carbon::parse($item->waktu_kunjungan)->format('d-m-Y H:i')
                    ]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
