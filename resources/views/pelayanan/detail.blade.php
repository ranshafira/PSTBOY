{{-- resources/views/pelayanan/detail.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pelayanan')

@section('content')
<div class="font-sans antialiased bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pelayanan</h1>
                <p class="text-md text-gray-500 mt-1">Ringkasan lengkap hasil layanan, status, dan dokumen terkait.</p>
            </div>
            <a href="{{ route('pelayanan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                <span>Kembali</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Kolom Kiri (Sidebar Info) --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div><p class="text-sm text-gray-500 mb-1">Nomor Antrian</p><p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->antrian->nomor_antrian ?? '-' }}</p></div>
                        <div><p class="text-sm text-gray-500 mb-1">Petugas</p><p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->petugas->nama_lengkap ?? '-' }}</p></div>
                        <div><p class="text-sm text-gray-500 mb-1">Status Pelayanan</p><span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-sm">{{ $pelayanan->status_penyelesaian ? ucfirst(str_replace('_',' ',$pelayanan->status_penyelesaian)) : '-' }}</span></div>
                        <div><p class="text-sm text-gray-500 mb-1">Jenis Layanan</p><p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->jenisLayanan->nama_layanan ?? '-' }}</p></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Waktu & Durasi</h3>
                    <div class="space-y-4">
                        <div><p class="text-sm text-gray-500 mb-1">Waktu Mulai</p><p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->translatedFormat('d F Y, H:i') }}</p></div>
                        <div><p class="text-sm text-gray-500 mb-1">Waktu Selesai</p><p class="font-semibold text-gray-900">{{ $pelayanan->waktu_selesai_sesi ? \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->translatedFormat('d F Y, H:i') : '-' }}</p></div>
                        @if($pelayanan->waktu_mulai_sesi && $pelayanan->waktu_selesai_sesi)
                        <div><p class="text-sm text-gray-500 mb-1">Total Durasi</p><p class="font-semibold text-orange-600 text-lg">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->diffInMinutes(\Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)) }} menit</p></div>
                        @endif
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <div class="flex justify-between items-start mb-5">
                        <h3 class="text-xl font-semibold text-gray-800">Survey Kepuasan</h3>
                        <p class="text-sm font-semibold text-gray-500 font-mono">{{ $pelayanan->survey_token ?? '-' }}</p>
                    </div>

                    @php
                        $surveyLabels = [
                            'fasilitas' => 'Fasilitas', 'keseluruhan' => 'Keseluruhan',
                            'efisiensi_waktu' => 'Efisiensi Waktu', 'kinerja_petugas' => 'Pelayanan Petugas',
                            'kualitas_layanan' => 'Kualitas Layanan',
                        ];
                        $survey = $pelayanan->surveyKepuasan;
                        if (is_null($survey?->rekomendasi)) { $rekomendasi = '-'; } 
                        elseif ($survey->rekomendasi == 1) { $rekomendasi = 'Merekomendasikan'; } 
                        elseif ($survey->rekomendasi == 0) { $rekomendasi = 'Tidak merekomendasikan'; } 
                        else { $rekomendasi = '-'; }
                    @endphp

                    <div class="space-y-4 mb-5">
                        @foreach($surveyLabels as $key => $label)
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $survey->skor_kepuasan[$key] ?? '-' }}/5</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-orange-500 rounded-full" style="width: {{ (($survey->skor_kepuasan[$key] ?? 0)/5)*100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 mb-1">Rekomendasi</p>
                        <p class="font-semibold text-sm text-gray-800">{{ $rekomendasi }}</p>
                    </div>

                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 mb-2">Saran / Masukan</p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            @php $saranMasukan = $pelayanan->surveyKepuasan?->saran_masukan ?? []; @endphp
                            @forelse($saranMasukan as $value)
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mt-1.5 flex-shrink-0"></span>
                                    <span>{{ $value }}</span>
                                </li>
                            @empty
                                <li><p class="text-sm text-gray-500">Belum ada saran dan masukan.</p></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Kolom Utama (Detail Pelayanan) --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                     <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-600"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">Pengunjung</p>
                                    <h3 class="font-semibold text-gray-900 text-xl">{{ $pelayanan->nama_pengunjung ?? '-' }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Kontak</p>
                                    <p class="text-md text-orange-600 font-medium">
                                        {{ $pelayanan->no_hp ?? '-' }}<br>
                                        {{ $pelayanan->email ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-md">{{ $pelayanan->instansi_pengunjung ?? '-' }}</p>
                            <div class="mt-5 pt-5 border-t border-gray-200">
                                <p class="text-sm text-gray-500 mb-2">Kebutuhan Pengunjung</p>
                                <p class="text-gray-800 leading-relaxed">{{ $pelayanan->kebutuhan_pengunjung ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Hasil & Output</h3>
                    <div class="space-y-6">
                        <div><p class="text-sm text-gray-500 mb-2">Deskripsi Hasil</p><p class="text-gray-800 leading-relaxed">{{ $pelayanan->deskripsi_hasil ?? '-' }}</p></div>
                        <div><p class="text-sm text-gray-500 mb-2">Jenis Output</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($pelayanan->jenis_output ?? [] as $output)
                                    <span class="px-3.5 py-1.5 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">{{ $output }}</span>
                                @empty
                                    <p class="text-sm text-gray-500">-</p>
                                @endforelse
                            </div>
                        </div>
                        <div><p class="text-sm text-gray-500 mb-2">Dokumen Terlampir</p>
                            <div class="space-y-2">
                                @if($pelayanan->path_surat_pengantar)<a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" class="flex items-center gap-2 text-orange-600 font-medium text-sm hover:underline" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" /></svg>Surat Pengantar</a>@endif
                                @if($pelayanan->path_dokumen_hasil)<a href="{{ Storage::url($pelayanan->path_dokumen_hasil) }}" class="flex items-center gap-2 text-orange-600 font-medium text-sm hover:underline" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" /></svg>Dokumen Hasil</a>@endif
                                @if(!$pelayanan->path_surat_pengantar && !$pelayanan->path_dokumen_hasil)<p class="text-sm text-gray-500">Tidak ada dokumen terkait.</p>@endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Tindak Lanjut & Catatan</h3>
                    <div class="space-y-6">
                        <div><p class="text-sm text-gray-500 mb-2">Tindak Lanjut Diperlukan?</p><p class="font-semibold text-gray-800 text-lg">{{ $pelayanan->perlu_tindak_lanjut == 1 ? 'Ya' : 'Tidak' }} @if($pelayanan->tanggal_tindak_lanjut) (Tanggal: {{ $pelayanan->tanggal_tindak_lanjut->translatedFormat('d F Y') }}) @endif</p>@if($pelayanan->catatan_tindak_lanjut)<p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $pelayanan->catatan_tindak_lanjut }}</p>@endif</div>
                        <div class="pt-5 border-t border-gray-200"><p class="text-sm text-gray-500 mb-2">Catatan Tambahan Internal</p><p class="text-gray-800 leading-relaxed">{{ $pelayanan->catatan_tambahan ?? '-' }}</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
