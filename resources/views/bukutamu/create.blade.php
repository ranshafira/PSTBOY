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
                <img src="https://4.bp.blogspot.com/-w45pPrU450Q/WitcqmIyloI/AAAAAAAAF3Q/k4pgHadbWvslcDQNTxLOezOK2cOaypPSACLcBGAs/s1600/BPS.png" alt="BPS Logo" class="h-10 mr-4 group-hover:opacity-80 transition-opacity">
                <span class="text-xl text-white hidden sm:block font-semibold group-hover:text-gray-300 transition-colors">
                    BPS Kabupaten Boyolali
                </span>
            </a>

            
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl p-8 space-y-6">

            <!-- Logo + Heading -->
            <div class="text-center">
                <img src="https://4.bp.blogspot.com/-w45pPrU450Q/WitcqmIyloI/AAAAAAAAF3Q/k4pgHadbWvslcDQNTxLOezOK2cOaypPSACLcBGAs/s1600/BPS.png" alt="BPS Logo" class="h-12 mx-auto mb-3">
                <h1 class="text-2xl font-bold text-bps-primary">Buku Tamu Digital</h1>
                <p class="text-sm text-gray-600">BPS Kabupaten Boyolali</p>
            </div>

            <!-- Alert jika berhasil -->
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Form Buku Tamu -->
<form action="{{ route('bukutamu.store') }}" method="POST" class="space-y-5">
    @csrf

    <!-- Nama Lengkap -->
    <div>
        <label for="nama_tamu" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="nama_tamu" id="nama_tamu" value="{{ old('nama_tamu') }}" required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-bps-primary focus:border-bps-primary">
        @error('nama_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Asal Instansi -->
    <div>
        <label for="instansi_tamu" class="block text-sm font-medium text-gray-700">Asal Instansi <span class="text-red-500">*</span></label>
        <input type="text" name="instansi_tamu" id="instansi_tamu" value="{{ old('instansi_tamu') }}" required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-bps-primary focus:border-bps-primary">
        @error('instansi_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Kontak -->
    <div>
        <label for="kontak_tamu" class="block text-sm font-medium text-gray-700">No. HP / Email <span class="text-red-500">*</span></label>
        <input type="text" name="kontak_tamu" id="kontak_tamu" value="{{ old('kontak_tamu') }}" required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-bps-primary focus:border-bps-primary">
        @error('kontak_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Keperluan -->
    <div>
        <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan <span class="text-red-500">*</span></label>
        <textarea name="keperluan" id="keperluan" rows="4" required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-bps-primary focus:border-bps-primary">{{ old('keperluan') }}</textarea>
        @error('keperluan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Tombol Submit -->
    <button type="submit" class="w-full bg-bps-orange text-white font-semibold py-3 rounded-md hover:bg-orange-500 transition-colors">
        Kirim
    </button>
</form>


            <!-- Kembali -->
            <div class="text-center">
                <a href="/" class="text-sm text-bps-primary font-semibold hover:underline">← Kembali ke Halaman Antrian</a>
            </div>
        </div>
    </main>
</body>
</html>
