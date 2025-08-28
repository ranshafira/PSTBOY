<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Absensi Admin PST</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">

  <!-- Navbar -->
  <nav class="bg-white shadow-sm sticky top-0 z-10">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <div class="bg-orange-500 text-white font-bold rounded-md w-8 h-8 flex items-center justify-center">P</div>
        <span class="font-semibold text-lg">Pelayanan PST</span>
      </div>
      <ul class="flex space-x-6 text-sm font-medium text-gray-700">
        <li><a href="#" class="text-orange-500 font-semibold">Dashboard Pegawai</a></li>
        <li><a href="{{ route('presensi.index') }}" class="hover:text-orange-500 transition">Presensi</a></li>
        <li><a href="#" class="hover:text-orange-500 transition">Riwayat</a></li>
        <li><a href="#" class="hover:text-orange-500 transition">Profil</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-orange-500 transition">
                Logout
                </button>
            </form>
        </li>

      </ul>
    </div>
  </nav>

  <main class="container mx-auto px-4 py-8 max-w-7xl">

    <h1 class="text-3xl font-bold mb-1">Dashboard Pegawai</h1>
    <p class="text-gray-600 mb-8">apaya</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Status Hari Ini -->
      <section class="bg-white rounded-lg shadow p-6 space-y-4">

      </section>

      <!-- Statistik Kehadiran -->
      <section class="bg-white rounded-lg shadow p-6">
  
      </section>

      <!-- Informasi -->
      <section class="bg-white rounded-lg shadow p-6">
      
      </section>
    </div>

    <!-- Riwayat Absensi -->
    <section class="bg-white rounded-lg shadow mt-10 p-6">
   
    </section>

  </main>
  
</body>
</html>
