<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\SurveyInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyInternalController extends Controller
{
    public function show($token)
    {
        $pelayanan = Pelayanan::where('survey_token', $token)->firstOrFail();

        // Cek apakah survei internal sudah diisi
        if (SurveyInternal::where('pelayanan_id', $pelayanan->id)->exists()) {
            // Jika sudah, langsung arahkan ke halaman terimakasih
            return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
                ->with('info', 'Anda sudah mengisi Survei Kepuasan Pelayanan.');
        }

        return view('survei.internal.show', compact('pelayanan'));
    }

    // app/Http/Controllers/SurveyInternalController.php

    public function store(Request $request, $token)
    {
        $pelayanan = Pelayanan::where('survey_token', $token)->firstOrFail();

        $validated = $request->validate([
            'skor_keseluruhan' => 'required|integer|min:1|max:5',
            'skor_petugas' => 'required|integer|min:1|max:5',
            'saran' => 'required_if:skor_keseluruhan,<,4|required_if:skor_petugas,<,4|nullable|string|max:1000',
        ]);

        // 1. CATAT TIMESTAMP SELESAI
        $pelayanan->update(['waktu_selesai_sesi' => now()]);

        // 2. [PERBAIKAN] UPDATE STATUS ANTRIAN MENJADI 'SELESAI'
        $pelayanan->antrian->update(['status' => 'selesai']);

        // 3. SIMPAN HASIL SURVEI INTERNAL
        SurveyInternal::create([
            'pelayanan_id' => $pelayanan->id,
            'skor_keseluruhan' => $validated['skor_keseluruhan'],
            'skor_petugas' => $validated['skor_petugas'],
            'saran' => $validated['saran'] ?? null,
        ]);

        // 4. BUAT TOKEN BARU UNTUK SKD
        do {
            $skdToken = strtoupper(Str::random(3) . '-' . Str::random(3));
        } while (Pelayanan::where('skd_token', $skdToken)->exists());

        $pelayanan->update(['skd_token' => $skdToken]);

        // 5. ARAHKAN KE HALAMAN TERIMA KASIH
        return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
            ->with('success', 'Terima kasih telah mengisi survei kepuasan!');
    }
}
