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
                        <a href="{{ route('presensi.index') }}" 
                           class="text-sm text-white font-semibold rounded-full px-5 py-2.5 hover:bg-white/20 transform hover:scale-105 transition-all duration-300 ease-in-out">
                           Presensi
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-sm bg-bps-orange text-white font-bold rounded-full px-5 py-2.5 hover:bg-orange-500 transform hover:scale-105 transition-all duration-300 ease-in-out shadow-lg hover:shadow-orange-400/50">
                           Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="text-sm text-white font-semibold rounded-full px-5 py-2.5 hover:bg-white/20 transform hover:scale-105 transition-all duration-300 ease-in-out">
                               Register
                            </a>
                        @endif
                    @endauth
                @endif
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

        <!-- Grid Layanan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-center mb-10">
        @foreach ($jenisLayanan as $layanan)
        <form method="POST" action="{{ route('antrian.store') }}">
            @csrf
            <input type="hidden" name="jenis_layanan_id" value="{{ $layanan->id }}">
            <button type="submit"
            class="w-full h-full bg-white/95 text-bps-dark rounded-xl shadow-md hover:shadow-lg p-6 flex flex-col items-center hover:-translate-y-1 transition-all duration-300">
            
            <!-- Icon -->
            <div class="mb-4 text-bps-primary">
                @if ($layanan->nama_layanan === 'Perpustakaan')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25
                            A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25
                            a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25
                            A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                @elseif ($layanan->nama_layanan === 'Konsultasi Statistik')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 
                            1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501
                            L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501
                            48.172 48.172 0 0 0 3.423-.379c1.584-.233 
                            2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228
                            A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513
                            C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                @elseif ($layanan->nama_layanan === 'Rekomendasi Statistik')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />

                </svg>
                @elseif ($layanan->nama_layanan === 'Pengaduan Layanan')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25
                            a2.25 2.25 0 0 0 2.25-2.25v-1.372
                            c0-.516-.351-.966-.852-1.091l-4.423-1.106
                            c-.44-.11-.902.055-1.173.417l-.97 1.293
                            c-.282.376-.769.542-1.21.38
                            a12.035 12.035 0 0 1-7.143-7.143
                            c-.162-.441.004-.928.38-1.21l1.293-.97
                            c.363-.271.527-.734.417-1.173L6.963 3.102
                            a1.125 1.125 0 0 0-1.091-.852H4.5
                            A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                @endif
            </div>

            <!-- Nama Layanan -->
            <h3 class="text-lg font-semibold mb-1 text-center">{{ $layanan->nama_layanan }}</h3>
            <p class="text-sm text-gray-600 mb-4 text-center flex-grow">
                {{ $layanan->deskripsi ?? 'Layanan ' . $layanan->nama_layanan }}
            </p>

            <!-- Tombol -->
            <span
                class="mt-auto w-full bg-gradient-to-r from-bps-orange to-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:from-orange-500 hover:to-bps-orange transition text-center">
                Ambil Nomor
            </span>
            </button>
        </form>
        @endforeach
        </div>

        <!-- Tombol Buku Tamu dengan Icon -->
        <div class="flex justify-center mt-8 mb-10">
        <a href="{{ route('bukutamu.create') }}"
            class="inline-flex items-center gap-2 border-2 border-white text-white font-medium text-lg px-6 py-3 rounded-full hover:bg-white hover:text-bps-primary transition duration-300">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25
                    a2.25 2.25 0 0 0 2.25-2.25V6
                    a2.25 2.25 0 0 0-2.25-2.25H6
                    A2.25 2.25 0 0 0 3.75 6v2.25
                    A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25
                    A2.25 2.25 0 0 0 10.5 18v-2.25
                    a2.25 2.25 0 0 0-2.25-2.25H6
                    a2.25 2.25 0 0 0-2.25 2.25V18
                    A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18
                    a2.25 2.25 0 0 0 2.25-2.25V6
                    A2.25 2.25 0 0 0 18 3.75h-2.25
                    A2.25 2.25 0 0 0 13.5 6v2.25
                    a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            Buku Tamu
        </a>
        </div>

        <!-- Informasi Pelayanan -->
        <div class="bg-white/10 backdrop-blur-lg text-white p-6 rounded-xl shadow-inner max-w-2xl mx-auto text-left border border-white/20">
        <h3 class="text-lg font-bold mb-3">Informasi Pelayanan</h3>
        <div class="grid sm:grid-cols-2 gap-6 text-sm">
            <div>
            <p class="font-semibold">Jam Operasional:</p>
            <ul class="list-disc ml-5">
                <li>Senin - Jumat</li>
                <li>08:00 - 12:00 WIB</li>
            </ul>
            </div>
            <div>
            <p class="font-semibold">Ketentuan Antrian:</p>
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
