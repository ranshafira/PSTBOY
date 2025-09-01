<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\JenisLayanan;
use App\Models\Antrian;
use Carbon\Carbon;

class PelayananController extends Controller
{
    // Step 0: Halaman mulai pelayanan
    public function show($nomor)
    {
        $antrian = Antrian::where('nomor_antrian', $nomor)->firstOrFail();
        $jenisLayanan = JenisLayanan::all();

        return view('pelayanan.show', compact('nomor', 'antrian', 'jenisLayanan'));
    }

    // Step 1: Simpan pilihan layanan & buat record pelayanan
    public function start(Request $request, $nomor)
{
    $request->validate([
        'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
        'waktu_mulai' => 'required|string',
    ]);

    $antrian = Antrian::where('nomor_antrian', $nomor)->firstOrFail();

    $waktu_mulai_string = str_replace('.', ':', $request->waktu_mulai);

    $waktu_mulai_datetime = Carbon::today()->format('Y-m-d') . ' ' . $waktu_mulai_string;

    $pelayanan = Pelayanan::create([
        'petugas_id' => auth()->id(),
        'antrian_id' => $antrian->id,
        'jenis_layanan_id' => $request->jenis_layanan_id,
        'waktu_mulai_sesi' => $waktu_mulai_datetime, // Gunakan variabel datetime lengkap
    ]);

    return redirect()->route('pelayanan.identitas', $pelayanan->id);
}

    // Step 2: Isi Identitas
    public function identitas($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        return view('pelayanan.identitas', compact('pelayanan'));
    }

    // Step 2: Simpan Identitas
    public function storeIdentitas(Request $request, $id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'instansi_pelanggan' => 'nullable|string|max:255',
            'kontak_pelanggan' => 'nullable|string|max:255',
            'path_surat_pengantar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kebutuhan_pelanggan' => 'nullable|string',
        ]);

        if ($request->hasFile('path_surat_pengantar')) {
            $data['path_surat_pengantar'] = $request->file('path_surat_pengantar')->store('surat_pengantar', 'public');
        }

        $pelayanan->update($data);

        return redirect('/dashboard')->with('success', 'Identitas berhasil disimpan');
    }
}
