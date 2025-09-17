<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PetugasJadwalController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan user yang login adalah petugas
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
            ->where('user_id', $user->id); // Hanya jadwal petugas yang login

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($month && $year) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            $query->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }

        $jadwals = $query->get();

        $events = [];
        $userColor = $this->getUserColor($user->id);

        foreach ($jadwals as $jadwal) {
            $startTime = $jadwal->shift === 'pagi' ? '08:00' : '11:30';
            $endTime = $jadwal->shift === 'pagi' ? '11:30' : '15:30';

            $events[] = [
                'id' => $jadwal->id,
                'title' => $user->name . ' (' . ucfirst($jadwal->shift) . ')',
                'start' => $jadwal->tanggal . 'T' . $startTime,
                'end' => $jadwal->tanggal . 'T' . $endTime,
                'backgroundColor' => $jadwal->shift === 'pagi' ? '#5cc84eff' : '#f29e45ff',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'jadwal_id' => $jadwal->id,
                    'userId' => $user->id,
                    'userName' => $user->name,
                    'petugas' => $user->name,
                    'shift' => $jadwal->shift,
                    'tanggal' => $jadwal->tanggal
                ]
            ];
        }

        return response()->json($events);
    }

    private function getUserColor($userId)
    {
        // Generate consistent color based on user ID
        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];
        return $colors[$userId % count($colors)];
    }
}