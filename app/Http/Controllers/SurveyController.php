<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\SurveyKepuasan;

class SurveyController extends Controller
{
    // Menampilkan halaman untuk memasukkan PIN
    public function entry()
    {
        return view('survei.entry');
    }

    // Memvalidasi PIN yang dimasukkan
    public function find(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:7', // Validasi format XXX-XXX
        ], [
            'pin.required' => 'Kode survei wajib diisi.',
            'pin.size' => 'Format kode survei tidak valid.'
        ]);

        $pin = strtoupper($request->pin);

        // Cari sesi pelayanan berdasarkan PIN
        $pelayanan = Pelayanan::where('survey_token', $pin)->first();

        // Kondisi jika PIN tidak ditemukan
        if (!$pelayanan) {
            return back()->withErrors(['pin' => 'Kode survei tidak ditemukan.']);
        }

        // Kondisi jika survei sudah pernah diisi
        if ($pelayanan->survey_completed_at) {
            return back()->withErrors(['pin' => 'Survei dengan kode ini sudah pernah diisi.']);
        }

        // Jika valid, arahkan ke halaman survei yang sebenarnya
        return redirect()->route('survei.show', $pelayanan->survey_token);
    }

    // Menampilkan form survei
    public function show($token)
    {
        $pelayanan = Pelayanan::where('survey_token', $token)->whereNull('survey_completed_at')->firstOrFail();
        return view('survei.show', compact('pelayanan'));
    }

    public function store(Request $request, $token)
    {
        $pelayanan = Pelayanan::where('survey_token', $token)->firstOrFail();

        // Pastikan survei hanya bisa diisi sekali
        if ($pelayanan->surveyKepuasan) {
            return redirect()->route('pelayanan.terimakasih', $pelayanan->id)->with('info', 'Anda sudah pernah mengisi survei ini.');
        }

        $validated = $request->validate([
            'skor_kepuasan' => 'required|array',
            'skor_kepuasan.*' => 'required|integer|min:1|max:5',
            'rekomendasi' => 'required|boolean',
            'saran_masukan' => 'nullable|array',
            'saran_masukan.*' => 'nullable|string',
        ]);

        SurveyKepuasan::create([
            'pelayanan_id' => $pelayanan->id,
            'skor_kepuasan' => $validated['skor_kepuasan'],
            'rekomendasi' => $validated['rekomendasi'],
            'saran_masukan' => $validated['saran_masukan'],
        ]);

        // === [PINDAHAN] LOGIKA FINISH PELAYANAN ===
        if (is_null($pelayanan->waktu_selesai_sesi)) {
            $pelayanan->waktu_selesai_sesi = now(); // Catat waktu selesai
            $pelayanan->save();

            if ($pelayanan->antrian) {
                $pelayanan->antrian->status = 'selesai'; // Update status antrian
                $pelayanan->antrian->save();
            }
        }
        // === SELESAI PINDAHAN ===

        return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
            ->with('success', 'Survei berhasil dikirim. Terima kasih!');
    }
}
