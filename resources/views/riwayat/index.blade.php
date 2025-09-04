@extends('layouts.app')

@section('title', 'Riwayat Pelayanan')

@section('content')
<div class="space-y-6">

  <!-- Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-1">Riwayat Pelayanan</h1>
      <p class="text-gray-600">Daftar dan detail pelayanan yang telah diselesaikan</p>
    </div>
    <!-- Export CSV di pojok kanan atas -->
    <div>
      <a href="{{ route('riwayat.export', request()->all()) }}" class="px-4 py-2 bg-orange-500 text-white font-bold text-sm rounded-lg shadow">
        Export CSV
      </a>
    </div>
  </div>

  <!-- Filter & Pencarian -->
  <form method="GET" action="{{ route('riwayat.index') }}" class="bg-white p-6 rounded-xl shadow">
    <div class="flex items-center mb-4 space-x-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 012 0h14a1 1 0 110 2H5a1 1 0 01-2 0V4zM3 12a1 1 0 012 0h14a1 1 0 110 2H5a1 1 0 01-2 0v-2zM3 20a1 1 0 012 0h14a1 1 0 110 2H5a1 1 0 01-2 0v-2z"/>
      </svg>
      <h2 class="font-semibold text-lg text-gray-700">Filter & Pencarian</h2>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
      <!-- Pencarian -->
      <div>
        <label class="block text-sm text-gray-600 mb-1">Pencarian</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor antrian, nama, atau jenis..."
          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm text-gray-600 mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <option value="">Semua status</option>
          <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
          <option value="Selesai dengan tindak lanjut" {{ request('status') == 'Selesai dengan tindak lanjut' ? 'selected' : '' }}>Selesai dengan tindak lanjut</option>
          <option value="Tidak dapat dipenuhi" {{ request('status') == 'Tidak dapat dipenuhi' ? 'selected' : '' }}>Tidak dapat dipenuhi</option>
          <option value="Dibatalkan klien" {{ request('status') == 'Dibatalkan klien' ? 'selected' : '' }}>Dibatalkan klien</option>
        </select>
      </div>

      <!-- Jenis Layanan -->
      <div>
        <label class="block text-sm text-gray-600 mb-1">Jenis Layanan</label>
        <select name="jenis_layanan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <option value="">Semua jenis</option>
          @foreach(\App\Models\JenisLayanan::all() as $jenis)
          <option value="{{ $jenis->id }}" {{ request('jenis_layanan') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_layanan }}</option>
          @endforeach
        </select>
      </div>

      <!-- Periode -->
      <div>
        <label class="block text-sm text-gray-600 mb-1">Periode</label>
        <select name="periode" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <option value="">Semua periode</option>
          <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari ini</option>
          <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu ini</option>
          <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan ini</option>
        </select>
      </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
      <div class="text-sm text-gray-500">
        Menampilkan {{ $riwayat->count() }} dari {{ $riwayat->total() }} pelayanan
      </div>
      <div>
        <a href="{{ route('riwayat.index') }}" class="text-sm text-orange-600 font-bold hover:underline">Hapus Filter</a>
        <button type="submit" class="ml-2 px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-bold shadow">Terapkan</button>
      </div>
    </div>
  </form>

  <!-- Daftar Pelayanan -->
  <div class="bg-white p-6 rounded-xl shadow">
    <div class="flex items-center mb-4 space-x-2">
      <svg xmlns="https://www.svgrepo.com/svg/510981/filter" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4v-4"/>
      </svg>
      <h2 class="font-semibold text-lg text-gray-700">Daftar Pelayanan</h2>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border-t border-gray-200 text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="px-4 py-2 text-left">No. Antrian</th>
            <th class="px-4 py-2 text-left">Klien</th>
            <th class="px-4 py-2 text-left">Kontak</th>
            <th class="px-4 py-2 text-left">Jenis Layanan</th>
            <th class="px-4 py-2 text-left">Tanggal</th>
            <th class="px-4 py-2 text-left">Durasi</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-left">Token Survei</th>
            <th class="px-4 py-2 text-left">Kepuasan</th>
            <th class="px-4 py-2 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($riwayat as $p)
          <tr>
            <td class="px-4 py-2">{{ $p->antrian->nomor_antrian ?? '-' }}</td>
            <td class="px-4 py-2">{{ $p->nama_pelanggan }}</td>
            <td class="px-4 py-2">{{ $p->kontak_pelanggan }}</td>
            <td class="px-4 py-2">{{ $p->jenisLayanan->nama_layanan ?? '-' }}</td>
            <td class="px-4 py-2 text-gray-500">{{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->format('d-m-Y') }}</td>
            <td class="px-4 py-2">
              @if($p->waktu_selesai_sesi)
                {{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInHours($p->waktu_selesai_sesi) }} jam
                {{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInMinutes($p->waktu_selesai_sesi) % 60 }} menit
              @else
                -
              @endif
            </td>
            <td class="px-4 py-2">
                @php
                    $status = $p->status_penyelesaian;
                    $bgColor = 'bg-gray-100 text-gray-500'; // default
                    if(in_array($status, ['Selesai', 'Selesai dengan tindak lanjut'])) $bgColor = 'bg-green-100 text-green-700';
                    elseif($status == 'Tidak dapat dipenuhi') $bgColor = 'bg-red-100 text-red-700';
                    elseif($status == 'Dibatalkan klien') $bgColor = 'bg-purple-100 text-purple-700';
                @endphp
                <span class="px-2 py-1 text-xs rounded-full {{ $bgColor }}">
                    {{ $status }}
                </span>
            </td>

            <td class="px-4 py-2">{{ $p->survey_token }}</td>
            @php
                $skor = $p->surveyKepuasan->skor_kepuasan ?? null;
                $rataRata = $skor ? round(array_sum($skor) / count($skor), 1) : null;
            @endphp
            <td class="px-4 py-2">
                {{ $rataRata ? $rataRata.'/5' : 'Belum Mengisi' }}
            </td>
            <td class="px-4 py-2">
              <a href="#" class="px-2 py-1 text-xs rounded-full bg-blue-100 text-gray-500 hover:text-indigo-600">Lihat</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $riwayat->withQueryString()->links() }}
    </div>
  </div>

</div>
@endsection
