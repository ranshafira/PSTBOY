<?php

namespace App\Http\Controllers;

use App\Models\JenisLayanan;
use App\Models\Antrian;
use Illuminate\Http\Request;
use App\Models\Pelayanan;
use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{

    public function index()
    {
        $jenisLayanan = JenisLayanan::all();
        $today = Carbon::today();

        // ambil semua jadwal untuk hari ini (bisa 2 shift)
        $jadwalHariIni = Jadwal::whereDate('tanggal', $today)
            ->with('user') // pastikan relasi user ada di model Jadwal
            ->get();

        // kirim semua jadwal hari ini ke view
        return view('antrian.index', compact('jenisLayanan', 'jadwalHariIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengunjung'  => 'required|string|max:255|regex:/^[\pL\s\'-]+$/u',
            'jenis_layanan_id' => 'nullable|integer|exists:jenis_layanan,id',
            'media_layanan'    => 'nullable|string|in:whatsapp,email,langsung',
            'instansi_pengunjung' => 'nullable|string|max:255|regex:/^[\pL\s0-9]+$/u',
            'pendidikan'       => 'nullable|string|max:50',
            'email'            => 'nullable|email|max:255',
            'jenis_kelamin'    => 'nullable|string|in:Laki-laki,Perempuan',
            'no_hp'            => 'nullable|string|min:10|max:15|regex:/^[0-9]+$/',
        ], [
            'nama_pengunjung.regex' => 'Nama hanya boleh berisi huruf, spasi, tanda petik satu, dan tanda hubung.',
            'instansi_pengunjung.regex' => 'Instansi hanya boleh berisi huruf, angka, dan spasi.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'no_hp.min' => 'Nomor HP harus terdiri dari 10 hingga 15 digit.',
            'no_hp.max' => 'Nomor HP harus terdiri dari 10 hingga 15 digit.',
        ]);

        // Tentukan kode antrian
        if ($request->media_layanan && $request->media_layanan !== 'langsung') {
            $kode = match ($request->media_layanan) {
                'whatsapp' => 'WA-',
                'email'    => 'EML-',
                default    => 'X',
            };
        } elseif ($request->jenis_layanan_id) {
            $jenis = JenisLayanan::findOrFail($request->jenis_layanan_id);
            $kode = $jenis->kode_antrian;
        } else {
            return back()->withErrors('Harus pilih jenis layanan atau media layanan.');
        }

        // Generate nomor antrian unik per kode per hari
        $nomorAntrian = $this->generateNomorAntrian($kode);

        // Membuat record antrian dahulu
        $antrian = Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'status'        => 'menunggu',
            'jenis_layanan_id' => $request->jenis_layanan_id,
        ]);

        // Simpan ke tabel pelayanan
        $pelayanan = Pelayanan::create([
            'nama_pengunjung'         => $request->nama_pengunjung,
            'instansi_pengunjung'     => $request->instansi_pengunjung,
            'pendidikan'              => $request->pendidikan,
            'email'                   => $request->email,
            'jenis_kelamin'           => $request->jenis_kelamin,
            'no_hp'                   => $request->no_hp,
            'jenis_layanan_id'        => $request->jenis_layanan_id,
            'media_layanan'           => $request->media_layanan,
            'nomor_antrian'           => $nomorAntrian,
            'antrian_id'              => $antrian->id,
            'petugas_id'              => null,
            'waktu_mulai_sesi'        => null,
        ]);

        return redirect()->back()
            ->with('success', 'Nomor antrian Anda adalah: ' . $nomorAntrian)
            ->with('nomor_antrian', $nomorAntrian)
            ->with('media_layanan', $request->input('media_layanan'));
    }

    private function generateNomorAntrian($kodeAntrian)
    {
        $tanggal = date('Y-m-d');

        $antrianTerakhir = Antrian::whereDate('created_at', $tanggal)
            ->orderBy('id', 'desc')
            ->first();

        $nomorUrut = 1;
        if ($antrianTerakhir) {
            $nomorTerakhir = (int) substr($antrianTerakhir->nomor_antrian, -3);
            $nomorUrut = $nomorTerakhir + 1;
        }

        return $kodeAntrian . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
    }

    public function panggil($id, Request $request)
    {
        $antrian = Antrian::findOrFail($id);

        if ($request->has('ulang')) {
            // Panggil ulang: tetap status dipanggil, tidak reset antrian lain
            $message = "Antrian {$antrian->nomor_antrian} dipanggil ulang.";
        } else {
            // Panggil baru: reset semua dipanggil ke menunggu
            Antrian::where('status', 'dipanggil')->update(['status' => 'menunggu']);
            $antrian->status = 'dipanggil';
            $antrian->save();
            $message = "Antrian {$antrian->nomor_antrian} sedang dipanggil.";
        }

        return back()->with('success', $message);
    }

    public function batal($id)
    {
        $antrian = \App\Models\Antrian::find($id);
        if ($antrian) {
            $antrian->status = 'menunggu';
            $antrian->save();
        }

        return back()->with('success', "Antrian {$antrian->nomor_antrian} dikembalikan ke menunggu.");
    }
}
