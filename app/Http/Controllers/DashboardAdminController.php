<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\SurveyInternal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        // === AMBIL FILTER DARI REQUEST ===
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $petugasId = $request->input('petugas_id'); // 🔹 Tambahan filter per-petugas

        // Default: 30 hari terakhir jika tidak ada filter
        if (!$startDate || !$endDate) {
            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }

        // Validasi format tanggal
        try {
            $startDateCarbon = Carbon::parse($startDate)->startOfDay();
            $endDateCarbon = Carbon::parse($endDate)->endOfDay();
        } catch (\Exception $e) {
            $startDateCarbon = Carbon::now()->subDays(30)->startOfDay();
            $endDateCarbon = Carbon::now()->endOfDay();
            $startDate = $startDateCarbon->format('Y-m-d');
            $endDate = $endDateCarbon->format('Y-m-d');
        }

        // Pastikan start_date tidak lebih besar dari end_date
        if ($startDateCarbon->gt($endDateCarbon)) {
            [$startDateCarbon, $endDateCarbon] = [$endDateCarbon, $startDateCarbon];
            [$startDate, $endDate] = [$startDateCarbon->format('Y-m-d'), $endDateCarbon->format('Y-m-d')];
        }

        // === BAGIAN 1: STATISTIK UMUM PELAYANAN ===
        $queryPelayanan = Pelayanan::query()
            ->whereBetween('created_at', [$startDateCarbon, $endDateCarbon]);

        if ($petugasId && $petugasId !== 'all') {
            $queryPelayanan->where('petugas_id', $petugasId);
        }

        $totalPelayananAll = (clone $queryPelayanan)->count();
        $totalPelayananSelesai = (clone $queryPelayanan)
            ->where('status_penyelesaian', 'selesai')
            ->count();
        $totalPelayananProses = (clone $queryPelayanan)
            ->where('status_penyelesaian', '!=', 'selesai')
            ->count();

        $persentasePenyelesaian = $totalPelayananAll > 0
            ? round(($totalPelayananSelesai / $totalPelayananAll) * 100, 1)
            : 0;

        // === BAGIAN 2: KINERJA PETUGAS ===
        $kinerjaPetugas = User::where('role_id', 2)
            ->withCount(['pelayanan as total_layanan' => function ($query) use ($startDateCarbon, $endDateCarbon, $petugasId) {
                $query->whereBetween('pelayanan.created_at', [$startDateCarbon, $endDateCarbon]);
                if ($petugasId && $petugasId !== 'all') {
                    $query->where('petugas_id', $petugasId);
                }
                $query->where('status_penyelesaian', 'selesai');
            }])
            ->having('total_layanan', '>', 0)
            ->orderBy('total_layanan', 'desc')
            ->get();

        // === BAGIAN 3: DATA SURVEI KEPUASAN ===
        $querySurvei = SurveyInternal::query()
            ->join('pelayanan', 'survey_internals.pelayanan_id', '=', 'pelayanan.id')
            ->join('users', 'pelayanan.petugas_id', '=', 'users.id')
            ->whereBetween('survey_internals.created_at', [$startDateCarbon, $endDateCarbon]);

        if ($petugasId && $petugasId !== 'all') {
            $querySurvei->where('pelayanan.petugas_id', $petugasId);
        }

        $ratingTahunan = (clone $querySurvei)
            ->select(
                DB::raw('YEAR(survey_internals.created_at) as tahun'),
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('AVG(skor_keseluruhan) as avg_rating_layanan'),
                DB::raw('AVG(skor_petugas) as avg_rating_petugas')
            )
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        // === BAGIAN 4: DATA PENGUNJUNG UNTUK CHARTS ===
        $baseQueryPelayanan = (clone $queryPelayanan);

        $chartPengunjung = [
            'menurut_pendidikan' => (clone $baseQueryPelayanan)
                ->select('pendidikan', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('pendidikan')->where('pendidikan', '!=', '')
                ->groupBy('pendidikan')->pluck('jumlah', 'pendidikan'),

            'menurut_jenis_kelamin' => (clone $baseQueryPelayanan)
                ->select('jenis_kelamin', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('jenis_kelamin')->where('jenis_kelamin', '!=', '')
                ->groupBy('jenis_kelamin')->pluck('jumlah', 'jenis_kelamin'),

            'menurut_jenis_layanan' => (clone $baseQueryPelayanan)
                ->join('jenis_layanan', 'pelayanan.jenis_layanan_id', '=', 'jenis_layanan.id')
                ->select('jenis_layanan.nama_layanan', DB::raw('COUNT(pelayanan.id) as jumlah'))
                ->groupBy('jenis_layanan.nama_layanan')->pluck('jumlah', 'nama_layanan'),
        ];

        // === BAGIAN 5: DATA TAMBAHAN ===
        $dateRangeDisplay = $startDateCarbon->locale('id')->translatedFormat('d F Y') . ' - ' .
            $endDateCarbon->locale('id')->translatedFormat('d F Y');
        $daysDiff = $startDateCarbon->diffInDays($endDateCarbon) + 1;

        // 🔹 Ambil semua petugas untuk dropdown filter
        $daftarPetugas = User::where('role_id', 2)->orderBy('nama_lengkap')->get();

        return view('admin.dashboard', compact(
            'totalPelayananSelesai',
            'totalPelayananAll',
            'totalPelayananProses',
            'persentasePenyelesaian',
            'kinerjaPetugas',
            'ratingTahunan',
            'chartPengunjung',
            'startDate',
            'endDate',
            'dateRangeDisplay',
            'daysDiff',
            'daftarPetugas',
            'petugasId'
        ));
    }
}
