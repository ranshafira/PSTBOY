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
        body { font-family: 'Inter', 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="bg-bps-primary">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">

        <a href="/" class="mb-6 flex items-center gap-3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/BPS_Logo.png" alt="BPS Logo" class="h-12">
            <div>
                <h1 class="text-2xl font-bold text-white">Buku Tamu Digital</h1>
                <p class="text-sm text-gray-300">BPS Kabupaten Boyolali</p>
            </div>
        </a>

        <div class="w-full max-w-lg bg-white rounded-lg shadow-xl p-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('bukutamu.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="nama_tamu" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tamu" id="nama_tamu" value="{{ old('nama_tamu') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-bps-primary focus:border-bps-primary">
                    @error('nama_tamu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="instansi_tamu" class="block text-sm font-medium text-gray-700">Asal Instansi (Opsional)</label>
                    <input type="text" name="instansi_tamu" id="instansi_tamu" value="{{ old('instansi_tamu') }}"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-bps-primary focus:border-bps-primary">
                </div>

                <div>
                    <label for="kontak_tamu" class="block text-sm font-medium text-gray-700">No. HP / Email (Opsional)</label>
                    <input type="text" name="kontak_tamu" id="kontak_tamu" value="{{ old('kontak_tamu') }}"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-bps-primary focus:border-bps-primary">
                </div>

                <div>
                    <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan <span class="text-red-500">*</span></label>
                    <textarea name="keperluan" id="keperluan" rows="4" required
                              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-bps-primary focus:border-bps-primary">{{ old('keperluan') }}</textarea>
                    @error('keperluan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-bps-orange hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        Kirim
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-gray-400">
            Kembali ke <a href="/" class="font-medium text-white hover:underline">Halaman Antrian</a>
        </p>
    </div>
</body>
</html>