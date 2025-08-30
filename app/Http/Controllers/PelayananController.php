<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;

class PelayananController extends Controller
{
    public function show($nomor)
    {
        // Cari antrian berdasarkan nomor_antrian
        $antrian = Antrian::where('nomor_antrian', $nomor)->first();

        if (!$antrian) {
            return redirect()->back()->with('error', 'Nomor antrian tidak ditemukan');
        }

        // Kirim data ke view pelayanan
        return view('pelayanan.show', compact('antrian'));
    }
}
