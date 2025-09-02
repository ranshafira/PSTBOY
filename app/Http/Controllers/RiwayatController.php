<?php
namespace App\Http\Controllers;

use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RiwayatController extends Controller
{
    public function index()
    {
        // Ambil semua riwayat pelayanan terbaru
        $riwayat = Pelayanan::with(['petugas', 'jenisLayanan', 'antrian'])
                    ->orderBy('waktu_mulai_sesi', 'desc')
                    ->paginate(10);

        return view('riwayat.index', compact('riwayat'));
    }

    // Method untuk export CSV
    public function exportCsv()
    {
        $riwayat = Pelayanan::with(['petugas','jenisLayanan','antrian'])
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

        $columns = ['No. Antrian', 'Klien', 'Jenis Layanan', 'Tanggal', 'Durasi', 'Status', 'Kepuasan'];

        $callback = function() use ($riwayat, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($riwayat as $p) {
                $durasi = $p->waktu_selesai_sesi 
                    ? \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInHours($p->waktu_selesai_sesi) . ' jam ' .
                      (\Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInMinutes($p->waktu_selesai_sesi) % 60) . ' menit'
                    : '-';
                $status = $p->waktu_selesai_sesi ? 'Selesai' : 'Proses';

                fputcsv($file, [
                    $p->antrian->nomor ?? '-',
                    $p->nama_pelanggan,
                    $p->jenisLayanan->nama ?? '-',
                    $p->waktu_mulai_sesi->format('d-m-Y'),
                    $durasi,
                    $status,
                    $p->kepuasan ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
