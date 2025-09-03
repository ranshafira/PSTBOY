<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\JenisLayanan;
use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class PelayananController extends Controller
{
    public function show($nomor)
    {
        $antrian = Antrian::where('nomor_antrian', $nomor)->firstOrFail();
        $jenisLayanan = JenisLayanan::all();
        return view('pelayanan.show', compact('nomor', 'antrian', 'jenisLayanan'));
    }

    public function start(Request $request, $nomor)
    {
        $request->validate(['jenis_layanan_id' => 'required|exists:jenis_layanan,id', 'waktu_mulai' => 'required|string',]);
        $antrian = Antrian::where('nomor_antrian', $nomor)->firstOrFail();
        $waktu_mulai_string = str_replace('.', ':', $request->waktu_mulai);
        $waktu_mulai_datetime = Carbon::today()->format('Y-m-d') . ' ' . $waktu_mulai_string;
        $pelayanan = Pelayanan::create(['petugas_id' => auth()->id(),'antrian_id' => $antrian->id,'jenis_layanan_id' => $request->jenis_layanan_id,'waktu_mulai_sesi' => $waktu_mulai_datetime,]);
        return redirect()->route('pelayanan.identitas', $pelayanan->id);
    }

    public function identitas($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        return view('pelayanan.identitas', compact('pelayanan'));
    }

    // Step 2: Simpan Identitas (dengan penyimpanan lokal)
    public function storeIdentitas(Request $request, $id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'instansi_pelanggan' => 'nullable|string|max:255',
            'kontak_pelanggan' => 'nullable|string|max:255',
            'path_surat_pengantar' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'kebutuhan_pelanggan' => 'nullable|string',
        ]);

        // PERUBAHAN: Upload ke penyimpanan lokal (public disk)
        if ($request->hasFile('path_surat_pengantar')) {
            // Simpan file di dalam folder 'storage/app/public/surat_pengantar'
            // dan simpan path-nya ke database
            $data['path_surat_pengantar'] = $request->file('path_surat_pengantar')->store('surat_pengantar', 'public');
        }

        $pelayanan->update($data);

        // Redirect ke halaman hasil pelayanan
        return redirect()->route('pelayanan.hasil', $pelayanan->id);
    }

    // TAMBAHAN BARU: Step 4 - Halaman Hasil Pelayanan
    public function hasil($id)
    {
        $pelayanan = Pelayanan::with('antrian')->findOrFail($id);
        return view('pelayanan.hasil', compact('pelayanan'));
    }

    public function storeHasil(Request $request, $id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        $data = $request->validate([
            'status_penyelesaian' => 'required|string',
            'deskripsi_hasil' => 'required|string',
            'jenis_output' => 'nullable|array',
            'path_dokumen_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,csv|max:10240',
            'perlu_tindak_lanjut' => 'nullable|boolean',
            'tanggal_tindak_lanjut' => 'required_if:perlu_tindak_lanjut,1|nullable|date',
            'catatan_tindak_lanjut' => 'required_if:perlu_tindak_lanjut,1|nullable|string',
            'catatan_tambahan' => 'nullable|string',
        ]);
        
        $data['perlu_tindak_lanjut'] = $request->has('perlu_tindak_lanjut');

        if ($request->hasFile('path_dokumen_hasil')) {
            $data['path_dokumen_hasil'] = $request->file('path_dokumen_hasil')->store('dokumen_hasil', 'public');
        }

        // PERUBAHAN 1: Hapus pencatatan waktu selesai di sini
        // $data['waktu_selesai_sesi'] = now(); // <-- Baris ini dihapus

        $pelayanan->update($data);

        // PERUBAHAN 2: Arahkan ke halaman ringkasan 'selesai', bukan ke dashboard
        return redirect()->route('pelayanan.selesai', $pelayanan->id);
    }

    // METHOD BARU: Menampilkan halaman Selesai Pelayanan
    public function selesai($id)
    {
        // Ambil semua data yang relevan untuk ditampilkan
        $pelayanan = Pelayanan::with(['antrian', 'jenisLayanan'])->findOrFail($id);

        // Tampilkan view baru
        return view('pelayanan.selesai', compact('pelayanan'));
    }

   public function finish(Request $request, $id)
{
    $pelayanan = Pelayanan::findOrFail($id);

    if (is_null($pelayanan->waktu_selesai_sesi)) {
        $pelayanan->waktu_selesai_sesi = now();
        
        // --- LOGIKA PEMBUATAN PIN UNIK ---
        do {
            // Buat PIN acak dengan format XXX-XXX
            $token = strtoupper(Str::random(3) . '-' . Str::random(3));
            // Cek apakah PIN ini sudah ada di database
            $exists = Pelayanan::where('survey_token', $token)->exists();
        } while ($exists); // Ulangi jika sudah ada, untuk menjamin keunikan

        $pelayanan->survey_token = $token;
        // --- SELESAI ---

        $pelayanan->save();
    }

    return redirect()->route('pelayanan.selesai', $pelayanan->id)
                     ->with('success', 'Waktu pelayanan berhasil dicatat!');
}
}