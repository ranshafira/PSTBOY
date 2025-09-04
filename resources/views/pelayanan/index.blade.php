@extends('layouts.app')

@section('title', 'Pelayanan Statistik Terpadu')

@section('content')
<div class="w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Pelayanan Admin PST</h1>
            <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y • H:i') }}</p>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="flex gap-6">
        {{-- Tabel Layanan Aktif (2/3 lebar) --}}
        <div class="w-2/3 bg-white rounded-lg shadow-sm border border-gray-100 overflow-x-auto">
            <div class="p-6 border-b border-gray-100 flex items-center gap-2">
                {{-- Icon People --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-6a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="font-semibold text-lg">Layanan Aktif</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">No. Antrian</th>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">Jenis Layanan</th>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-2 text-center text-left text-xs font-small text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($riwayatGabungan as $item)
                            @php
                            $rawStatus  = is_string($item->status) ? strtolower($item->status) : $item->status;
                            $isBukuTamu = ($item->nomor_antrian === '-' || strtolower($item->nama_layanan) === 'buku tamu');
                            $statusColors = [
                                'pending'   => 'bg-yellow-100 text-yellow-800',
                                'menunggu'  => 'bg-yellow-100 text-yellow-800',
                                'dipanggil' => 'bg-blue-100 text-blue-800',
                                'sedang_dilayani' => 'bg-purple-100 text-purple-800',
                                'selesai'   => 'bg-green-100 text-green-800',
                                'batal'     => 'bg-red-100 text-red-800',
                            ];
                            $statusText = [
                                'pending'   => 'Menunggu',
                                'menunggu'  => 'Menunggu',
                                'dipanggil' => 'Dipanggil',
                                'sedang_dilayani' => 'Sedang Dilayani',
                                'selesai'   => 'Selesai',
                                'batal'     => 'Batal',
                            ];
                            @endphp
                        <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 text-center text-sm">{{ $item->nomor_antrian }}</td>
              <td class="px-6 py-4 text-center text-sm">{{ $item->nama }}</td>
              <td class="px-6 py-4 text-center text-sm">{{ $item->nama_layanan }}</td>
              <td class="px-6 py-4 text-center text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$rawStatus] ?? 'bg-gray-100 text-gray-800' }}">
                  {{ $statusText[$rawStatus] ?? ucfirst((string) $item->status) }}
                </span>
              </td>
              <td class="px-6 py-4 text-center text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
              </td>
              <td class="px-6 py-4 text-sm text-center">
                @if ($isBukuTamu)
                    <span class="text-gray-400 text-xs">-</span>
                
                @elseif ($rawStatus === 'menunggu' || $rawStatus === 'pending')
                    <!-- Tombol Panggil -->
                    <form action="{{ route('antrian.panggil', ['id' => $item->id]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded bg-blue-100 hover:bg-blue-300 text-blue-800 text-xs font-medium">
                            Panggil
                        </button>
                    </form>

                @elseif ($rawStatus === 'dipanggil')
                    <!-- Tombol Panggil Ulang -->
                    <form action="{{ route('antrian.panggil', ['id' => $item->id]) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="ulang" value="1">
                        <button type="submit" class="px-3 py-1 rounded bg-blue-100 hover:bg-blue-300 text-blue-800 text-xs font-medium mr-2">
                            Panggil Ulang
                        </button>
                    </form>

                    <!-- Tombol Mulai -->
                    <a href="{{ route('pelayanan.show', $item->id) }}" 
                      class="px-3 py-1 rounded bg-green-100 hover:bg-green-300 text-green-800 text-xs font-medium mr-2">
                      Mulai
                    </a>

                    <!-- Tombol Batal -->
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
                    <span class="text-gray-500 text-xs font-medium">Selesai Dilayani</span>
                @endif
            </td>

            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $riwayatGabungan->links() }}
            </div>
        </div>

        {{-- Sidebar (1/3 lebar) --}}
        <div class="w-1/3 flex flex-col gap-6">
            {{-- Alur Pelayanan --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-4">
                    {{-- Icon Play --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.6-3.8A1 1 0 007 8.2v7.6a1 1 0 001.152.984l6.6-1.9a1 1 0 000-1.964z" />
                    </svg>
                    <h3 class="font-semibold text-lg">Alur Pelayanan</h3>
                </div>
                <ol class="list-decimal list-inside text-gray-700 space-y-2">
                    <li>Mulai Layanan</li>
                    <li>Identitas & Dokumen</li>
                    <li>Keperluan</li>
                    <li>Hasil Layanan</li>
                    <li>Selesai</li>
                </ol>
            </div>

            {{-- Tips Pelayanan --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-4">
                    {{-- Icon Lightbulb / Tips --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v1m1 16h-2m-2-2h6m-4-3h2a2 2 0 100-4h-2a2 2 0 100 4zm0 0v1a2 2 0 002 2h2a2 2 0 002-2v-1m-4 0V3" />
                    </svg>
                    <h3 class="font-semibold text-lg">Tips Pelayanan</h3>
                </div>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Pastikan identitas klien terverifikasi dengan benar</li>
                    <li>Lengkapi semua dokumen yang diperlukan</li>
                    <li>Catat hasil pelayanan secara detail</li>
                    <li>Jangan lupa untuk survei kepuasan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
