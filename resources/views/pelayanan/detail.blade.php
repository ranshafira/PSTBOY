@extends('layouts.app')

@section('title', 'Detail Pelayanan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 px-6">
        {{-- Header --}}
        <div class="mb-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pelayanan</h1>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Berikut ringkasan lengkap hasil layanan, status penyelesaian, dan dokumen terkait
                    </p>
                </div>
            </div>
            <button 
                onclick="window.location='{{ route('pelayanan.index') }}'" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg shadow hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <span class="text-sm font-semibold text-black">Kembali</span>
            </button>
        </div>
        
        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Column - Service Info --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Service Details Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor Antrian</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->antrian->nomor_antrian ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Petugas</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->petugas->nama_lengkap ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-block px-3 py-1 rounded-full bg-orange-100 text-orange-600 font-semibold text-sm">
                                    {{ $pelayanan->status_penyelesaian 
                                        ? ucfirst(str_replace('_',' ',$pelayanan->status_penyelesaian)) 
                                        : '-' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jenis Layanan</p>
                            <p class="font-semibold text-gray-900">{{ $pelayanan->jenisLayanan->nama_layanan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Documents Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Dokumen</p>
                            <div class="space-y-2">
                                 @if($pelayanan->path_surat_pengantar)
                                    <a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" 
                                    class="flex items-center gap-2 text-orange-600 font-semibold text-sm hover:underline" target="_blank">
                                    
                                        <!-- SVG di kiri -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                        </svg>

                                        <!-- Nama dokumen -->
                                        SuratPengantar_{{ $pelayanan->nama_pelanggan }}
                                        @if($pelayanan->instansi_pelanggan)
                                            _{{ $pelayanan->instansi_pelanggan }}
                                        @endif
                                    </a>
                                @else
                                    <p class="text-gray-500 flex items-center gap-2">
                                        <!-- SVG di kiri -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                        </svg>
                                        -
                                    </p>
                                @endif

                                @if($pelayanan->path_dokumen_hasil)
                                    <a href="{{ Storage::url($pelayanan->path_dokumen_hasil) }}" 
                                        class="flex items-center gap-2 text-orange-600 font-semibold text-sm hover:underline" target="_blank">
                                         <!-- SVG di kiri -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                        </svg>

                                        DokumenHasil_{{ $pelayanan->nama_pelanggan }}
                                        @if($pelayanan->instansi_pelanggan)
                                            _{{ $pelayanan->instansi_pelanggan }}
                                        @endif
                                    </a>
                                    @else
                                    <p class="text-gray-500 flex items-center gap-2">
                                        <!-- SVG di kiri -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                        </svg>
                                        -
                                    </p>
                                @endif
                            </div>
                        </div>

                        @php
                            $jenisOutputs = $pelayanan->jenis_output ?? []; 
                        @endphp

                        <div>
                            <p class="text-sm text-gray-500 mb-2">Jenis Output</p>
                            <div class="flex gap-2">
                                @foreach($jenisOutputs as $output)
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full">{{ $output }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Middle Column - Client Info & Results --}}
            <div class="lg:col-span-6 space-y-6">
                {{-- Client Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <p class="text-sm text-gray-500">Klien</p>
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $pelayanan->nama_pelanggan ?? '-' }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Kontak</p>
                                    <p class="text-sm text-orange-600 font-medium">{{ $pelayanan->kontak_pelanggan ?? '-' }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600">{{ $pelayanan->instansi_pelanggan ?? '-' }}</p>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-1">Kebutuhan</p>
                                <p class="text-black-700">{{ $pelayanan->kebutuhan_pelanggan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Results Summary --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-start gap-4">
                    <!-- Logo -->
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                    </svg>
                    </div>

                    <!-- Konten di kanan -->
                    <div class="flex-1">
                    <h3 class="text-sm text-gray-500">Ringkasan Hasil</h3>
                    <p class="text-gray-700 mb-4">{{ $pelayanan->deskripsi_hasil ?? '-' }}</p>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                        <p class="text-sm text-gray-500 mb-2">Tindak Lanjut</p>
                        <p class="font-bold text-sm text-black-700">{{ $pelayanan->perlu_tindak_lanjut == 1 ? 'Perlu':'Tidak Perlu' }}</p>
                        <p class="font-bold text-sm text-black-700">{{ $pelayanan->tanggal_tindak_lanjut?->format('d M Y') ?? '-' }}</p>
                        <p class="text-sm text-black-700 mt-1">{{ $pelayanan->catatan_tindak_lanjut ?? '-' }}</p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Additional Notes --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex items-start gap-4">
                <!-- Logo -->
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                
                <!-- Teks -->
                <div>
                    <h3 class="text-sm text-gray-500">Catatan Tambahan</h3>
                    <p class="text-gray-700">{{ $pelayanan->catatan_tambahan ?? '-' }}</p>
                </div>
                </div>
            </div>

            {{-- Right Column - Survey & Timestamps --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Survey Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 relative">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-gray-900">Survey Kepuasan</h3>
                        <p class="text-sm font-semibold text-gray-500">{{ $pelayanan->survey_token ?? '-' }}</p>
                    </div>
                    
                    @php
                        $surveyLabels = [
                            'fasilitas' => 'Fasilitas',
                            'keseluruhan' => 'Keseluruhan',
                            'efisiensi_waktu' => 'Efisiensi Waktu',
                            'kinerja_petugas' => 'Pelayanan Petugas',
                            'kualitas_layanan' => 'Kualitas Layanan',
                        ];

                        $survey = $pelayanan->surveyKepuasan;

                        if (is_null($survey?->rekomendasi)) {
                            $rekomendasi = '-';
                        } elseif ($survey->rekomendasi == 1) {
                            $rekomendasi = 'Merekomendasikan';
                        } elseif ($survey->rekomendasi == 0) {
                            $rekomendasi = 'Tidak merekomendasikan';
                        } else {
                            $rekomendasi = '-';
                        }
                    @endphp

                    <div class="space-y-3 mb-4">
                        @foreach($surveyLabels as $key => $label)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $survey->skor_kepuasan[$key] ?? '-' }}/5
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full transition-all duration-300" 
                                        style="width: {{ (($survey->skor_kepuasan[$key] ?? 0)/5)*100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">Rekomendasi</p>
                        <p class="font-bold text-sm text-black-700">
                            {{ $rekomendasi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">Saran / Masukan</p>
                        <ul class="space-y-1">
                            @php
                                $saranMasukan = $pelayanan->surveyKepuasan?->saran_masukan ?? [];
                            @endphp

                            @if(!empty($saranMasukan))
                                @foreach($saranMasukan as $key => $value)
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        <span class="text-sm text-gray-700">{{ $value }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    <span class="text-sm text-gray-700">Belum ada saran dan masukan</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Timestamps Card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Dibuat</p>
                            <p class="font-medium text-gray-900">{{ $pelayanan->created_at?->format('d M Y, H:i') ?? '05 Sep 2025, 15:17' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Terakhir Diubah</p>
                            <p class="font-medium text-gray-900">{{ $pelayanan->updated_at?->format('d M Y, H:i') ?? '05 Sep 2025, 16:44' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection