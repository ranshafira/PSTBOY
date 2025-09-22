<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\SurveySkd;
use Illuminate\Http\Request;

class SurveySkdController extends Controller
{
    public function entry()
    {
        return view('survei.skd.entry');
    }

    public function find(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|exists:pelayanan,skd_token'
        ]);
        return redirect()->route('survei.skd.show', $validated['token']);
    }

    public function show($token)
    {
        $pelayanan = Pelayanan::where('skd_token', $token)->firstOrFail();

        if (SurveySkd::where('pelayanan_id', $pelayanan->id)->exists()) {
            return redirect()->route('survei.skd.entry')->with('info', 'Anda sudah mengisi Survei Kebutuhan Dasar (SKD) untuk token ini.');
        }

        return view('survei.skd.show', compact('pelayanan'));
    }

    public function store(Request $request, $token)
    {
        $pelayanan = Pelayanan::where('skd_token', $token)->firstOrFail();

        $validated = $request->validate([
            'skor_pertanyaan_1' => 'required|integer',
            'skor_pertanyaan_2' => 'required|integer',
            'jawaban_terbuka' => 'nullable|string',
        ]);

        SurveySkd::create([
            'pelayanan_id' => $pelayanan->id,
            'skor_pertanyaan_1' => $validated['skor_pertanyaan_1'],
            'skor_pertanyaan_2' => $validated['skor_pertanyaan_2'],
            'jawaban_terbuka' => $validated['jawaban_terbuka'],
        ]);

        // [DIUBAH] Arahkan ke route 'terimakasih' yang baru
        return redirect()->route('survei.skd.terimakasih');
    }

    /**
     * [BARU] Menampilkan halaman "Terima Kasih" setelah SKD selesai.
     */
    public function terimakasih()
    {
        return view('survei.skd.terimakasih');
    }
}
