@extends('layouts.app')

@section('title', 'Presensi')

@section('content')

  {{-- Judul Halaman --}}
  <div class="mb-8">
      <h1 class="text-3xl font-bold">Presensi Admin PST</h1>
      <p class="text-gray-500">Sistem pencatatan kehadiran dan kepulangan harian</p>
  </div>

  {{-- Notifikasi --}}
  @if (session('success'))
      <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
         <p>{{ session('success') }}</p>
      </div>
  @endif
  @if (session('error'))
       <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
         <p>{{ session('error') }}</p>
      </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      {{-- Kolom Kiri --}}
      <div class="lg:col-span-2 space-y-8">

          {{-- Status Hari Ini --}}
          <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
              <h3 class="font-bold text-lg mb-1">Status Hari Ini</h3>
              <p class="text-gray-500 text-sm mb-4">
                  {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} • <span id="live-clock"></span>
              </p>

              <div class="flex items-center space-x-8 mb-4">
                  <div>
                      <p class="text-sm text-gray-500"> Waktu Masuk</p>
                      <p class="font-bold text-xl">{{ $presensiHariIni ? $presensiHariIni->waktu_datang : '-' }}</p>
                  </div>
                  <div>
                      <p class="text-sm text-gray-500"> Waktu Keluar</p>
                      <p class="font-bold text-xl">{{ $presensiHariIni ? $presensiHariIni->waktu_pulang : '-' }}</p>
                  </div>
              </div>

              @if($presensiHariIni && is_null($presensiHariIni->waktu_pulang))
                  <form action="{{ route('presensi.checkout') }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition">
                           Check out
                      </button>
                  </form>
              @elseif(is_null($presensiHariIni))
                  <form action="{{ route('presensi.checkin') }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-orange-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-600 transition">
                           Check in
                      </button>
                  </form>
              @else
                  <p class="text-sm font-semibold text-green-600 bg-green-100 px-4 py-2 rounded-lg inline-block">
                      Presensi hari ini sudah selesai
                  </p>
              @endif
          </div>

          {{-- Riwayat Presensi --}}
          <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
              <h3 class="font-bold text-lg mb-4">Riwayat Presensi</h3>
              <div class="overflow-x-auto">
                  <table class="w-full text-sm text-left">
                      <thead class="bg-gray-200">
                          <tr>
                              <th class="p-3">Tanggal</th>
                              <th class="p-3">Masuk</th>
                              <th class="p-3">Keluar</th>
                              <th class="p-3">Jam Kerja</th>
                              <th class="p-3">Status</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($riwayatPresensi as $presensi)
                              <tr class="border-b">
                                  <td class="p-3">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}</td>
                                  <td class="p-3">{{ $presensi->waktu_datang }}</td>
                                  <td class="p-3">{{ $presensi->waktu_pulang ?? '-' }}</td>
                                  <td class="p-3">
                                      @if($presensi->waktu_datang && $presensi->waktu_pulang)
                                          {{ \Carbon\Carbon::parse($presensi->waktu_datang)->diffInHours(\Carbon\Carbon::parse($presensi->waktu_pulang)) }} jam
                                      @else
                                          -
                                      @endif
                                  </td>
                                  <td class="p-3">
                                      <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $presensi->waktu_pulang ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                          {{ $presensi->waktu_pulang ? 'Selesai' : 'Berlangsung' }}
                                      </span>
                                  </td>
                              </tr>
                          @empty
                              <tr>
                                  <td colspan="5" class="p-3 text-center text-gray-500">Belum ada riwayat presensi.</td>
                              </tr>
                          @endforelse
                      </tbody>
                  </table>
              </div>
          </div>
      </div>

      {{-- Kolom Kanan --}}
      <div class="space-y-8">
          {{-- Statistik Kehadiran --}}
          <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-center">
              <h3 class="font-bold text-lg mb-4">Statistik Kehadiran</h3>
              <p class="text-5xl font-bold text-orange-500 mb-4">{{ number_format($statistik['persentase'], 1) }}%</p>
              <div class="text-sm space-y-2">
                  <div class="flex justify-between"><span>Hari Hadir:</span> <span class="font-semibold">{{ $statistik['hadir'] }} hari</span></div>
                  <div class="flex justify-between"><span>Total Hari:</span> <span class="font-semibold">{{ $statistik['total_hari'] }} hari</span></div>
                  <div class="flex justify-between"><span>Tidak Hadir:</span> <span class="font-semibold">{{ $statistik['tidak_hadir'] }} hari</span></div>
              </div>
          </div>

          {{-- Informasi --}}
          <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
              <h3 class="font-bold text-lg mb-4">Informasi</h3>
              <ul class="text-sm text-gray-600 list-disc list-inside space-y-2">
                  <li>Jam kerja standar: 08:00 - 16:00</li>
                  <li>Check-in otomatis mencatat waktu kedatangan</li>
                  <li>Jangan lupa check-out saat pulang</li>
              </ul>
          </div>
      </div>
  </div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('live-clock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush
