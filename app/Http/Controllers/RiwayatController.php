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
            $riwayat = Pelayanan::with(['petugas', 'jenisLayanan', 'antrian', 'surveyInternal'])
                ->when($request->q, function ($query, $q) {
                    $query->where('nama_pengunjung', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                        ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"))
                        ->orWhereHas('petugas', fn($q4) => $q4->where('username', 'like', "%{$q}%"));
                })
                ->when($request->status, function ($query, $status) {
                    if ($status == 'Proses') {
                        $query->whereNull('status_penyelesaian');
                    } else {
                        $query->where('status_penyelesaian', $status);
                    }
                })
                ->when($request->media_layanan, fn($query, $media) => $query->where('media_layanan', $media))
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
                    elseif ($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif ($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
                })
                ->orderBy('waktu_mulai_sesi', 'desc')
                ->paginate(10);
        } else {
            $riwayat = BukuTamu::when($request->q, fn($q1) =>
            $q1->where('nama_tamu', 'like', "%{$request->q}%")
                ->orWhere('instansi_tamu', 'like', "%{$request->q}%")
                ->orWhere('kontak_tamu', 'like', "%{$request->q}%")
                ->orWhere('keperluan', 'like', "%{$request->q}%"))
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'hari_ini') $query->whereDate('waktu_kunjungan', today());
                    elseif ($periode == 'minggu_ini') $query->whereBetween('waktu_kunjungan', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif ($periode == 'bulan_ini') $query->whereMonth('waktu_kunjungan', now()->month);
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
            $riwayat = Pelayanan::with(['petugas', 'jenisLayanan', 'antrian', 'surveyInternal'])
                ->when($request->q, function ($query, $q) {
                    $query->where('nama_pengunjung', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhereHas('antrian', fn($q2) => $q2->where('nomor_antrian', 'like', "%{$q}%"))
                        ->orWhereHas('jenisLayanan', fn($q3) => $q3->where('nama_layanan', 'like', "%{$q}%"))
                        ->orWhereHas('petugas', fn($q4) => $q4->where('username', 'like', "%{$q}%"));
                })
                ->when($request->status, function ($query, $status) {
                    if ($status == 'Proses') {
                        $query->whereNull('status_penyelesaian');
                    } else {
                        $query->where('status_penyelesaian', $status);
                    }
                })
                ->when($request->media_layanan, fn($query, $media) => $query->where('media_layanan', $media))
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'hari_ini') $query->whereDate('waktu_mulai_sesi', today());
                    elseif ($periode == 'minggu_ini') $query->whereBetween('waktu_mulai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif ($periode == 'bulan_ini') $query->whereMonth('waktu_mulai_sesi', now()->month);
                })
                ->orderBy('waktu_mulai_sesi', 'desc')
                ->get();

            $columns = [
                'No. Antrian',
                'Nama Pengunjung',
                'Instansi',
                'No. HP',
                'Email',
                'Jenis Kelamin',
                'Pendidikan',
                'Media Layanan',
                'Jenis Layanan',
                'Tanggal',
                'Status',
                'Kebutuhan Pengunjung',
                'Deskripsi Hasil',
                'Jenis Output',
                'Perlu Tindak Lanjut',
                'Tanggal Tindak Lanjut',
                'Catatan Tindak Lanjut',
                'Catatan Tambahan',
                'Surat Pengantar',
                'Dokumen Hasil'
            ];
            $filename = 'riwayat_pst.csv';
        } else {
            $riwayat = BukuTamu::when($request->q, fn($q1) =>
            $q1->where('nama_tamu', 'like', "%{$request->q}%")
                ->orWhere('instansi_tamu', 'like', "%{$request->q}%")
                ->orWhere('kontak_tamu', 'like', "%{$request->q}%")
                ->orWhere('keperluan', 'like', "%{$request->q}%"))
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'hari_ini') $query->whereDate('waktu_kunjungan', today());
                    elseif ($periode == 'minggu_ini') $query->whereBetween('waktu_kunjungan', [now()->startOfWeek(), now()->endOfWeek()]);
                    elseif ($periode == 'bulan_ini') $query->whereMonth('waktu_kunjungan', now()->month);
                })
                ->orderBy('waktu_kunjungan', 'desc')
                ->get();

            $columns = ['ID', 'Nama Tamu', 'Instansi', 'Kontak', 'Keperluan', 'Waktu Kunjungan'];
            $filename = 'riwayat_nonpst.csv';
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($riwayat, $columns, $tab) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($riwayat as $item) {
                if ($tab == 'pelayanan') {
                    $suratPengantar = $item->path_surat_pengantar
                        ? asset('storage/' . $item->path_surat_pengantar)
                        : '-';
                    $dokumenHasil = $item->path_dokumen_hasil
                        ? asset('storage/' . $item->path_dokumen_hasil)
                        : '-';

                    // Buat array data satu baris
                    $row = [
                        optional($item->antrian)->nomor_antrian ?? '-',
                        $item->nama_pengunjung,
                        $item->instansi_pengunjung,
                        $item->no_hp,
                        $item->email,
                        $item->jenis_kelamin,
                        $item->pendidikan,
                        ucfirst($item->media_layanan ?? '-'),
                        optional($item->jenisLayanan)->nama_layanan ?? '-',
                        optional($item->waktu_mulai_sesi)->format('d-m-Y') ?? '-',
                        $item->status_penyelesaian ?? '-',
                        $item->kebutuhan_pengunjung ?? '-',
                        $item->deskripsi_hasil ?? '-',
                        $item->jenis_output ?? '-',
                        $item->perlu_tindak_lanjut ?? '-',
                        optional($item->tanggal_tindak_lanjut)->format('d-m-Y') ?? '-',
                        $item->catatan_tindak_lanjut ?? '-',
                        $item->catatan_tambahan ?? '-',
                        $suratPengantar,
                        $dokumenHasil,
                    ];

                    // 🔍 Deteksi otomatis tipe data sebelum fputcsv
                    foreach ($row as &$value) {
                        if (is_array($value)) {
                            $value = json_encode($value);
                        } elseif (is_object($value)) {
                            $value = method_exists($value, '__toString')
                                ? (string) $value
                                : json_encode($value);
                        } elseif (is_bool($value)) {
                            $value = $value ? 'true' : 'false';
                        } elseif (is_null($value)) {
                            $value = '-';
                        }
                    }

                    fputcsv($file, $row);
                } else {
                    $row = [
                        $item->id,
                        $item->nama_tamu,
                        $item->instansi_tamu,
                        $item->kontak_tamu,
                        $item->keperluan,
                        \Carbon\Carbon::parse($item->waktu_kunjungan)->format('d-m-Y H:i'),
                    ];

                    foreach ($row as &$value) {
                        if (is_array($value)) {
                            $value = json_encode($value);
                        } elseif (is_object($value)) {
                            $value = method_exists($value, '__toString')
                                ? (string) $value
                                : json_encode($value);
                        } elseif (is_bool($value)) {
                            $value = $value ? 'true' : 'false';
                        } elseif (is_null($value)) {
                            $value = '-';
                        }
                    }

                    fputcsv($file, $row);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
