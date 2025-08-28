<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan Statistik Terpadu - BPS Kab. Boyolali</title>

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

    <header 
    x-data="{ shown: false }" 
    x-init="setTimeout(() => shown = true, 200)"
    class="bg-gradient-to-r from-bps-dark to-bps-primary shadow-lg sticky top-0 z-20 border-b border-white/10">
    
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        <a href="https://boyolalikab.bps.go.id/" target="_blank" rel="noopener noreferrer"
           x-show="shown" 
           x-transition:enter="transition ease-out duration-500"
           x-transition:enter-start="opacity-0 -translate-x-4"
           x-transition:enter-end="opacity-100 translate-x-0"
           class="flex items-center group">
            
            <img src="https://4.bp.blogspot.com/-w45pPrU450Q/WitcqmIyloI/AAAAAAAAF3Q/k4pgHadbWvslcDQNTxLOezOK2cOaypPSACLcBGAs/s1600/BPS.png" alt="BPS Logo" class="h-10 mr-4 group-hover:opacity-80 transition-opacity">
            <strong class="text-xl text-white hidden sm:block font-semibold group-hover:text-gray-300 transition-colors">
                BPS Kabupaten Boyolali
            </strong>
        </a>

        <nav class="flex items-center gap-3">
    @if (Route::has('login'))
        @auth
            {{-- Jika user sudah login, jangan tampilkan tombol login/register --}}
            <a href="{{ route('presensi.index') }}" 
               class="text-sm text-white font-semibold rounded-full px-5 py-2.5 
                      hover:bg-white/20 transform hover:scale-105 
                      transition-all duration-300 ease-in-out">
               Presensi
            </a>
        @else
            <a href="{{ route('login') }}" 
               class="text-sm bg-bps-orange text-white font-bold rounded-full px-5 py-2.5 
                      hover:bg-orange-500 transform hover:scale-105 
                      transition-all duration-300 ease-in-out shadow-lg hover:shadow-orange-400/50">
               Login
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" 
                   class="text-sm text-white font-semibold rounded-full px-5 py-2.5 
                          hover:bg-white/20 transform hover:scale-105 
                          transition-all duration-300 ease-in-out">
                   Register
                </a>
            @endif
        @endauth
    @endif
</nav>


        <nav 
            x-show="shown"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="flex items-center gap-3">

            <a href="{{ route('bukutamu.create') }}" 
               class="text-sm text-white font-semibold rounded-full px-5 py-2.5 
                      hover:bg-white/20 transform hover:scale-105 
                      transition-all duration-300 ease-in-out">
               Buku Tamu
            </a>

        </nav>
    </div>
</header>


    <main class="flex-grow container mx-auto px-6 pt-12 md:pt-20 pb-16">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-2">
                Sistem Antrian Digital PST
            </h1>
            <p class="text-lg text-gray-300 leading-relaxed mb-6">
                Selamat datang di Pelayanan Statistik Terpadu. Silakan pilih jenis layanan di bawah.
            </p>

            <div class="text-base text-gray-300 mb-12">
                <span id="jam">--:--</span> &bull; <span id="tanggal">-- ---- ----</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-center mb-10">
            @foreach ($jenisLayanan as $layanan)
            <form method="POST" action="{{ route('antrian.store') }}">
                @csrf
                <input type="hidden" name="jenis_layanan_id" value="{{ $layanan->id }}">
                <button type="submit" class="w-full h-full bg-white text-bps-dark rounded-xl shadow-lg p-6 flex flex-col items-center hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-left">
                    <div class="text-5xl mb-4">
                        @php
                            $iconMap = [
                                'Perpustakaan' => '📘',
                                'PST' => '👥',
                                'Konsultasi Statistik' => '📊',
                                'Rekomendasi Statistik' => '📌',
                                'Pengaduan Layanan' => '📣',
                            ];
                            $icon = $iconMap[$layanan->nama_layanan] ?? '⭐️';
                        @endphp
                        {{ $icon }}
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-center">{{ $layanan->nama_layanan }}</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center flex-grow">{{ $layanan->deskripsi ?? 'Layanan ' . $layanan->nama_layanan }}</p>
                    <span class="mt-auto w-full bg-bps-orange text-white px-4 py-2 rounded-md font-bold group-hover:bg-orange-600 transition text-center">
                        Ambil Nomor
                    </span>
                </button>
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
        setInterval(updateWaktu, 60000); // Update setiap menit
    </script>

</body>
</html>