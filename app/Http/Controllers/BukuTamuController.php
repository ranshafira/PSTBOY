<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Illuminate\Http\Request;

class BukuTamuController extends Controller
{
    /**
     * Menampilkan form buku tamu.
     */
    public function create()
    {
        return view('bukutamu.create');
    }

    /**
     * Menyimpan data dari form buku tamu ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'instansi_tamu' => 'nullable|string|max:255',
            'kontak_tamu' => 'nullable|string|max:100',
            'keperluan' => 'required|string',
        ]);

        // 2. Simpan ke database
        BukuTamu::create($validatedData);

        // 3. Redirect kembali ke halaman form dengan pesan sukses
        return redirect()->route('bukutamu.create')
                         ->with('success', 'Terima kasih! Data Anda telah berhasil disimpan.');

    }
}