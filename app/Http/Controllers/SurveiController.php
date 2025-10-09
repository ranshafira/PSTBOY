<?php

namespace App\Http\Controllers;

use App\Models\Pelayanan;
use App\Models\Survei;
use Illuminate\Http\Request;

class SurveiController extends Controller
{
    public function showPublic($kode_unik)
    {
        $pelayanan = Pelayanan::where('kode_unik', $kode_unik)->firstOrFail();
        return view('survei.public', compact('pelayanan'));
    }

    public function storePublic(Request $request, $kode_unik)
    {
        $pelayanan = Pelayanan::where('kode_unik', $kode_unik)->firstOrFail();

        $request->validate([
            'skor_keseluruhan' => 'required|integer|min:1|max:5',
            'skor_petugas' => 'required|integer|min:1|max:5',
            'saran' => 'nullable|string|max:500',
        ]);

        $pelayanan->surveyPublic()->create([
            'pelayanan_id' => $pelayanan->id,
            'skor_keseluruhan' => $request->skor_keseluruhan,
            'skor_petugas' => $request->skor_petugas,
            'saran' => $request->saran,
        ]);

        return redirect()->route('pelayanan.terimakasih', ['kode_unik' => $pelayanan->kode_unik]);
    }
}
