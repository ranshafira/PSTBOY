<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - BPS Kab. Boyolali</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <img src="{{ asset('build/assets/images/BPS.png') }}" alt="BPS Logo" class="h-10 mr-4 group-hover:opacity-80 transition-opacity">
                <span class="text-xl text-white hidden sm:block font-semibold group-hover:text-gray-300 transition-colors">
                    BPS Kabupaten Boyolali
                </span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-4xl bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-bps-dark to-bps-primary p-6 text-center">
                <img src="{{ asset('build/assets/images/BPS.png') }}" alt="BPS Logo" class="mx-auto w-14 h-14 mb-2">
                <h1 class="text-2xl font-bold text-white">Pelayanan Non-PST</h1>
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

                <!-- Form Buku Tamu -->
                <form action="{{ route('bukutamu.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_tamu" class="block text-sm font-medium text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_tamu" id="nama_tamu" value="{{ old('nama_tamu') }}" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        @error('nama_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Asal Instansi -->
                    <div>
                        <label for="instansi_tamu" class="block text-sm font-medium text-gray-700">
                            Asal Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="instansi_tamu" id="instansi_tamu" value="{{ old('instansi_tamu') }}" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        @error('instansi_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label for="kontak_tamu" class="block text-sm font-medium text-gray-700">
                            No. HP / Email <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kontak_tamu" id="kontak_tamu" value="{{ old('kontak_tamu') }}" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">
                        @error('kontak_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Keperluan Ditujukan Kepada -->
                    <div>
                        <label for="tujuan" class="block text-sm font-medium text-gray-700">
                            Keperluan Ditujukan Kepada <span class="text-red-500">*</span>
                        </label>
                        <select name="tujuan" id="tujuan" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary text-gray-700">
                            <option value="" disabled selected hidden>Pilih Tujuan</option>
                            <option value="Kepala BPS" {{ old('tujuan') == 'Kepala' ? 'selected' : '' }}>Kepala BPS</option>
                            <option value="Subbag Umum" {{ old('tujuan') == 'Subbag Umum' ? 'selected' : '' }}>Subbag Umum</option>
                            <option value="Bagian Teknis" {{ old('tujuan') == 'Teknis' ? 'selected' : '' }}>Bagian Teknis</option>
                            <option value="Bagian Pengaduan" {{ old('tujuan') == 'Pengaduan' ? 'selected' : '' }}>Bagian Pengaduan</option>
                            <option value="Lainnya" {{ old('tujuan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('tujuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Keperluan -->
                    <div class="md:col-span-2">
                        <label for="keperluan" class="block text-sm font-medium text-gray-700">
                            Keperluan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keperluan" id="keperluan" rows="4" required
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-bps-primary focus:border-bps-primary">{{ old('keperluan') }}</textarea>
                        @error('keperluan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex justify-end space-x-3">
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
</body>

</html>