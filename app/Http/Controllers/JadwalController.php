<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\JadwalDiubahNotification;
use App\Notifications\JadwalDihapusNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use App\Helpers\FonnteHelper;


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

    // Proses generate jadwal dengan pengecekan hari libur
    public function generateJadwal(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Validasi input
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2022|max:2099',
        ]);

        try {
            $petugas = User::where('role_id', 2)->where('is_active', true)->get();
            if ($petugas->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada petugas ditemukan.');
            }

            // Ambil hari libur menggunakan helper
            $libur = getHolidaysIndonesia($tahun);

            // DEBUG: Log hari libur yang didapat
            Log::info("=== DEBUG GENERATE JADWAL ===");
            Log::info("Tahun: {$tahun}, Bulan: {$bulan}");
            Log::info("Total hari libur dari API: " . count($libur));
            Log::info("Daftar hari libur: " . json_encode($libur));

            // Hapus jadwal yang sudah ada
            $startDate = "$tahun-$bulan-01";
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

            $deletedCount = Jadwal::whereBetween('tanggal', [$startDate, $endDate])->delete();
            Log::info("Deleted {$deletedCount} existing schedules");

            // Inisialisasi counter
            $userShiftCount = [];
            foreach ($petugas as $user) {
                $userShiftCount[$user->id] = [
                    'pagi' => 0,
                    'siang' => 0,
                    'total' => 0
                ];
            }

            $workingDays = [];
            $skippedDays = [];

            $start = Carbon::create($tahun, $bulan, 1);
            $end = $start->copy()->endOfMonth();

            // Loop untuk identifikasi hari kerja
            Log::info("=== ANALYZING DAYS ===");
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $dayName = $date->locale('id')->isoFormat('dddd, D MMMM Y');

                // Cek apakah weekend
                $isWeekend = $date->isWeekend();

                // Cek apakah hari libur nasional
                $isNationalHoliday = in_array($dateStr, $libur);

                // DEBUG: Log setiap hari
                Log::info("Checking {$dateStr} ({$dayName}): Weekend={$isWeekend}, NationalHoliday={$isNationalHoliday}");

                if ($isWeekend || $isNationalHoliday) {
                    $reason = $isWeekend ? 'Weekend' : 'Hari Libur Nasional';
                    if ($isNationalHoliday) {
                        $holidayName = getHolidayName($date);
                        $reason .= " ({$holidayName})";
                    }

                    $skippedDays[] = [
                        'date' => $dateStr,
                        'day' => $dayName,
                        'reason' => $reason
                    ];

                    Log::info("  → SKIPPED: {$reason}");
                } else {
                    $workingDays[] = $dateStr;
                    Log::info("  → WORKING DAY");
                }
            }

            Log::info("=== SUMMARY ===");
            Log::info("Total days in month: " . $end->day);
            Log::info("Working days: " . count($workingDays));
            Log::info("Skipped days: " . count($skippedDays));

            // Jika tidak ada hari kerja sama sekali
            if (empty($workingDays)) {
                return redirect()->back()->with('error', 'Tidak ada hari kerja di bulan ini (semua hari adalah weekend atau libur).');
            }

            // Generate jadwal untuk hari kerja
            Log::info("=== GENERATING SCHEDULES ===");
            $generatedCount = 0;

            foreach ($workingDays as $currentDate) {
                $date = Carbon::parse($currentDate);
                $dayName = $date->locale('id')->isoFormat('dddd, D MMMM Y');

                Log::info("Generating for {$currentDate} ({$dayName})");

                // Shift Pagi
                $eligibleUsers = $petugas->shuffle()->sortBy(function ($user) use ($userShiftCount) {
                    return $userShiftCount[$user->id]['pagi'];
                });

                $selectedUserPagi = $eligibleUsers->first();

                Jadwal::create([
                    'user_id' => $selectedUserPagi->id,
                    'tanggal' => $currentDate,
                    'shift' => 'pagi'
                ]);

                $userShiftCount[$selectedUserPagi->id]['pagi']++;
                $userShiftCount[$selectedUserPagi->id]['total']++;
                $generatedCount++;

                Log::info("  → Pagi: {$selectedUserPagi->nama_lengkap}");

                // Shift Siang (pastikan beda dengan pagi)
                $eligibleUsers = $petugas->shuffle()
                    ->filter(function ($user) use ($selectedUserPagi) {
                        return $user->id != $selectedUserPagi->id;
                    })
                    ->sortBy(function ($user) use ($userShiftCount) {
                        return $userShiftCount[$user->id]['siang'];
                    });

                $selectedUserSiang = $eligibleUsers->first();

                Jadwal::create([
                    'user_id' => $selectedUserSiang->id,
                    'tanggal' => $currentDate,
                    'shift' => 'siang'
                ]);

                $userShiftCount[$selectedUserSiang->id]['siang']++;
                $userShiftCount[$selectedUserSiang->id]['total']++;
                $generatedCount++;

                Log::info("  → Siang: {$selectedUserSiang->nama_lengkap}");
            }

            // Log distribusi final
            Log::info("=== DISTRIBUTION ===");
            foreach ($userShiftCount as $userId => $counts) {
                $userName = $petugas->where('id', $userId)->first()->nama_lengkap;
                Log::info("{$userName}: Pagi={$counts['pagi']}, Siang={$counts['siang']}, Total={$counts['total']}");
            }

            Log::info("=== GENERATION COMPLETE ===");
            Log::info("Total schedules generated: {$generatedCount}");

            // Buat pesan sukses
            $monthName = Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
            $message = "✅ Jadwal berhasil dibuat untuk bulan {$monthName} {$tahun}!\n\n";
            $message .= "📊 Total hari kerja: " . count($workingDays) . " hari\n";
            $message .= "🚫 Hari libur/weekend: " . count($skippedDays) . " hari\n";
            $message .= "📝 Total jadwal dibuat: {$generatedCount} shift";

            return redirect()->route('admin.jadwal.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('=== ERROR GENERATING JADWAL ===');
            Log::error('Error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $jadwal = Jadwal::with('user')->findOrFail($id);
        $users = User::where('role_id', 2)->where('is_active', true)->get();
        return view('admin.jadwal.edit', compact('jadwal', 'users'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userLama = $jadwal->user; // Petugas lama
        $userBaru = User::findOrFail($request->user_id); // Petugas baru

        // Update jadwal
        $jadwal->user_id = $request->user_id;
        $jadwal->save();

        // Kirim notifikasi hanya jika ada perubahan petugas
        if ($userLama->id !== $userBaru->id) {
            try {
                // Kirim notifikasi email terlebih dahulu
                $userLama->notify(new JadwalDiubahNotification($jadwal, 'lama'));
                $userBaru->notify(new JadwalDiubahNotification($jadwal, 'baru'));
                Log::info('Email notifications sent successfully');

                // Kirim notifikasi WhatsApp
                $whatsappResult = $this->sendWhatsAppNotifications($userLama, $userBaru, $jadwal);

                if ($whatsappResult) {
                    $message = 'Jadwal berhasil diperbarui dan semua notifikasi (email + WhatsApp) telah dikirim.';
                } else {
                    $message = 'Jadwal berhasil diperbarui dan email terkirim, tetapi WhatsApp gagal dikirim.';
                }
            } catch (\Exception $e) {
                Log::error('Error sending notifications: ' . $e->getMessage());
                return redirect()->route('admin.jadwal.edit', $jadwal->id)
                    ->with('warning', 'Jadwal berhasil diperbarui, tetapi ada masalah saat mengirim notifikasi: ' . $e->getMessage());
            }
        } else {
            $message = 'Jadwal berhasil diperbarui (tidak ada perubahan petugas).';
        }

        return redirect()->route('admin.jadwal.edit', $jadwal->id)->with('success', $message);
    }

    /**
     * Kirim notifikasi WhatsApp untuk update jadwal
     */
    private function sendWhatsAppNotifications($userLama, $userBaru, $jadwal)
    {
        try {
            $tanggal = \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
            $shift = ucfirst($jadwal->shift);

            // Validasi nomor HP
            if (empty($userLama->no_hp) || empty($userBaru->no_hp)) {
                Log::warning('Nomor HP tidak lengkap', [
                    'user_lama' => $userLama->no_hp,
                    'user_baru' => $userBaru->no_hp
                ]);
                return false;
            }

            // Pesan untuk petugas lama
            $messageLama = <<<EOT
🔄 *PERUBAHAN JADWAL TUGAS*

Halo {$userLama->nama_lengkap},

Jadwal tugas Anda telah diubah:

📅 Tanggal: {$tanggal}
⏰ Shift: {$shift}
📋 Status: Digantikan oleh petugas lain

Terima kasih atas pengertiannya.

_PST Kabupaten Boyolali_
EOT;

            // Pesan untuk petugas baru
            $messageBaru = <<<EOT
✅ *PENUGASAN JADWAL BARU*

Halo {$userBaru->nama_lengkap},

Anda mendapat penugasan baru:

📅 Tanggal: {$tanggal}
⏰ Shift: {$shift}
📋 Status: Petugas Bertugas

Mohon hadir sesuai jadwal.

_PST Kabupaten Boyolali_
EOT;

            // Kirim pesan WhatsApp
            Log::info('Mengirim WhatsApp ke petugas lama: ' . $userLama->nama_lengkap);
            $resultLama = FonnteHelper::sendMessage($userLama->no_hp, $messageLama);

            Log::info('Mengirim WhatsApp ke petugas baru: ' . $userBaru->nama_lengkap);
            $resultBaru = FonnteHelper::sendMessage($userBaru->no_hp, $messageBaru);

            // Log hasil
            Log::info('WhatsApp Results:', [
                'petugas_lama' => $resultLama ? 'SUCCESS' : 'FAILED',
                'petugas_baru' => $resultBaru ? 'SUCCESS' : 'FAILED'
            ]);

            return ($resultLama && $resultBaru);
        } catch (\Exception $e) {
            Log::error('Error in sendWhatsAppNotifications: ' . $e->getMessage());
            return false;
        }
    }


    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $petugas = $jadwal->user;

            if ($petugas) {
                // Kirim notifikasi WA via FonnteHelper
                $tanggal = \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
                $shift = ucfirst($jadwal->shift);

                $no_hp = preg_replace('/[^0-9]/', '', $petugas->no_hp);

                $message = <<<EOT
[Pemberitahuan Penghapusan Jadwal]

Halo, {$petugas->nama_lengkap}

Kami informasikan bahwa jadwal tugas Anda telah dihapus dengan rincian berikut:

Tanggal : {$tanggal}
Shift   : {$shift}

Jika ada pertanyaan, silakan hubungi admin PST Kabupaten Boyolali.

Terima kasih atas perhatian Anda.

PST Kabupaten Boyolali
EOT;

                FonnteHelper::sendMessage($no_hp, $message);
            }

            $jadwal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus dan notifikasi telah dikirim ke petugas.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Generate a unique soft color based on user ID
     * 
     * @param int $userId
     * @param string $shift
     * @return string
     */
    private function getUserColor($userId, $shift)
    {
        // Palet warna yang jelas, tegas, dan menarik untuk semua user
        $userPalette = [
            '#FF5252', // Merah terang
            '#448AFF', // Biru terang
            '#66BB6A', // Hijau terang
            '#AB47BC', // Ungu
            '#FFA726', // Oranye
            '#26C6DA', // Cyan
            '#EC407A', // Pink
            '#7E57C2', // Ungu kebiruan
            '#5C6BC0', // Indigo
            '#42A5F5', // Biru langit
            '#26A69A', // Teal
            '#9CCC65', // Lime
            '#FFCA28', // Amber
            '#FF7043', // Deep Orange
            '#8D6E63', // Brown
            '#78909C', // Blue Grey
            '#F06292', // Pink muda
            '#7986CB', // Indigo muda
            '#4DB6AC', // Teal muda
            '#FFA000', // Amber gelap
            '#D81B60', // Pink gelap
            '#5E35B1', // Deep Purple
            '#00ACC1', // Cyan gelap
            '#43A047', // Hijau gelap
            '#C0CA33', // Lime gelap
            '#039BE5', // Light Blue
            '#00897B', // Teal gelap
            '#3949AB', // Indigo gelap
            '#E53935', // Merah gelap
            '#8E24AA', // Ungu gelap
        ];

        // Gunakan user ID sebagai indeks untuk memastikan setiap user mendapat warna yang konsisten
        // dan tidak ada user yang mendapat warna yang sama
        $colorIndex = ($userId - 1) % count($userPalette);

        // Kembalikan warna yang sama terlepas dari shift untuk konsistensi
        return $userPalette[$colorIndex];
    }

    // Update juga method getEvents dengan cara yang sama
    public function getEvents(Request $request)
    {
        try {
            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));

            $query = Jadwal::with('user');

            if ($month && $year) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
            }

            $jadwal = $query->get();

            $events = [];

            foreach ($jadwal as $item) {
                $start = Carbon::parse($item->tanggal);
                $end = Carbon::parse($item->tanggal);

                if ($item->shift === 'pagi') {
                    $start->setTime(8, 0);
                    $end->setTime(11, 30);
                } else {
                    $start->setTime(11, 30);
                    $end->setTime(15, 30);
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => $item->user->nama_lengkap,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => 'white',
                    'extendedProps' => [
                        'shift' => $item->shift,
                        'petugas' => $item->user->nama_lengkap,
                        'tanggal' => $item->tanggal,
                        'jadwal_id' => $item->id,
                        'userId' => $item->user_id,
                        'userName' => $item->user->nama_lengkap
                    ]
                ];
            }

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error in getEvents: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    // PERBAIKAN: Tambah marker hari libur di calendar
    public function events(Request $request)
    {
        try {
            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));
            $startDate = $request->get('start');
            $endDate = $request->get('end');

            $query = Jadwal::with('user');

            if ($startDate && $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($month && $year) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
            }

            $jadwal = $query->get();

            $events = [];

            // Tambahkan event jadwal petugas
            foreach ($jadwal as $item) {
                $start = Carbon::parse($item->tanggal);
                $end = Carbon::parse($item->tanggal);

                if ($item->shift === 'pagi') {
                    $start->setTime(8, 0);
                    $end->setTime(11, 30);
                } else {
                    $start->setTime(11, 30);
                    $end->setTime(15, 30);
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => $item->user->nama_lengkap,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'jadwal_id' => $item->id,
                        'userId' => $item->user_id,
                        'userName' => $item->user->nama_lengkap,
                        'shift' => $item->shift,
                        'petugas' => $item->user->nama_lengkap
                    ]
                ];
            }

            // TAMBAHAN: Tambahkan marker untuk hari libur nasional
            if ($month && $year) {
                $holidays = getHolidaysIndonesia($year);

                foreach ($holidays as $holidayDate) {
                    $date = Carbon::parse($holidayDate);

                    // Hanya tampilkan jika dalam bulan yang dipilih
                    if ($date->month == $month) {
                        $holidayName = getHolidayName($date);

                        $events[] = [
                            'id' => 'holiday_' . $holidayDate,
                            'title' => '🏖️ ' . $holidayName,
                            'start' => $date->format('Y-m-d'),
                            'end' => $date->format('Y-m-d'),
                            'display' => 'background',
                            'backgroundColor' => '#FEE2E2',
                            'borderColor' => '#EF4444',
                            'extendedProps' => [
                                'type' => 'holiday',
                                'holiday_name' => $holidayName,
                            ]
                        ];
                    }
                }
            }

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error in events: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    //Method untuk MENAMPILKAN HALAMAN jadwal khusus petugas
    public function indexPetugas()
    {
        $user = auth()->user();
        return view('jadwal.index', compact('user'));
    }

    public function eventsPetugas(Request $request)
    {
        try {
            $userId = auth()->id();

            $query = Jadwal::with('user')
                ->where('user_id', $userId);

            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));

            if ($month && $year) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
            }

            $jadwal = $query->get();

            $events = [];
            foreach ($jadwal as $item) {
                $start = Carbon::parse($item->tanggal);
                $end = Carbon::parse($item->tanggal);

                if ($item->shift === 'pagi') {
                    $start->setTime(8, 0);
                    $end->setTime(11, 30);
                } else {
                    $start->setTime(11, 30);
                    $end->setTime(15, 30);
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => 'Jadwal Anda',
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => 'white',
                    'extendedProps' => [
                        'shift' => $item->shift,
                        'petugas' => $item->user->nama_lengkap,
                        'jadwal_id' => $item->id,
                        'userId' => $item->user_id,
                        'userName' => $item->user->nama_lengkap
                    ]
                ];
            }

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error in eventsPetugas: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat jadwal Anda'], 500);
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('m'));
            $tahun = $request->input('tahun', date('Y'));

            // Validasi input
            if (!is_numeric($bulan) || $bulan < 1 || $bulan > 12) {
                return redirect()->back()->with('error', 'Bulan tidak valid.');
            }

            if (!is_numeric($tahun) || $tahun < 2000 || $tahun > 2100) {
                return redirect()->back()->with('error', 'Tahun tidak valid.');
            }

            // Query jadwal berdasarkan filter
            $jadwal = Jadwal::with('user')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc')
                ->orderBy('shift', 'asc')
                ->get();

            // Nama file CSV
            $fileName = 'jadwal_pst_' . $bulan . '_' . $tahun . '.csv';

            // Headers untuk response CSV
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Callback untuk generate CSV
            $callback = function () use ($jadwal, $bulan, $tahun) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                // Header CSV
                $monthName = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
                fputcsv($file, [
                    'JADWAL PETUGAS PST',
                    'Bulan: ' . $monthName . ' ' . $tahun
                ]);
                fputcsv($file, []); // Empty row

                // Column headers
                fputcsv($file, [
                    'No',
                    'Tanggal',
                    'Hari',
                    'Shift',
                    'Waktu',
                    'Petugas',
                    'Status'
                ]);

                // Data rows
                $counter = 1;
                foreach ($jadwal as $item) {
                    $tanggal = Carbon::parse($item->tanggal);
                    $hari = $tanggal->locale('id')->isoFormat('dddd');

                    $shift = ucfirst($item->shift);
                    $waktu = $item->shift == 'pagi' ? '08:00 - 11:30' : '11:30 - 15:30';

                    // PERBAIKAN: Gunakan helper untuk cek status hari libur
                    $status = 'Kerja';
                    if (isHoliday($tanggal)) {
                        $holidayName = getHolidayName($tanggal);
                        $status = 'Libur' . ($holidayName ? " ({$holidayName})" : '');
                    }

                    fputcsv($file, [
                        $counter++,
                        $tanggal->format('d-m-Y'),
                        $hari,
                        $shift,
                        $waktu,
                        $item->user->nama_lengkap,
                        $status
                    ]);
                }

                // Empty row
                fputcsv($file, []);

                // Summary
                fputcsv($file, ['SUMMARY:']);
                fputcsv($file, ['Total Jadwal:', count($jadwal)]);
                fputcsv($file, ['Shift Pagi:', $jadwal->where('shift', 'pagi')->count()]);
                fputcsv($file, ['Shift Siang:', $jadwal->where('shift', 'siang')->count()]);

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting CSV: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengekspor CSV: ' . $e->getMessage());
        }
    }
}
