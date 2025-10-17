<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\JadwalSwap;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\JadwalDitukar; // TAMBAHKAN INI
use Illuminate\Support\Facades\Notification; // TAMBAHKAN INI

class PetugasJadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('jadwal.index', compact('user'));
    }

    public function events(Request $request)
    {
        $user = Auth::user();
        $start = $request->get('start');
        $end = $request->get('end');
        $month = $request->get('month');
        $year = $request->get('year');

        $query = Jadwal::with('user')
            ->where('user_id', $user->id);

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($month && $year) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            $query->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }

        $jadwals = $query->get();

        $events = [];

        foreach ($jadwals as $jadwal) {
            $startTime = $jadwal->shift === 'pagi' ? '08:00' : '11:30';
            $endTime = $jadwal->shift === 'pagi' ? '11:30' : '15:30';

            $events[] = [
                'id' => $jadwal->id,
                'title' => $user->nama_lengkap,
                'start' => $jadwal->tanggal . 'T' . $startTime,
                'end' => $jadwal->tanggal . 'T' . $endTime,
                'backgroundColor' => '#f97316',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'jadwal_id' => $jadwal->id,
                    'userId' => $user->id,
                    'userName' => $user->nama_lengkap,
                    'petugas' => $user->nama_lengkap,
                    'shift' => $jadwal->shift,
                    'tanggal' => $jadwal->tanggal
                ]
            ];
        }

        return response()->json($events);
    }

    private function getUserColor($userId)
    {
        $colors = [
            '#3B82F6',
            '#EF4444',
            '#10B981',
            '#F59E0B',
            '#8B5CF6',
            '#06B6D4',
            '#84CC16',
            '#F97316',
            '#EC4899',
            '#6366F1'
        ];
        return $colors[$userId % count($colors)];
    }

    public function getAvailablePetugas(Request $request)
    {
        try {
            Log::info('=== DEBUG AVAILABLE PETUGAS START ===');
            Log::info('Request data:', $request->all());

            // Validasi
            if (!$request->has('tanggal') || !$request->has('shift') || !$request->has('exclude_jadwal')) {
                Log::error('Missing required parameters');
                return response()->json(['error' => 'Parameter tidak lengkap'], 400);
            }

            $tanggal = $request->tanggal;
            $shift = $request->shift;
            $excludeJadwalId = $request->exclude_jadwal;

            Log::info('Parameters received:', [
                'tanggal' => $tanggal,
                'shift' => $shift,
                'exclude_jadwal' => $excludeJadwalId
            ]);

            // Cari jadwal asal
            $jadwalAsal = Jadwal::find($excludeJadwalId);

            if (!$jadwalAsal) {
                Log::error('Jadwal asal tidak ditemukan: ' . $excludeJadwalId);
                return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
            }

            Log::info('Jadwal asal found:', ['id' => $jadwalAsal->id, 'user_id' => $jadwalAsal->user_id]);

            // Cari SEMUA petugas PST yang aktif
            $availableUsers = User::where('role_id', 2) // PST
                ->where('is_active', true)
                ->where('id', '!=', $jadwalAsal->user_id) // exclude current user
                ->whereNotNull('nama_lengkap') // pastikan nama tidak null
                ->get();

            Log::info('Available PST users found: ' . $availableUsers->count());
            Log::info('PST Users details:', $availableUsers->pluck('id', 'nama_lengkap')->toArray());

            // Process results
            $result = [];
            foreach ($availableUsers as $user) {
                // Cek apakah user ini sudah memiliki jadwal di tanggal yang sama
                $existingJadwal = Jadwal::where('user_id', $user->id)
                    ->where('tanggal', $tanggal)
                    ->where('shift', $shift)
                    ->first();

                $userData = [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'email' => $user->email,
                    'jadwal_id' => $existingJadwal ? $existingJadwal->id : null,
                    'shift' => $shift,
                    'tanggal' => $tanggal,
                    'status' => $existingJadwal ? 'sudah_ada_jadwal' : 'tersedia'
                ];

                Log::info('Processed user:', $userData);
                $result[] = $userData;
            }

            Log::info('Final available petugas count: ' . count($result));
            Log::info('Final result:', $result);
            Log::info('=== DEBUG AVAILABLE PETUGAS END ===');

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR in getAvailablePetugas: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Terjadi kesalahan sistem',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function submitSwapRequest(Request $request)
    // {
    //     $request->validate([
    //         'jadwal_asal_id' => 'required|exists:jadwal,id',
    //         'petugas_tujuan_id' => 'required|exists:users,id'
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $user = Auth::user();
    //         $jadwalAsal = Jadwal::findOrFail($request->jadwal_asal_id);
    //         $petugasTujuan = User::findOrFail($request->petugas_tujuan_id);

    //         // Validasi kepemilikan jadwal
    //         if ($jadwalAsal->user_id !== $user->id) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Anda tidak memiliki akses untuk menukar jadwal ini.'
    //             ], 403);
    //         }

    //         // Format tanggal untuk notifikasi
    //         $tanggalFormatted = Carbon::parse($jadwalAsal->tanggal)->translatedFormat('l, d F Y');
    //         $shift = $jadwalAsal->shift;

    //         // Cari jadwal tujuan yang sudah ada
    //         $jadwalTujuan = Jadwal::where('user_id', $petugasTujuan->id)
    //             ->where('tanggal', $jadwalAsal->tanggal)
    //             ->where('shift', $jadwalAsal->shift)
    //             ->first();

    //         if ($jadwalTujuan) {
    //             // Jika petugas tujuan sudah punya jadwal, langsung tukar
    //             $tempUserAsal = $jadwalAsal->user_id;
    //             $tempUserTujuan = $jadwalTujuan->user_id;

    //             // Tukar jadwal
    //             $jadwalAsal->user_id = $tempUserTujuan;
    //             $jadwalTujuan->user_id = $tempUserAsal;

    //             $jadwalAsal->save();
    //             $jadwalTujuan->save();

    //             $message = "Jadwal berhasil ditukar dengan {$petugasTujuan->nama_lengkap}";
    //         } else {
    //             // Jika petugas tujuan belum punya jadwal, assign jadwal baru
    //             $jadwalAsal->user_id = $petugasTujuan->id;
    //             $jadwalAsal->save();

    //             $message = "Jadwal berhasil dialihkan ke {$petugasTujuan->nama_lengkap}";
    //         }

    //         // ✅ KIRIM NOTIFIKASI EMAIL
    //         try {
    //             $petugasTujuan->notify(new JadwalDitukar(
    //                 $jadwalAsal,
    //                 $user, // petugas asal
    //                 $petugasTujuan, // petugas tujuan  
    //                 $tanggalFormatted,
    //                 $shift
    //             ));

    //             Log::info('Notifikasi email berhasil dikirim ke: ' . $petugasTujuan->email);
    //         } catch (\Exception $e) {
    //             Log::error('Gagal mengirim email notifikasi: ' . $e->getMessage());
    //             // Jangan rollback transaction hanya karena gagal kirim email
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => $message,
    //             'data' => [
    //                 'jadwal_asal' => $jadwalAsal,
    //                 'petugas_tujuan' => $petugasTujuan
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error submitting swap request: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat memproses permintaan tukar jadwal.'
    //         ], 500);
    //     }
    // }
    public function submitSwapRequest(Request $request)
    {
        $request->validate([
            'jadwal_asal_id' => 'required|exists:jadwal,id',
            'petugas_tujuan_id' => 'required|exists:users,id'
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $jadwalAsal = Jadwal::findOrFail($request->jadwal_asal_id);
            $petugasTujuan = User::findOrFail($request->petugas_tujuan_id);

            // Debug log untuk cek data
            Log::info('Swap Request Debug:', [
                'current_user_id' => $user->id,
                'jadwal_asal_user_id' => $jadwalAsal->user_id,
                'jadwal_asal_id' => $jadwalAsal->id,
                'petugas_tujuan_id' => $petugasTujuan->id,
                'request_data' => $request->all()
            ]);

            // Validasi kepemilikan jadwal - PERBAIKAN DI SINI
            // Konversi ke integer untuk memastikan perbandingan yang benar
            if ((int)$jadwalAsal->user_id !== (int)$user->id) {
                Log::error('Access denied - User mismatch:', [
                    'jadwal_user_id' => $jadwalAsal->user_id,
                    'jadwal_user_id_type' => gettype($jadwalAsal->user_id),
                    'current_user_id' => $user->id,
                    'current_user_id_type' => gettype($user->id)
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menukar jadwal ini.'
                ], 403);
            }

            // Cari jadwal tujuan yang sudah ada
            $jadwalTujuan = Jadwal::where('user_id', $petugasTujuan->id)
                ->where('tanggal', $jadwalAsal->tanggal)
                ->where('shift', $jadwalAsal->shift)
                ->first();

            if ($jadwalTujuan) {
                // Jika petugas tujuan sudah punya jadwal, langsung tukar
                $tempUserAsal = $jadwalAsal->user_id;
                $tempUserTujuan = $jadwalTujuan->user_id;

                // Tukar jadwal
                $jadwalAsal->user_id = $tempUserTujuan;
                $jadwalTujuan->user_id = $tempUserAsal;

                $jadwalAsal->save();
                $jadwalTujuan->save();

                Log::info('Jadwal berhasil ditukar:', [
                    'jadwal_asal_id' => $jadwalAsal->id,
                    'jadwal_tujuan_id' => $jadwalTujuan->id,
                    'user_asal' => $tempUserAsal,
                    'user_tujuan' => $tempUserTujuan
                ]);

                $message = "Jadwal berhasil ditukar dengan {$petugasTujuan->nama_lengkap}";
            } else {
                // Jika petugas tujuan belum punya jadwal, assign jadwal baru
                $jadwalAsal->user_id = $petugasTujuan->id;
                $jadwalAsal->save();

                Log::info('Jadwal berhasil dialihkan:', [
                    'jadwal_id' => $jadwalAsal->id,
                    'dari_user' => $user->id,
                    'ke_user' => $petugasTujuan->id
                ]);

                $message = "Jadwal berhasil dialihkan ke {$petugasTujuan->nama_lengkap}";
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting swap request: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan tukar jadwal.'
            ], 500);
        }
    }
}
