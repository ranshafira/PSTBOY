<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Jadwal;
use Carbon\Carbon;

class JadwalController extends Controller
{
    // Tampilkan daftar jadwal
    public function index()
    {
        $jadwal = Jadwal::with('user')->orderBy('tanggal', 'asc')->get();
        return view('admin.jadwal.index', compact('jadwal'));
    }

    // Form untuk memilih bulan & tahun
    public function generateForm()
    {
        return view('admin.jadwal.generate');
    }

    // Proses generate jadwal
    public function generateJadwal(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $petugas = User::where('role_id', 2)->get();
        if ($petugas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada petugas ditemukan.');
        }

        // Ambil data hari libur dari API
        $response = Http::get('https://api-harilibur.vercel.app/api', [
            'month' => $bulan,
            'year' => $tahun,
        ]);
        $liburData = $response->successful() ? $response->json() : [];
        $liburTanggal = array_map(fn($item) => $item['date'] ?? $item['holiday_date'] ?? null, $liburData);
        $libur = array_filter($liburTanggal);

        $start = Carbon::create($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();
        $i = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekend() || in_array($date->format('Y-m-d'), $libur)) {
                continue;
            }

            $isJumat = $date->dayOfWeek === Carbon::FRIDAY;
            $shifts = $isJumat
                ? [['pagi', '08:00', '11:30'], ['siang', '13:30', '16:30']]
                : [['pagi', '08:00', '12:00'], ['siang', '13:00', '16:00']];

            foreach ($shifts as $shiftData) {
                $user = $petugas[$i % $petugas->count()];

                Jadwal::updateOrCreate(
                    ['tanggal' => $date->format('Y-m-d'), 'shift' => $shiftData[0]],
                    ['user_id' => $user->id]
                );

                $i++;
            }
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat menggunakan data hari libur nasional.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::with('user')->findOrFail($id);
        $users = User::where('role_id', 2)->get();
        return view('admin.jadwal.edit', compact('jadwal', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'user_id' => $request->user_id
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEvents(Request $request)
    {
        try {
            $jadwal = Jadwal::with('user')->get();

            // Format data jadwal untuk FullCalendar dengan warna dan waktu yang tepat
            $events = $jadwal->map(function ($item) {
                // Tentukan waktu berdasarkan shift dan hari
                $date = Carbon::parse($item->tanggal);
                $isJumat = $date->dayOfWeek === Carbon::FRIDAY;
                
                if ($item->shift === 'pagi') {
                    $startTime = '08:00:00';
                    $endTime = $isJumat ? '11:30:00' : '12:00:00';
                    $color = '#1e88e5'; // Biru untuk shift pagi
                } else {
                    $startTime = $isJumat ? '13:30:00' : '13:00:00';
                    $endTime = '16:00:00';
                    if ($isJumat && $item->shift === 'siang') {
                        $endTime = '16:30:00';
                    }
                    $color = '#ff9800'; // Orange untuk shift siang
                }

                return [
                    'id' => $item->id,
                    'title' => $item->user->nama_lengkap . ' (' . ucfirst($item->shift) . ')',
                    'start' => $item->tanggal . 'T' . $startTime,
                    'end' => $item->tanggal . 'T' . $endTime,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => 'white',
                    'extendedProps' => [
                        'shift' => $item->shift,
                        'petugas' => $item->user->nama_lengkap,
                        'tanggal' => $item->tanggal,
                        'jadwal_id' => $item->id  // Fixed: Changed from 'id' to 'jadwal_id'
                    ]
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Error in getEvents: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }
}