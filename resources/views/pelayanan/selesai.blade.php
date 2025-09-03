@extends('layouts.app')

@section('title', 'Selesai Pelayanan')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('pelayanan.hasil', $pelayanan->id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Selesai Pelayanan</h1>
        <p class="text-gray-500">Langkah 5 dari 7: Pencatatan waktu selesai dan durasi pelayanan</p>
    </div>

    {{-- Progress Step --}}
    <div class="mb-8">
        <div class="relative w-full h-2 bg-gray-200 rounded-full">
            <div class="absolute top-0 left-0 h-2 bg-orange-500 rounded-full" style="width: {{ (5/7)*100 }}%;"></div>
        </div>
        <p class="text-center text-sm text-gray-600 mt-2">Langkah 5/7: Selesai Pelayanan</p>
    </div>

    {{-- Info Klien & Layanan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Klien</p>
                <p class="font-semibold text-gray-800">{{ $pelayanan->nama_pelanggan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jenis Layanan</p>
                <p class="font-semibold text-gray-800">{{ $pelayanan->jenisLayanan->nama_layanan }}</p>
            </div>
             <div>
                <p class="text-sm text-gray-500">No. Antrian</p>
                <p class="font-semibold text-gray-800">{{ $pelayanan->antrian->nomor_antrian }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tanggal</p>
                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->created_at)->translatedFormat('l, j F Y') }}</p>
            </div>
        </div>
    </div>
    
    {{-- Waktu Penyelesaian --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 text-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Waktu Penyelesaian</h3>
        
        @if($pelayanan->waktu_selesai_sesi)
            {{-- TAMPILAN JIKA SUDAH SELESAI --}}
            <p class="text-5xl font-bold text-gray-800 my-2">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H.i.s') }}</p>
            <p class="text-gray-500">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->translatedFormat('l, j F Y') }}</p>

            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-lg inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>Pelayanan Selesai Pukul {{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i') }}</span>
            </div>
        @else
            {{-- TAMPILAN JIKA BELUM SELESAI --}}
            <p class="text-5xl font-bold text-gray-400 my-2">- - : - - : - -</p>
            <p class="text-gray-500">Menunggu konfirmasi penyelesaian...</p>

            <form action="{{ route('pelayanan.finish', $pelayanan->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    Selesaikan Pelayanan Sekarang
                </button>
            </form>
        @endif
    </div>

    {{-- Durasi Pelayanan --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Durasi Pelayanan</h3>
        <div class="flex items-center justify-around text-center">
            <div>
                <p class="text-gray-500">Waktu Mulai</p>
                <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Waktu Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pelayanan->waktu_selesai_sesi ? \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i') : '--:--' }}</p>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                <p class="text-orange-600">Total Durasi</p>
                @php
                    $durasi = 'NaN';
                    if ($pelayanan->waktu_selesai_sesi) {
                        $mulai = \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi);
                        $selesai = \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi);
                        $durasi = $selesai->diffInMinutes($mulai);
                    }
                @endphp
                <p class="text-2xl font-bold text-orange-600">{{ $durasi }} menit</p>
            </div>
        </div>
        
        {{-- Analisis Durasi, hanya tampil jika sudah selesai --}}
        @if($pelayanan->waktu_selesai_sesi)
            @php
                $analisis = '';
                if ($durasi <= 15) {
                    $analisis = 'Pelayanan diselesaikan dengan sangat efisien.';
                } elseif ($durasi <= 30) {
                    $analisis = 'Durasi pelayanan sesuai standar waktu yang diharapkan.';
                } else {
                    $analisis = 'Pelayanan memerlukan waktu lebih dari standar.';
                }
            @endphp
            <div class="mt-6 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
                <strong>Analisis Durasi:</strong> {{ $analisis }}
            </div>
        @endif
    </div>

    {{-- Tombol Aksi Final --}}
    <div class="text-sm p-4 bg-gray-50 border rounded-lg flex items-center justify-between">
        <p class="text-gray-600">Pelayanan telah selesai dilakukan. Lanjutkan ke survei kepuasan untuk mendapatkan feedback dari klien.</p>
    </div>
    <div class="flex justify-between items-center pt-6">
        <a href="{{ route('pelayanan.hasil', $pelayanan->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
            Kembali
        </a>
        
        {{-- Tombol lanjut hanya aktif jika pelayanan sudah diselesaikan --}}
        @if($pelayanan->waktu_selesai_sesi)
    <div class="text-center p-6 bg-gray-50 border rounded-lg">
        <h3 class="text-lg font-semibold text-gray-800">Langkah Selanjutnya: Survei Kepuasan</h3>
        <p class="text-gray-600 my-2">Silakan berikan Kode Survei di bawah ini kepada pengguna dan arahkan ke PC Survei.</p>

        <div class="my-4 p-4 bg-orange-100 border-2 border-dashed border-orange-300 rounded-lg inline-block">
            <p class="text-5xl font-bold text-orange-600 tracking-widest">
                {{ $pelayanan->survey_token }}
            </p>
        </div>
        
        <p class="text-sm text-gray-500">Pengguna dapat mengunjungi alamat <span class="font-semibold">{{ route('survei.entry') }}</span> dan memasukkan kode tersebut.</p>
    </div>
@endif
    </div>
</div>
@endsection