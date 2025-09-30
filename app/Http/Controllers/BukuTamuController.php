<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Illuminate\Http\Request;

class BukuTamuController extends Controller
{
    public function createPST()
    {
        return view('antrian.bukutamu-pst'); // view untuk form PST
    }
    
    public function createNonPST()
    {
        return view('antrian.bukutamu-nonpst'); // view untuk form Non-PST
    }
    
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'instansi_tamu' => 'nullable|string|max:255',
            'kontak_tamu' => 'nullable|string|max:100',
            'keperluan' => 'required|string',
            'tujuan'    => 'required|string|in:Kepala BPS,Subbag Umum,Bagian Teknis,Bagian Pengaduan,Lainnya',
        ]);

        // 2. Simpan ke database
        BukuTamu::create($validatedData);

        // 3. Redirect kembali ke halaman form dengan pesan sukses
        return redirect()->route('bukutamu.nonpst')
                         ->with('success', 'Terima kasih! Data Anda telah berhasil disimpan.');
    }
}