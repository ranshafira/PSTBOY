@extends('layouts.app')

@section('title', 'Riwayat Layanan')

@section('content')
<div class="space-y-6">

  <!-- Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-1">Riwayat Layanan</h1>
      <p class="text-gray-600">Daftar dan detail layanan yang telah dicatat</p>
    </div>
    <!-- Export CSV di pojok kanan atas -->
    <div>
      <a href="{{ route('riwayat.export', array_merge(request()->all(), ['tab' => $tab])) }}" 
         class="px-4 py-2 bg-white border border-gray-300 text-black font-bold text-sm rounded-lg shadow">
        Export CSV
      </a>
    </div>
  </div>

  <!-- Toggle Tab Modern -->
  <div class="flex justify-start mb-6 border-b border-gray-200 dark:border-gray-700">
    <a href="{{ route('riwayat.index', array_merge(request()->all(), ['tab' => 'pelayanan'])) }}"
      class="px-4 py-2 font-medium transition-all duration-300
              {{ $tab == 'pelayanan' 
                ? 'text-orange-600 border-b-2 border-orange-500' 
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
      Riwayat Pelayanan
    </a>

    <a href="{{ route('riwayat.index', array_merge(request()->all(), ['tab' => 'buku_tamu'])) }}"
      class="ml-6 px-4 py-2 font-medium transition-all duration-300
              {{ $tab == 'buku_tamu' 
                ? 'text-orange-600 border-b-2 border-orange-500' 
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
      Riwayat Buku Tamu
    </a>
  </div>

  <!-- Filter & Pencarian -->
  <form method="GET" action="{{ route('riwayat.index') }}" class="bg-white p-6 rounded-xl shadow">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="flex items-center mb-4 space-x-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z" />
      </svg>
      <h2 class="font-semibold text-lg text-gray-700">Filter & Pencarian</h2>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Pencarian</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari..."
          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
      </div>

      @if($tab == 'pelayanan')
      <div>
        <label class="block text-sm text-gray-600 mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <option value="">Semua status</option>
          <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
          <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
          <option value="Selesai dengan tindak lanjut" {{ request('status') == 'Selesai dengan tindak lanjut' ? 'selected' : '' }}>Selesai dengan tindak lanjut</option>
          <option value="Tidak dapat dipenuhi" {{ request('status') == 'Tidak dapat dipenuhi' ? 'selected' : '' }}>Tidak dapat dipenuhi</option>
          <option value="Dibatalkan pengunjung" {{ request('status') == 'Dibatalkan pengunjung' ? 'selected' : '' }}>Dibatalkan pengunjung</option>
        </select>
      </div>

      <div>
        <label class="block text-sm text-gray-600 mb-1">Jenis Layanan</label>
        <select name="jenis_layanan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
          <option value="">Semua jenis</option>
          @foreach(\App\Models\JenisLayanan::all() as $jenis)
          <option value="{{ $jenis->id }}" {{ request('jenis_layanan') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_layanan }}</option>
          @endforeach
        </select>
      </div>
      @endif

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
        Menampilkan {{ $riwayat->count() }} dari {{ $riwayat->total() }} data
      </div>
      <div>
        <a href="{{ route('riwayat.index', ['tab' => $tab]) }}" class="text-sm text-orange-600 font-bold hover:underline">Hapus Filter</a>
        <button type="submit" class="ml-2 px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-bold shadow">Terapkan</button>
      </div>
    </div>
  </form>

  <!-- Daftar Tabel -->
  @if($tab == 'pelayanan')
      @include('riwayat.tabel-pelayanan', ['riwayat' => $riwayat])
  @else
      @include('riwayat.tabel-bukutamu', ['riwayat' => $riwayat])
  @endif

</div>
@endsection
