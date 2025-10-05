@extends('layouts.app')

@section('title', 'Pelayanan Statistik Terpadu')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-1">Pelayanan Admin PST</h1>
        <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y • H:i') }}</p>
    </div>
</div>

{{-- Grid Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Kolom Kiri (Tabel Layanan Aktif + Buku Tamu) --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- Tabel Layanan Aktif --}}
        <div class="bg-gray-50 rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="mb-4">
                <div class="flex items-center gap-2">
                    {{-- Icon User Group --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 20h-3a2 2 0 01-2-2v-2a4 4 0 014-4h1a4 4 0 014 4v2a2 2 0 01-2 2zM6 20H3a2 2 0 01-2-2v-2a4 4 0 014-4h1a4 4 0 014 4v2a2 2 0 01-2 2zm9-10a3 3 0 100-6 3 3 0 000 6zm-8 0a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <h3 class="font-semibold text-lg">Layanan Aktif</h3>
                </div>
                <p class="text-sm text-gray-600">Daftar layanan yang sedang berlangsung</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">No. Antrian</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Pengunjung</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Jenis Layanan</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Status</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Waktu</th>
                            <th class="px-4 py-2 text-center font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white [&>tr>td]:py-4 [&>tr>td]:px-4">
                        @forelse($riwayatAntrian as $item)
                        @php
                        $rawStatus = is_string($item->status) ? strtolower($item->status) : $item->status;
                        $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                        'dipanggil' => 'bg-blue-100 text-blue-800',
                        'sedang_dilayani' => 'bg-purple-100 text-purple-800',
                        'selesai' => 'bg-green-100 text-green-800',
                        'batal' => 'bg-red-100 text-red-800',
                        ];
                        $statusText = [
                        'pending' => 'Menunggu',
                        'menunggu' => 'Menunggu',
                        'dipanggil' => 'Dipanggil',
                        'sedang_dilayani' => 'Sedang Dilayani',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                        ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $item->nomor_antrian }}</td>
                            <td class="px-4 py-2">{{ $item->pelayanan->nama_pengunjung ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->pelayanan->jenisLayanan->nama_layanan ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$rawStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusText[$rawStatus] ?? ucfirst((string) $item->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-500">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{-- Aksi disesuaikan status --}}
                                @if ($rawStatus === 'menunggu' || $rawStatus === 'pending')
                                <form action="{{ route('antrian.panggil', ['id' => $item->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded bg-blue-100 hover:bg-blue-300 text-blue-800 text-xs font-medium">
                                        Panggil
                                    </button>
                                </form>
                                @elseif ($rawStatus === 'dipanggil')
                                <form action="{{ route('antrian.panggil', ['id' => $item->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="ulang" value="1">
                                    <button type="submit" class="px-3 py-1 rounded bg-blue-100 hover:bg-blue-300 text-blue-800 text-xs font-medium mr-2">
                                        Panggil Ulang
                                    </button>
                                </form>
                                <a href="{{ route('pelayanan.langkah1.create', $item->id) }}"
                                    class="px-3 py-1 rounded bg-green-100 hover:bg-green-300 text-green-800 text-xs font-medium mr-2">
                                    Mulai
                                </a>
                                <form action="{{ route('antrian.batal', ['id' => $item->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded bg-red-100 hover:bg-red-300 text-red-800 text-xs font-medium">
                                        Batal
                                    </button>
                                </form>
                                @elseif ($rawStatus === 'sedang_dilayani')
                                @php
                                $pelayanan = \App\Models\Pelayanan::where('antrian_id', $item->id)
                                ->latest('created_at')
                                ->first();
                                @endphp
                                @if($pelayanan)
                                <a href="{{ route('pelayanan.lanjut', $pelayanan->id) }}"
                                    class="px-3 py-1 rounded bg-green-100 hover:bg-green-300 text-green-800 text-xs font-medium mr-2">
                                    Lanjutkan
                                </a>
                                @endif
                                @elseif ($rawStatus === 'selesai')
                                @if($item->pelayanan)
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Indikator Status (Tidak bisa diklik) --}}
                                    <span class="px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-medium">
                                        Selesai
                                    </span>
                                </div>
                                @else
                                {{-- Fallback jika data pelayanan tidak ada --}}
                                <span class="px-3 py-1 rounded bg-gray-100 text-gray-500 text-xs font-medium">
                                    Selesai
                                </span>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada layanan aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $riwayatAntrian->links() }}
            </div>
        </div>

        {{-- Tabel Buku Tamu --}}
        <div class="bg-gray-50 rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="mb-4 flex items-center gap-2">
                {{-- Icon Calendar --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="font-semibold text-lg text-gray-700">Daftar Buku Tamu</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">ID</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Nama Tamu</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Instansi</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Kontak</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Keperluan</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Waktu Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white [&>tr>td]:py-4 [&>tr>td]:px-4">
                        @forelse($riwayatBukuTamu as $tamu)
                        <tr class="hover:bg-gray-50">
                            <td>{{ $tamu->id }}</td>
                            <td>{{ $tamu->nama_tamu }}</td>
                            <td>{{ $tamu->instansi_tamu }}</td>
                            <td>{{ $tamu->kontak_tamu }}</td>
                            <td>{{ $tamu->keperluan }}</td>
                            <td>{{ \Carbon\Carbon::parse($tamu->waktu_kunjungan)->format('d-m-Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada buku tamu hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $riwayatBukuTamu->links() }}
            </div>
        </div>
    </div>

    {{-- Kolom Kanan (Statistik + Alur) --}}
    <div class="space-y-8">

        {{-- Statistik Pelayanan --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center space-x-2 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
                <h3 class="font-semibold text-lg text-gray-700">Statistik Pelayanan Hari Ini</h3>
            </div>

            <div class="text-center mb-6">
                <p class="{{ $statistik['antrianBerjalan'] ? 'text-3xl font-bold text-orange-500' : 'text-2xl font-semibold text-gray-400' }}">
                    {{ $statistik['antrianBerjalan']->nomor_antrian ?? 'Tidak ada' }}
                </p>
                <p class="text-sm {{ $statistik['antrianBerjalan'] ? 'text-gray-500' : 'text-gray-400' }}">
                    Nomor Antrian Aktif
                </p>
            </div>

            <div class="space-y-2 text-sm text-gray-800">
                <div class="flex justify-between">
                    <span>Total Antrian:</span>
                    <span>{{ $statistik['totalAntrianHariIni'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Sudah Dilayani:</span>
                    <span>{{ $statistik['sudahDilayani'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Buku Tamu:</span>
                    <span>{{ $statistik['bukuTamuCount'] }}</span>
                </div>
            </div>
        </div>

        {{-- Alur Pelayanan --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.752 11.168l-6.6-3.8A1 1 0 007 8.2v7.6a1 1 0 001.152.984l6.6-3.8a1 1 0 000-1.964z" />
                </svg>
                <h3 class="font-semibold text-lg text-gray-700">Alur Pelayanan</h3>
            </div>
            <p class="text-sm text-gray-500 mb-4">Tahapan lengkap proses pelayanan PST</p>

            <ol class="space-y-3 text-sm text-gray-800">
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">1</span>
                    Mulai Layanan
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">2</span>
                    Identitas & Dokumen
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">3</span>
                    Keperluan
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">4</span>
                    Hasil Layanan
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">5</span>
                    Selesai
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">6</span>
                    Survei
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">7</span>
                    Simpan
                </li>
            </ol>
        </div>
    </div>
</div>
</div>
@endsection