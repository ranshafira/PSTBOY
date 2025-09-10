{{-- resources/views/pelayanan/selesai.blade.php --}}
@extends('layouts.app')
@section('title', 'Selesai Pelayanan')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('pelayanan.hasil', $pelayanan->id) }}" class="text-sm font-medium text-gray-600 hover:text-orange-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Selesaikan Pelayanan</h1>
            <p class="text-md text-gray-500 mt-1">Langkah 4 dari 6: Pencatatan waktu selesai dan durasi pelayanan.</p>
        </div>

        <div class="mb-10">
            <p class="text-sm text-gray-600 mb-2">Progress: Langkah 4 dari 6</p>
            <div class="relative w-full h-2 bg-gray-200 rounded-full"><div class="absolute top-0 left-0 h-2 bg-orange-500 rounded-full" style="width: {{ (4/6)*100 }}%;"></div></div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7 text-center mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Waktu Penyelesaian</h3>
            @if($pelayanan->waktu_selesai_sesi)
                <p class="text-6xl font-bold text-gray-800 my-4">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i:s') }}</p>
                <p class="text-gray-600 text-lg">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->translatedFormat('l, j F Y') }}</p>
                <div class="mt-6 p-3 bg-emerald-50 text-emerald-800 rounded-lg inline-flex items-center space-x-2 text-md font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Pelayanan Selesai Pukul {{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i') }}</span>
                </div>
            @else
                <p class="text-6xl font-bold text-gray-400 my-4">- - : - -</p>
                <p class="text-gray-600 text-lg">Tekan tombol di bawah untuk merekam waktu selesai.</p>
                <form action="{{ route('pelayanan.finish', $pelayanan->id) }}" method="POST" class="mt-6">
    @csrf
    <button type="submit" class="px-7 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">Rekam & Selesaikan Sekarang</button>
</form>
            @endif
        </div>

        @if($pelayanan->waktu_selesai_sesi)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Rincian Durasi Pelayanan</h3>
            <div class="grid grid-cols-3 gap-6 text-center">
                <div><p class="text-sm text-gray-500 mb-1">Waktu Mulai</p><p class="text-3xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->format('H:i') }}</p></div>
                <div><p class="text-sm text-gray-500 mb-1">Waktu Selesai</p><p class="text-3xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i') }}</p></div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <p class="text-sm text-orange-600 mb-1">Total Durasi</p>
                    @php
                        $mulai = \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi);
                        $selesai = \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi);
                        $durasi = $selesai->diffInMinutes($mulai);
                    @endphp
                    <p class="text-3xl font-bold text-orange-700">{{ $durasi }} <span class="text-xl font-semibold">menit</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Langkah Selanjutnya: Survei Kepuasan</h3>
            <p class="text-gray-600 text-md my-4">Berikan Kode Survei di bawah ini kepada pengguna.</p>
            <div class="my-6 p-5 bg-orange-100 border-2 border-dashed border-orange-300 rounded-xl inline-block">
                <p class="text-6xl font-extrabold text-orange-700 tracking-wider">{{ $pelayanan->survey_token }}</p>
            </div>
            <p class="text-sm text-gray-500 mt-4">Arahkan pengguna ke PC Survei dengan alamat <a href="{{ route('survei.entry') }}" class="font-semibold text-orange-600 hover:underline" target="_blank">
    {{ route('survei.entry') }}
</a></p>
        </div>
        @endif
        
        <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-8">
            <a href="{{ route('pelayanan.hasil', $pelayanan->id) }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Kembali</a>
            @if($pelayanan->waktu_selesai_sesi)
            <a href="{{ route('pelayanan.detail', $pelayanan->id) }}" class="px-7 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">Lihat Detail & Selesai</a>
            @endif
        </div>
    </div>
</div>
@endsection