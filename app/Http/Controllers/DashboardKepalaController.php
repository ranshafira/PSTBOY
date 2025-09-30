<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelayanan;
use App\Models\SurveyInternal; // Asumsi nama model Anda
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardKepalaController extends Controller
{
    public function index(Request $request)
    {
        // === BAGIAN 1: STATISTIK UMUM PELAYANAN ===
        $totalPelayananSelesai = Pelayanan::query()
            ->where('status_penyelesaian', 'selesai')
            ->count();


        // === BAGIAN 2: KINERJA PETUGAS (TOTAL LAYANAN) ===
        $kinerjaPetugas = User::where('role_id', 2) // Role Petugas PST
            ->withCount(['pelayanan as total_layanan' => function ($query) {
                $query->where('status_penyelesaian', 'selesai');
            }])
            ->having('total_layanan', '>', 0)
            ->orderBy('total_layanan', 'desc')
            ->get();


        // === BAGIAN 3: DATA SURVEI KEPUASAN ===
        $querySurvei = SurveyInternal::query()
            ->join('pelayanan', 'survey_internals.pelayanan_id', '=', 'pelayanan.id')
            ->join('users', 'pelayanan.petugas_id', '=', 'users.id');

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
        $chartPengunjung = [
            'menurut_pendidikan' => Pelayanan::query()
                ->select('pendidikan', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('pendidikan')->where('pendidikan', '!=', '')
                ->groupBy('pendidikan')->pluck('jumlah', 'pendidikan'),

            'menurut_jenis_kelamin' => Pelayanan::query()
                ->select('jenis_kelamin', DB::raw('COUNT(*) as jumlah'))
                ->whereNotNull('jenis_kelamin')->where('jenis_kelamin', '!=', '')
                ->groupBy('jenis_kelamin')->pluck('jumlah', 'jenis_kelamin'),

            'menurut_jenis_layanan' => Pelayanan::query()
                ->join('jenis_layanan', 'pelayanan.jenis_layanan_id', '=', 'jenis_layanan.id')
                ->select('jenis_layanan.nama_layanan', DB::raw('COUNT(pelayanan.id) as jumlah'))
                ->groupBy('jenis_layanan.nama_layanan')
                ->pluck('jumlah', 'nama_layanan'),
        ];


        // Kirim semua data yang sudah diolah ke view
        return view('admin.dashboard', compact(
            'totalPelayananSelesai',
            'kinerjaPetugas',
            'ratingTahunan',
            'chartPengunjung'
        ));
    }
}
