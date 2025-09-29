<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - BPS Kab. Boyolali</title>
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
<body class="bg-bps-primary min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-gradient-to-r from-bps-dark to-bps-primary shadow-lg border-b border-white/10">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="https://boyolalikab.bps.go.id/" target="_blank" rel="noopener noreferrer" class="flex items-center group">
                <img src="https://4.bp.blogspot.com/-w45pPrU450Q/WitcqmIyloI/AAAAAAAAF3Q/k4pgHadbWvslcDQNTxLOezOK2cOaypPSACLcBGAs/s1600/BPS.png" alt="BPS Logo" class="h-10 mr-4 group-hover:opacity-80 transition-opacity">
                <span class="text-xl text-white hidden sm:block font-semibold group-hover:text-gray-300 transition-colors">
                    BPS Kabupaten Boyolali
                </span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-5xl bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-bps-dark to-bps-primary p-6 text-center">
                <img src="https://4.bp.blogspot.com/-w45pPrU450Q/WitcqmIyloI/AAAAAAAAF3Q/k4pgHadbWvslcDQNTxLOezOK2cOaypPSACLcBGAs/s1600/BPS.png" alt="BPS Logo" class="mx-auto w-14 h-14 mb-2">
                <h1 class="text-2xl font-bold text-white">Pelayanan PST</h1>
                <p class="text-blue-100 text-sm">Buku Tamu Digital</p>
            </div>

            <!-- Body Card -->
            <div class="p-8 space-y-6">

                <!-- Alert jika berhasil -->
                @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Form Buku Tamu PST -->
                <form action="{{ route('antrian.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <!-- Kiri -->
                    <div class="space-y-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pengunjung" id="nama" value="{{ old('nama_pengunjung') }}" required
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                            @error('nama_pengunjung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Instansi -->
                        <div>
                            <label for="instansi" class="block text-sm font-medium text-gray-700">Instansi/Organisasi</label>
                            <input type="text" name="instansi_pengunjung" id="instansi" value="{{ old('instansi_pengunjung') }}"
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        </div>

                        <!-- Pendidikan -->
                        <div>
                            <label for="pendidikan" class="block text-sm font-medium text-gray-700">Pendidikan</label>
                            <select name="pendidikan" id="pendidikan"
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                                <option value="" disabled {{ old('pendidikan') ? '' : 'selected' }}>Pilih Jenjang</option>
                                <option value="SMA" {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ old('pendidikan') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="S1" {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="space-y-6">
                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <div class="mt-1 flex gap-3">
                                <label class="flex-1">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" class="peer hidden" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}>
                                    <div class="h-12 border rounded-lg flex items-center justify-center cursor-pointer peer-checked:bg-bps-primary peer-checked:text-white">
                                        Laki-laki
                                    </div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" class="peer hidden" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                    <div class="h-12 border rounded-lg flex items-center justify-center cursor-pointer peer-checked:bg-bps-primary peer-checked:text-white">
                                        Perempuan
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- No HP -->
                        <div>
                            <label for="hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input type="text" name="no_hp" id="hp" value="{{ old('no_hp') }}"
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        </div>

                        <!-- Jenis Layanan -->
                        <div>
                        <label class="block text-sm font-medium text-gray-700">Media Layanan</label>
                        <div class="mt-1 flex gap-4">
                            <label class="flex-1">
                                <input type="radio" name="media_layanan" value="langsung" onclick="toggleLayanan(true)" class="peer hidden" {{ old('media_layanan') == 'langsung' ? 'checked' : '' }}>
                                <div class="h-12 border rounded-lg flex items-center justify-center cursor-pointer whitespace-nowrap px-6 peer-checked:bg-bps-primary peer-checked:text-white">
                                Layanan Langsung
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="media_layanan" value="whatsapp" onclick="toggleLayanan(false)" class="peer hidden" {{ old('media_layanan') == 'whatsapp' ? 'checked' : '' }}>
                                <div class="h-12 border rounded-lg flex items-center justify-center cursor-pointer whitespace-nowrap px-6 peer-checked:bg-green-600 peer-checked:text-white">
                                WhatsApp
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="media_layanan" value="email" onclick="toggleLayanan(false)" class="peer hidden" {{ old('media_layanan') == 'email' ? 'checked' : '' }}>
                                <div class="h-12 border rounded-lg flex items-center justify-center cursor-pointer whitespace-nowrap px-6 peer-checked:bg-orange-500 peer-checked:text-white">
                                Email
                                </div>
                            </label>
                        </div>
                        </div>

                        <!-- Sub Layanan -->
                        <div id="subLayanan" class="{{ old('media_layanan') == 'langsung' ? '' : 'hidden' }}">
                            <label for="sub_layanan" class="block text-sm font-medium text-gray-700">Pilih Layanan</label>
                            <select name="jenis_layanan_id" id="sub_layanan"
                                class="h-12 mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                                <option value="" {{ old('jenis_layanan_id') ? '' : 'selected' }} disabled>Pilih Layanan</option>
                                <option value="2" {{ old('jenis_layanan_id') == '2' ? 'selected' : '' }}>Konsultasi Statistik</option>
                                <option value="3" {{ old('jenis_layanan_id') == '3' ? 'selected' : '' }}>Rekomendasi Statistik</option>
                                <option value="1" {{ old('jenis_layanan_id') == '1' ? 'selected' : '' }}>Perpustakaan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex justify-end space-x-3 pt-4">
                        <a href="/" class="px-5 py-2 border border-bps-primary text-bps-primary font-medium rounded-full hover:bg-bps-primary hover:text-white transition-colors">
                            Kembali
                        </a>
                        <button type="submit"
                            class="px-5 py-2 bg-gradient-to-r from-bps-orange to-orange-500 text-white font-medium rounded-full shadow hover:opacity-90 transition">
                            Kirim
                        </button>
                    </div>
                </form>
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

            @php
                $nomor = explode(': ', session('success'))[1] ?? '';
                $mediaLayanan = strtolower(session('media_layanan') ?? '');
            @endphp

            <p class="text-6xl font-bold text-bps-orange my-4 bg-gray-100 p-4 rounded-lg">
                {{ $nomor }}
            </p>
            
            @if($mediaLayanan === 'whatsapp')
                <p class="text-gray-600 mt-3 text-sm">
                    Silakan tunggu, petugas akan melayani Anda sesuai urutan antrian melalui WhatsApp.
                </p>
            @elseif($mediaLayanan === 'email')
                <p class="text-gray-600 mt-3 text-sm">
                    Silakan tunggu, petugas akan menghubungi Anda melalui email sesuai antrian.
                </p>
            @endif

            <button @click="showModal = false" 
                    class="mt-6 w-full bg-bps-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-bps-dark transition-colors duration-300">
                Tutup
            </button>
        </div>
    </div>
    @endif

    
    <script>
        function toggleLayanan(show) {
            document.getElementById('subLayanan').classList.toggle('hidden', !show);
            // Optional: reset jenis_layanan_id jika WA/email dipilih
            if (!show) document.getElementById('sub_layanan').value = '';
        }
    </script>
</body>
</html>
