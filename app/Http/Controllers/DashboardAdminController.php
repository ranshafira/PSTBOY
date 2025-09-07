<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Jadwal;
use App\Models\JenisLayanan;
use App\Models\Pelayanan;
use App\Models\SurveyKepuasan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data analisis.
     */
    public function index(Request $request)
    {
        // === 1. DATA UNTUK STAT CARDS ===
        $totalAntrianHariIni = Antrian::whereDate('created_at', today())->count();
        $jumlahPetugas = User::where('role_id', 2)->count();
        $petugasHariIni = Jadwal::with('user')->where('tanggal', today())->get();

        // === 2. DATA PROPORSI PELAYANAN (PIE CHART) ===
        $filterPie = $request->input('filter_pie', 'bulan_ini');
        $proporsiLayanan = $this->getProporsiLayananData($filterPie);

        // === 3. DATA HASIL SURVEI (BAR CHART) ===
        $filterSurvei = $request->input('filter_survei', 'bulanan');
        $hasilSurvei = $this->getHasilSurveiData($filterSurvei);
        
        // === 4. DATA TREN TAHUNAN (LINE CHART) ===
        $filterTrenLayananId = $request->input('filter_tren_layanan');
        $trenTahunan = $this->getTrenTahunanData($filterTrenLayananId);
        $daftarJenisLayanan = JenisLayanan::orderBy('nama_layanan')->get();

        // === 5. DATA TOP PETUGAS ===
        $topPetugas = Pelayanan::with('petugas')
            ->select('petugas_id', DB::raw('count(*) as jumlah_layanan'))
            ->whereNotNull('petugas_id')
            ->where('status_penyelesaian', 'selesai')
            ->groupBy('petugas_id')
            ->orderByDesc('jumlah_layanan')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalAntrianHariIni',
            'jumlahPetugas',
            'petugasHariIni',
            'proporsiLayanan',
            'filterPie',
            'hasilSurvei',
            'filterSurvei',
            'trenTahunan',
            'daftarJenisLayanan',
            'filterTrenLayananId',
            'topPetugas'
        ));
    }

    /**
     * Logika untuk mengambil data Proporsi Layanan berdasarkan filter.
     */
    private function getProporsiLayananData($filter)
    {
        $query = Pelayanan::join('jenis_layanan', 'pelayanan.jenis_layanan_id', '=', 'jenis_layanan.id')
            ->select('jenis_layanan.nama_layanan as nama', DB::raw('count(pelayanan.id) as jumlah'));

        switch ($filter) {
            case 'minggu_ini':
                $query->whereBetween('pelayanan.waktu_selesai_sesi', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'triwulan_ini':
                $query->whereBetween('pelayanan.waktu_selesai_sesi', [now()->startOfQuarter(), now()->endOfQuarter()]);
                break;
            case 'bulan_ini':
            default:
                $query->whereMonth('pelayanan.waktu_selesai_sesi', now()->month);
                break;
        }

        return $query->groupBy('jenis_layanan.nama_layanan')->pluck('jumlah', 'nama');
    }

    /**
     * Logika untuk mengambil data Hasil Survei berdasarkan filter.
     */
    private function getHasilSurveiData($filter)
    {
        $query = SurveyKepuasan::query();

        switch ($filter) {
            case 'triwulanan':
                $query->whereBetween('waktu_isi', [now()->startOfQuarter(), now()->endOfQuarter()]);
                break;
            case 'bulanan':
            default:
                $query->whereMonth('waktu_isi', now()->month);
                break;
        }
        
        $surveys = $query->get();
        if ($surveys->isEmpty()) {
            return collect(); // Return collection kosong jika tidak ada data
        }

        // Agregasi manual skor dari kolom JSON
        $skorAgregat = [];
        $hitungAgregat = [];

        foreach ($surveys as $survey) {
            // Pastikan skor_kepuasan adalah array
            $skorArray = is_array($survey->skor_kepuasan) ? $survey->skor_kepuasan : json_decode($survey->skor_kepuasan, true);
            if (is_array($skorArray)) {
                foreach ($skorArray as $aspek => $nilai) {
                    if (!isset($skorAgregat[$aspek])) {
                        $skorAgregat[$aspek] = 0;
                        $hitungAgregat[$aspek] = 0;
                    }
                    $skorAgregat[$aspek] += (int) $nilai;
                    $hitungAgregat[$aspek]++;
                }
            }
        }
        
        // Hitung rata-rata
        $hasilAkhir = [];
        foreach ($skorAgregat as $aspek => $totalSkor) {
            $hasilAkhir[ucwords(str_replace('_', ' ', $aspek))] = round($totalSkor / $hitungAgregat[$aspek], 2);
        }
        
        return collect($hasilAkhir);
    }
    
    /**
     * Logika untuk mengambil data Tren Tahunan berdasarkan filter jenis layanan.
     */
    private function getTrenTahunanData($jenisLayananId)
    {
        $query = Pelayanan::select(
                DB::raw("DATE_FORMAT(waktu_selesai_sesi, '%Y-%m') as bulan"),
                DB::raw('count(*) as jumlah')
            )
            ->where('waktu_selesai_sesi', '>=', now()->subYear())
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc');
        
        if($jenisLayananId) {
            $query->where('jenis_layanan_id', $jenisLayananId);
        }

        $data = $query->pluck('jumlah', 'bulan');

        // Pastikan semua 12 bulan terakhir ada dalam data, isi 0 jika tidak ada
        $hasil = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->format('Y-m');
            $hasil[$bulan] = $data[$bulan] ?? 0;
        }
        
        return collect($hasil);
    }

    /**
     * Fungsi untuk ekspor data survei ke CSV.
     */
    public function exportSurvei(Request $request)
    {
        $filter = $request->input('filter_survei', 'bulanan');
        $query = SurveyKepuasan::with('pelayanan.jenisLayanan', 'pelayanan.petugas');

        switch ($filter) {
            case 'triwulanan':
                $query->whereBetween('waktu_isi', [now()->startOfQuarter(), now()->endOfQuarter()]);
                break;
            case 'bulanan':
            default:
                $query->whereMonth('waktu_isi', now()->month);
                break;
        }

        $surveys = $query->get();
        $fileName = 'laporan-survei-' . $filter . '-' . date('Y-m-d') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $callback = function() use ($surveys) {
            $file = fopen('php://output', 'w');
            
            // Header Kolom
            fputcsv($file, ['ID Pelayanan', 'Waktu Isi', 'Layanan', 'Petugas', 'Aspek Penilaian', 'Skor', 'Rekomendasi', 'Saran Masukan']);

            foreach ($surveys as $survey) {
                $skorArray = is_array($survey->skor_kepuasan) ? $survey->skor_kepuasan : json_decode($survey->skor_kepuasan, true);
                if (is_array($skorArray)) {
                    foreach($skorArray as $aspek => $skor) {
                        fputcsv($file, [
                            $survey->pelayanan_id,
                            $survey->waktu_isi,
                            $survey->pelayanan->jenisLayanan->nama_layanan ?? 'N/A',
                            $survey->pelayanan->petugas->nama_lengkap ?? 'N/A',
                            ucwords(str_replace('_', ' ', $aspek)),
                            $skor,
                            $survey->rekomendasi ? 'Ya' : 'Tidak',
                            is_array($survey->saran_masukan) ? implode(', ', $survey->saran_masukan) : $survey->saran_masukan
                        ]);
                    }
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}