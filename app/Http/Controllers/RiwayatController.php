<?php
namespace App\Http\Controllers;

use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $riwayat = Pelayanan::with(['petugas', 'jenisLayanan', 'antrian', 'surveyKepuasan'])
            ->when($request->q, function($query, $q) {
                $query->where('nama_pelanggan', 'like', "%{$q}%")
                    ->orWhere('kontak_pelanggan', 'like', "%{$q}%") 
                    ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                    ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"));
            })
            ->when($request->status, function($query, $status) {
                if($status == 'Selesai') $query->where('status_penyelesaian', 'Selesai');
                if($status == 'Selesai dengan tindak lanjut')  $query->where('status_penyelesaian', 'Selesai dengan tindak lanjut');
                if($status == 'Tidak dapat dipenuhi')  $query->where('status_penyelesaian', 'Tidak dapat dipenuhi');
                if($status == 'Dibatalkan klien')  $query->where('status_penyelesaian', 'Dibatalkan klien');
            })
            ->when($request->jenis_layanan, fn($query, $id) => $query->where('jenis_layanan_id', $id))
            ->when($request->periode, function($query, $periode) {
                if($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
                if($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                if($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
            })
            ->orderBy('waktu_mulai_sesi', 'desc')
            ->paginate(10);

        return view('riwayat.index', compact('riwayat'));
    }

    public function exportCsv(Request $request)
    {
        $riwayat = Pelayanan::with(['petugas','jenisLayanan','antrian','surveyKepuasan'])
        ->when($request->q, function($query, $q) {
            $query->where('nama_pelanggan', 'like', "%{$q}%")
                ->orWhere('kontak_pelanggan', 'like', "%{$q}%")
                ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"));
        })
        ->when($request->status, function($query, $status) {
            if($status == 'Selesai') $query->where('status_penyelesaian', 'Selesai');
            if($status == 'Selesai dengan tindak lanjut')  $query->where('status_penyelesaian', 'Selesai dengan tindak lanjut');
            if($status == 'Tidak dapat dipenuhi')  $query->where('status_penyelesaian', 'Tidak dapat dipenuhi');
            if($status == 'Dibatalkan klien')  $query->where('status_penyelesaian', 'Dibatalkan klien');
        })
        ->when($request->jenis_layanan, fn($query, $id) => $query->where('jenis_layanan_id', $id))
        ->when($request->periode, function($query, $periode) {
            if($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
            if($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
            if($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
        })
        ->orderBy('waktu_mulai_sesi', 'desc')
        ->get(); 

        $filename = 'riwayat_pelayanan.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No. Antrian', 'Klien','Kontak', 'Jenis Layanan', 'Tanggal', 'Durasi', 'Status', 'Token Survei','Kepuasan'];

        $callback = function() use ($riwayat, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($riwayat as $p) {
                $durasi = $p->waktu_selesai_sesi 
                    ? \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInHours($p->waktu_selesai_sesi) . ' jam ' .
                      (\Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInMinutes($p->waktu_selesai_sesi) % 60) . ' menit'
                    : '-';
                $status = $p->waktu_selesai_sesi ? 'Selesai' : 'Proses';
                $skor = $p->surveyKepuasan->skor_kepuasan ?? null;
                $rataRata = $skor ? round(array_sum($skor) / count($skor), 1) : 'Belum Mengisi';

                fputcsv($file, [
                    $p->antrian->nomor_antrian ?? '-',
                    $p->nama_pelanggan,
                    $p->kontak_pelanggan,
                    $p->jenisLayanan->nama_layanan ?? '-',
                    $p->waktu_mulai_sesi->format('d-m-Y'),
                    $durasi,
                    $p->status_penyelesaian,
                    $p->survey_token,
                    $rataRata != 'Belum Mengisi' ? $rataRata : $rataRata
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
