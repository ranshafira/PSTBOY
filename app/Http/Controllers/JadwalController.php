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

    // Proses generate jadwal
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

            // Hapus jadwal yang sudah ada untuk bulan dan tahun yang dipilih
            $startDate = "$tahun-$bulan-01";
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

            Jadwal::whereBetween('tanggal', [$startDate, $endDate])->delete();

            // Inisialisasi array untuk menghitung jumlah shift per user
            $userShiftCount = [];
            foreach ($petugas as $user) {
                $userShiftCount[$user->id] = [
                    'pagi' => 0,
                    'siang' => 0,
                    'total' => 0
                ];
            }

            $jadwalData = [];
            $workingDays = [];

            $start = Carbon::create($tahun, $bulan, 1);
            $end = $start->copy()->endOfMonth();

            // Kumpulkan semua hari kerja terlebih dahulu
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->isWeekend() || in_array($date->format('Y-m-d'), $libur)) {
                    continue;
                }
                $workingDays[] = $date->format('Y-m-d');
            }

            // Proses untuk shift pagi
            foreach ($workingDays as $currentDate) {
                $date = Carbon::parse($currentDate);
                $isJumat = $date->dayOfWeek === Carbon::FRIDAY;

                // Pilih user dengan jumlah shift pagi paling sedikit
                $eligibleUsers = $petugas->shuffle()->sortBy(function ($user) use ($userShiftCount) {
                    return $userShiftCount[$user->id]['pagi'];
                });

                $selectedUser = $eligibleUsers->first();

                Jadwal::create([
                    'user_id' => $selectedUser->id,
                    'tanggal' => $currentDate,
                    'shift' => 'pagi'
                ]);

                // Update counter
                $userShiftCount[$selectedUser->id]['pagi']++;
                $userShiftCount[$selectedUser->id]['total']++;

                // Pilih user dengan jumlah shift siang paling sedikit, tapi bukan yang sudah terjadwal pagi
                $eligibleUsers = $petugas->shuffle()->filter(function ($user) use ($selectedUser) {
                    return $user->id != $selectedUser->id;
                })->sortBy(function ($user) use ($userShiftCount) {
                    return $userShiftCount[$user->id]['siang'];
                });

                $selectedUser = $eligibleUsers->first();

                Jadwal::create([
                    'user_id' => $selectedUser->id,
                    'tanggal' => $currentDate,
                    'shift' => 'siang'
                ]);

                // Update counter
                $userShiftCount[$selectedUser->id]['siang']++;
                $userShiftCount[$selectedUser->id]['total']++;
            }

            // Hitung statistik distribusi untuk log
            $stats = [];
            foreach ($userShiftCount as $userId => $counts) {
                $userName = $petugas->where('id', $userId)->first()->nama_lengkap;
                $stats[] = "$userName: {$counts['pagi']} pagi, {$counts['siang']} siang, {$counts['total']} total";
            }

            Log::info('Jadwal generated with distribution: ' . implode(' | ', $stats));

            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat dengan distribusi seimbang.');
        } catch (\Exception $e) {
            Log::error('Error generating jadwal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $jadwal = Jadwal::with('user')->findOrFail($id);
        $users = User::where('role_id', 2)->get();
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

    public function getEvents(Request $request)
    {
        try {
            // Filter jadwal berdasarkan bulan dan tahun yang dipilih
            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));

            $query = Jadwal::with('user');

            // Jika bulan dan tahun disediakan, filter jadwal
            if ($month && $year) {
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
            }

            $jadwal = $query->get();

            $events = [];

            foreach ($jadwal as $item) {
                $start = Carbon::parse($item->tanggal);
                $end = Carbon::parse($item->tanggal);
                $isJumat = $start->dayOfWeek === Carbon::FRIDAY;

                // Tentukan waktu berdasarkan shift
                if ($item->shift === 'pagi') {
                    $start->setTime(8, 0); // 08:00
                    $end->setTime(11, 30); // 11:30
                } else { // shift siang
                    $start->setTime(11, 30); // 11:30
                    $end->setTime(15, 30); // 15:30
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => $item->user->nama_lengkap . ' (' . ucfirst($item->shift) . ')',
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

    // Di JadwalController atau controller terkait
    public function events(Request $request)
    {
        try {
            // Filter jadwal berdasarkan bulan dan tahun yang dipilih
            $month = $request->input('month', date('m'));
            $year = $request->input('year', date('Y'));
            $startDate = $request->get('start'); // Parameter baru
            $endDate = $request->get('end');     // Parameter baru

            $query = Jadwal::with('user');

            if ($startDate && $endDate) {
                // Gunakan range tanggal untuk query yang lebih akurat
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            } elseif ($month && $year) {
                // Filter berdasarkan bulan dan tahun
                $query->whereMonth('tanggal', $month)
                    ->whereYear('tanggal', $year);
            }

            $jadwal = $query->get();

            $events = [];

            foreach ($jadwal as $item) {
                $start = Carbon::parse($item->tanggal);
                $end = Carbon::parse($item->tanggal);

                // Tentukan waktu berdasarkan shift
                if ($item->shift === 'pagi') {
                    $start->setTime(8, 0); // 08:00
                    $end->setTime(11, 30);  // 11:30
                } else { // shift siang
                    $start->setTime(11, 30); // 11:30
                    $end->setTime(15, 0); // 15:00
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => $item->user->nama_lengkap . ' (' . ucfirst($item->shift) . ')',
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

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error in events: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    //Method untuk MENAMPILKAN HALAMAN jadwal khusus petugas
    public function indexPetugas()
    {
        // Kita hanya perlu mengirim user yang sedang login ke view
        $user = auth()->user();

        // Pastikan Anda punya view di resources/views/jadwal/index.blade.php
        return view('jadwal.index', compact('user'));
    }

    public function eventsPetugas(Request $request)
    {
        try {
            $userId = auth()->id(); // Mengambil ID petugas yang sedang login

            $query = Jadwal::with('user')
                ->where('user_id', $userId); // <-- INI BAGIAN PALING PENTING

            // Filter berdasarkan bulan dan tahun dari request
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
                } else { // shift siang
                    $start->setTime(11, 30);
                    $end->setTime(15, 30);
                }

                $color = $this->getUserColor($item->user_id, $item->shift);

                $events[] = [
                    'id' => $item->id,
                    'title' => 'Jadwal Anda (' . ucfirst($item->shift) . ')', // Judul lebih simpel
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
}
