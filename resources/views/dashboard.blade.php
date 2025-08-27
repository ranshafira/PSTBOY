<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan Statistik Terpadu</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bps-primary': '#002D72',
                        'bps-dark': '#001C48',
                        'bps-orange': '#ff6600',
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-bps-primary text-white min-h-screen flex flex-col">

   <header class="bg-bps-dark shadow-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        <div class="flex items-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/BPS_Logo.png" alt="BPS Logo" class="h-9 mr-3">
            <strong class="text-lg text-white hidden sm:block">BPS Kabupaten Boyolali</strong>
        </div>

        <nav class="flex items-center gap-4">
            
           

        </nav>
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 pt-20 pb-16 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang di Pelayanan PST</h1>
        <p class="text-gray-200 mb-4">Sistem antrian digital untuk pelayanan yang lebih efisien</p>

        <!-- Tanggal & Waktu -->
        <div class="text-sm text-gray-300 mb-8">
            <span id="jam"></span> • <span id="tanggal"></span>
        </div>

        <h2 class="text-xl font-semibold mb-6">Pilih Layanan yang Anda Butuhkan</h2>

        <!-- Grid Layanan -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 justify-center mb-10">
            @foreach ($jenisLayanan as $layanan)
            <form method="POST" action="{{ route('antrian.store') }}">
                @csrf
                <input type="hidden" name="jenis_layanan_id" value="{{ $layanan->id }}">
                <div class="bg-white text-bps-dark rounded-xl shadow-lg p-4 flex flex-col items-center hover:shadow-xl transition">
                    <!-- Icon -->
                    <div class="text-5xl mb-4">
                        @php
                            $iconMap = [
                                'Perpustakaan' => '📘',
                                'PST' => '👥',
                                'Konsultasi Statistik' => '📊',
                                'Rekomendasi Statistik' => '📌',
                                'Pengaduan Layanan' => '📣',
                            ];
                            $icon = $iconMap[$layanan->nama_layanan] ?? '📌';
                        @endphp
                        {{ $icon }}
                    </div>
                    <!-- Nama Layanan -->
                    <h3 class="text-lg font-bold mb-1 text-center">{{ $layanan->nama_layanan }}</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center">{{ $layanan->deskripsi ?? 'Layanan ' . $layanan->nama_layanan }}</p>
                    <button type="submit" class="bg-bps-orange text-white px-4 py-2 rounded-md font-bold hover:bg-orange-600 transition">
                        Ambil Nomor Antrian
                    </button>
                </div>
            </form>
            @endforeach
        </div>

        <!-- Informasi Pelayanan -->
        <div class="bg-white text-bps-dark p-6 rounded-lg shadow-md max-w-2xl mx-auto text-left">
            <h3 class="text-lg font-bold mb-3">Informasi Pelayanan</h3>
            <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <p><strong>Jam Operasional:</strong></p>
                    <ul class="list-disc ml-5">
                        <li>Senin - Jumat: 08:00 - 16:00</li>
                        <li>Sabtu: 08:00 - 12:00</li>
                    </ul>
                </div>
                <div>
                    <p><strong>Ketentuan Antrian:</strong></p>
                    <ul class="list-disc ml-5">
                        <li>Nomor antrian berlaku untuk hari ini</li>
                        <li>Harap datang sesuai waktu layanan</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Jika Ada Antrian -->
    @if (session('success'))
    <div x-data="{ showModal: true }"
         x-show="showModal"
         @keydown.escape.window="showModal = false"
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
         x-cloak>
        <div @click.outside="showModal = false"
             class="bg-white text-black p-8 rounded-lg shadow-2xl text-center max-w-sm mx-auto"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90">

            <h2 class="text-2xl font-bold text-gray-800 mb-2">Nomor Antrian Anda</h2>
            <p class="text-gray-600 mb-5">Silakan tunjukkan nomor ini kepada petugas.</p>

            @php
                $nomor = explode(': ', session('success'))[1] ?? '';
            @endphp

            <p class="text-6xl font-bold text-bps-orange my-4 bg-gray-100 p-4 rounded-lg">
                {{ $nomor }}
            </p>

            <button @click="showModal = false" class="mt-6 w-full bg-bps-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-bps-dark transition-colors duration-300">
                Tutup
            </button>
        </div>
    </div>
    @endif

    <!-- Script Tanggal & Jam -->
    <script>
        function updateWaktu() {
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

            const now = new Date();
            const tanggal = now.getDate();
            const namaHari = hari[now.getDay()];
            const namaBulan = bulan[now.getMonth()];
            const tahun = now.getFullYear();

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');

            document.getElementById('tanggal').textContent = `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
            document.getElementById('jam').textContent = `${jam}:${menit}`;
        }

        updateWaktu();
        setInterval(updateWaktu, 1000);
    </script>

</body>
</html>
