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
    $pelayanan = Pelayanan::where('survey_token', $token)->whereNull('survey_completed_at')->firstOrFail();
    
    // 1. Validasi semua input dari form baru
    $validated = $request->validate([
        'skor_keseluruhan' => 'required|integer|between:1,5',
        'skor_kualitas' => 'required|integer|between:1,5',
        'skor_petugas' => 'required|integer|between:1,5',
        'skor_efisiensi' => 'required|integer|between:1,5',
        'skor_fasilitas' => 'required|integer|between:1,5',
        'rekomendasi' => 'required|boolean',
        'feedback_pelayanan' => 'nullable|string|max:2000',
        'saran_perbaikan' => 'nullable|string|max:2000',
    ]);

    // 2. Kelompokkan data skor menjadi satu array
    $skor = [
        'keseluruhan' => (int) $validated['skor_keseluruhan'],
        'kualitas_layanan' => (int) $validated['skor_kualitas'],
        'kinerja_petugas' => (int) $validated['skor_petugas'],
        'efisiensi_waktu' => (int) $validated['skor_efisiensi'],
        'fasilitas' => (int) $validated['skor_fasilitas'],
    ];

    // 3. Kelompokkan data masukan & saran menjadi satu array
    $masukan = [
        'feedback' => $validated['feedback_pelayanan'],
        'saran' => $validated['saran_perbaikan'],
    ];

    // 4. Simpan ke database menggunakan Model SurveyKepuasan
    SurveyKepuasan::create([
        'pelayanan_id' => $pelayanan->id,
        'skor_kepuasan' => $skor, // Disimpan sebagai JSON
        'rekomendasi' => (bool) $validated['rekomendasi'],
        'saran_masukan' => $masukan, // Disimpan sebagai JSON
        'waktu_isi' => now(),
    ]);

    // 5. Update status di tabel pelayanan
    $pelayanan->survey_completed_at = now();
    $pelayanan->save();

    return view('survei.thanks');
}
}