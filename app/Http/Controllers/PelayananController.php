<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\BukuTamu;
use App\Models\JenisLayanan;
use App\Models\Pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PelayananController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard pelayanan petugas.
     */
    public function index()
    {
        $riwayatAntrian = Antrian::with('jenisLayanan', 'pelayanan')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'antrian_page');

        $riwayatBukuTamu = BukuTamu::whereDate('waktu_kunjungan', today())
            ->orderBy('waktu_kunjungan', 'desc')
            ->paginate(10, ['*'], 'bukutamu_page');

        $statistik = [
            'totalAntrianHariIni' => Antrian::whereDate('created_at', today())->whereIn('status', ['menunggu', 'lewati', 'dipanggil', 'sedang_dilayani'])->count(),
            'sudahDilayani' => Antrian::whereDate('created_at', today())->where('status', 'selesai')->count(),
            'antrianBerjalan' => Antrian::whereIn('status', ['dipanggil', 'sedang_dilayani'])->latest('updated_at')->first(),
            'bukuTamuCount' => BukuTamu::whereDate('waktu_kunjungan', today())->count(),
        ];

        return view('pelayanan.index', compact('riwayatAntrian', 'riwayatBukuTamu', 'statistik'));
    }

    /**
     * LANGKAH 1: Menampilkan form identitas pengunjung.
     */
    public function createStep1($antrian_id)
    {
        $antrian = Antrian::findOrFail($antrian_id);
        $jenisLayanan = JenisLayanan::all();
        $pelayanan = Pelayanan::where('antrian_id', $antrian->id)->latest()->first();

        return view('pelayanan.langkah1', compact('antrian', 'jenisLayanan', 'pelayanan'));
    }

    /**
     * LANGKAH 1: Menyimpan data identitas & memulai sesi pelayanan.
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'antrian_id' => 'required|exists:antrian,id',
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'nama_pengunjung' => 'required|string|max:255',
            'instansi_pengunjung' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pendidikan' => 'nullable|string|max:50',
        ]);

        $antrian = Antrian::find($validated['antrian_id']);
        $antrian->status = 'sedang_dilayani';
        $antrian->save();

        $pelayanan = Pelayanan::updateOrCreate(
            ['antrian_id' => $validated['antrian_id']],
            [
                'petugas_id' => auth()->id(),
                'waktu_mulai_sesi' => now(),
                'jenis_layanan_id' => $validated['jenis_layanan_id'],
                'nama_pengunjung' => $validated['nama_pengunjung'],
                'instansi_pengunjung' => $validated['instansi_pengunjung'],
                'no_hp' => $validated['no_hp'],
                'email' => $validated['email'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'pendidikan' => $validated['pendidikan'],
            ]
        );

        return redirect()->route('pelayanan.langkah2.create', $pelayanan->id);
    }

    /**
     * LANGKAH 2: Menampilkan form hasil pelayanan.
     */
    public function createStep2(Pelayanan $pelayanan)
    {
        $pelayanan->load('antrian');
        return view('pelayanan.langkah2', compact('pelayanan'));
    }

    /**
     * LANGKAH 2: Menyimpan data hasil pelayanan.
     */
    public function storeStep2(Request $request, Pelayanan $pelayanan)
    {
        $data = $request->validate([
            'kebutuhan_pengunjung' => 'required|string',
            'path_surat_pengantar' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:5120',
            'status_penyelesaian' => 'required|string',
            'deskripsi_hasil' => 'required|string',
            'jenis_output' => 'nullable|array',
            'path_dokumen_hasil' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv|max:10240',
            'perlu_tindak_lanjut' => 'nullable|boolean',
            'tanggal_tindak_lanjut' => 'required_if:perlu_tindak_lanjut,1|nullable|date',
            'catatan_tindak_lanjut' => 'required_if:perlu_tindak_lanjut,1|nullable|string',
        ]);

        if ($request->hasFile('path_surat_pengantar')) {
            $data['path_surat_pengantar'] = $request->file('path_surat_pengantar')->store('surat_pengantar', 'public');
        }
        if ($request->hasFile('path_dokumen_hasil')) {
            $data['path_dokumen_hasil'] = $request->file('path_dokumen_hasil')->store('dokumen_hasil', 'public');
        }
        $data['perlu_tindak_lanjut'] = $request->has('perlu_tindak_lanjut');

        if (empty($pelayanan->survey_token)) {
            do {
                $token = strtoupper(Str::random(3) . '-' . Str::random(3));
            } while (Pelayanan::where('survey_token', $token)->exists());
            $data['survey_token'] = $token;
        }

        $pelayanan->update($data);
        $pelayanan->antrian->update(['status' => 'selesai']);

        return redirect()->route('survei.internal.show', $pelayanan->survey_token);
    }

    /**
     * Halaman "Terima Kasih" setelah alur pelayanan selesai.
     */
    public function terimakasih(Pelayanan $pelayanan)
    {
        $pelayanan->load('antrian');
        return view('pelayanan.terimakasih', compact('pelayanan'));
    }

    /**
     * Fitur untuk melanjutkan sesi pelayanan yang belum selesai.
     */
    public function lanjutkan($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        if (!$pelayanan->deskripsi_hasil) {
            return redirect()->route('pelayanan.langkah2.create', $pelayanan->id);
        }
        return redirect()->route('pelayanan.detail', $pelayanan->id);
    }

    /**
     * Menampilkan halaman detail untuk riwayat pelayanan.
     */
    public function detail($id)
    {
        // CATATAN: Pastikan Anda memiliki file 'detail.blade.php' di dalam folder 'pelayanan'.
        $pelayanan = Pelayanan::with(['jenisLayanan', 'antrian', 'petugas', 'surveyKepuasan'])
            ->findOrFail($id);
        return view('pelayanan.detail', compact('pelayanan'));
    }
}
