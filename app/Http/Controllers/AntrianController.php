<?php

namespace App\Http\Controllers;

use App\Models\JenisLayanan; // PASTIKAN BARIS INI ADA
use App\Models\Antrian;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    /**
     * Menampilkan halaman utama untuk mengambil antrian.
     */
    public function index()
    {
        // Mengambil semua data jenis layanan dari database
        $jenisLayanan = JenisLayanan::all();

        // Mengirim data tersebut ke view 'antrian.index'
        return view('antrian.index', compact('jenisLayanan'));
    }

    /**
     * Menyimpan antrian baru.
     */
    public function store(Request $request)
    {
        $jenisLayananId = $request->input('jenis_layanan_id');
        $jenisLayanan = JenisLayanan::findOrFail($jenisLayananId);

        // Logika untuk membuat nomor antrian
        $nomorAntrian = $this->generateNomorAntrian($jenisLayanan->kode_antrian);

        $antrian = Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'jenis_layanan_id' => $jenisLayananId,
        ]);

        return redirect()->route('antrian.index')->with('success', 'Nomor antrian Anda adalah: ' . $nomorAntrian);
    }

    /**
     * Fungsi private untuk generate nomor antrian.
     */
    private function generateNomorAntrian($kodeAntrian)
    {
        $tanggal = date('Y-m-d');

        // 1. Cari antrian terakhir HARI INI, TANPA MEMPERDULIKAN JENIS LAYANAN
        $antrianTerakhir = Antrian::whereDate('created_at', $tanggal)
                                  ->orderBy('id', 'desc')
                                  ->first();

        $nomorUrut = 1;
        if ($antrianTerakhir) {
            // Jika ada antrian sebelumnya (misal: KST-001)
            
            // 2. Kita perlu tahu kode antrian dari antrian terakhir itu untuk memotong nomornya
            $kodeAntrianTerakhir = $antrianTerakhir->jenisLayanan->kode_antrian;

            // 3. Potong nomor antrian terakhir berdasarkan panjang KODE-nya
            $nomorTerakhir = (int) substr(
                $antrianTerakhir->nomor_antrian,
                strlen($kodeAntrianTerakhir)
            );
            
            // 4. Tambah 1 untuk mendapatkan nomor urut baru
            $nomorUrut = $nomorTerakhir + 1;
        }

        // 5. Gabungkan KODE LAYANAN YANG SEKARANG DIAMBIL dengan NOMOR URUT BARU
        return $kodeAntrian . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
    }

    public function panggil($nomor)
    {
        // pastikan hanya ada 1 yang dipanggil
        \App\Models\Antrian::where('status', 'dipanggil')
            ->update(['status' => 'menunggu']);

        // set antrian yang dipilih jadi dipanggil
        \App\Models\Antrian::where('nomor_antrian', $nomor)
            ->update(['status' => 'dipanggil']);

        return back()->with('success', "Antrian $nomor sedang dipanggil.");
    }

    public function mulai($nomor)
    {
        // ubah jadi sedang dilayani
        \App\Models\Antrian::where('nomor_antrian', $nomor)
            ->where('status', 'dipanggil')
            ->update(['status' => 'sedang_dilayani']);

        return back()->with('success', "Antrian $nomor sudah mulai dilayani.");
    }


}