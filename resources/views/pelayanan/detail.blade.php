@extends('layouts.app')
@section('title', 'Detail Pelayanan')

@section('content')
<div class="font-sans antialiased bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8 flex flex-wrap gap-3 items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pelayanan</h1>
                <p class="text-md text-gray-500 mt-1">Ringkasan lengkap hasil layanan, status, dan dokumen terkait.</p>
            </div>

            <div class="flex gap-3 ml-auto">
               @if (auth()->user()->role_id == 2)
                    <a href="{{ route('pelayanan.langkah1.edit', $pelayanan->id) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 border transition text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        <span>Edit Pengunjung</span>
                    </a>

                    <a href="{{ route('pelayanan.langkah2.edit', $pelayanan->id) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 border transition text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>Edit Layanan</span>
                    </a>
                @endif
                <a href="{{ route('riwayat.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Kolom Kiri (Sidebar Info) --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor Antrian</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->antrian->nomor_antrian ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Petugas</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->petugas->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status Pelayanan</p><span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-sm">{{ $pelayanan->status_penyelesaian ? ucfirst(str_replace('_',' ',$pelayanan->status_penyelesaian)) : '-' }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jenis Layanan</p>
                            <p class="font-semibold text-gray-900 text-lg">{{ $pelayanan->jenisLayanan->nama_layanan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Waktu & Durasi</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Waktu Mulai</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Waktu Selesai</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->waktu_selesai_sesi ? \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->translatedFormat('d F Y, H:i') : '-' }}</p>
                        </div>
                        @if($pelayanan->waktu_mulai_sesi && $pelayanan->waktu_selesai_sesi)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Durasi</p>
                            <p class="font-semibold text-orange-600 text-lg">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->diffInMinutes(\Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)) }} menit</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- [BARU] Kartu Survei Internal --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <div class="flex justify-between items-start mb-5">
                        <h3 class="text-xl font-semibold text-gray-800">Survei Internal</h3>
                        <p class="text-sm font-semibold text-gray-500 font-mono">{{ $pelayanan->survey_token ?? '-' }}</p>
                    </div>

                    @if($pelayanan->surveyInternal)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Kepuasan Keseluruhan</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->surveyInternal->skor_keseluruhan }}/5</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pelayanan Petugas</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->surveyInternal->skor_petugas }}/5</p>
                        </div>
                        @if($pelayanan->surveyInternal->saran)
                        <div class="border-t border-gray-200 pt-3">
                            <p class="text-sm text-gray-500 mb-1">Saran / Masukan</p>
                            <p class="text-sm text-gray-700 italic">"{{ $pelayanan->surveyInternal->saran }}"</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Belum diisi.</p>
                    @endif
                </div>
            </div>

            {{-- Kolom Utama (Detail Pelayanan) --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">Pengunjung</p>
                                    <h3 class="font-semibold text-gray-900 text-xl">{{ $pelayanan->nama_pengunjung ?? '-' }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Kontak</p>
                                    <p class="text-md text-orange-600 font-medium">{{ $pelayanan->no_hp ?? '-' }}<br>{{ $pelayanan->email ?? '-' }}</p>
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
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Deskripsi Hasil</p>
                            <p class="text-gray-800 leading-relaxed">{{ $pelayanan->deskripsi_hasil ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Jenis Output</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($pelayanan->jenis_output ?? [] as $output)
                                <span class="px-3.5 py-1.5 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">{{ $output }}</span>
                                @empty
                                <p class="text-sm text-gray-500">-</p>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Dokumen Terlampir</p>
                            <div class="space-y-2">
                                @if($pelayanan->path_surat_pengantar)<a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" class="flex items-center gap-2 text-orange-600 font-medium text-sm hover:underline" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                    </svg>Surat Pengantar</a>@endif
                                @if($pelayanan->path_dokumen_hasil)<a href="{{ Storage::url($pelayanan->path_dokumen_hasil) }}" class="flex items-center gap-2 text-orange-600 font-medium text-sm hover:underline" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                    </svg>Dokumen Hasil</a>@endif
                                @if(!$pelayanan->path_surat_pengantar && !$pelayanan->path_dokumen_hasil)<p class="text-sm text-gray-500">Tidak ada dokumen terkait.</p>@endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-7">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Tindak Lanjut & Catatan</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Tindak Lanjut Diperlukan?</p>
                            <p class="font-semibold text-gray-800 text-lg">{{ $pelayanan->perlu_tindak_lanjut == 1 ? 'Ya' : 'Tidak' }} @if($pelayanan->tanggal_tindak_lanjut) (Tanggal: {{ \Carbon\Carbon::parse($pelayanan->tanggal_tindak_lanjut)->translatedFormat('d F Y') }}) @endif</p>@if($pelayanan->catatan_tindak_lanjut)<p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $pelayanan->catatan_tindak_lanjut }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection