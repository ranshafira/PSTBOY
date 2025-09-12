{{-- resources/views/pelayanan/selesai.blade.php --}}
@extends('layouts.app')
@section('title', 'Selesai Pelayanan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- [REFAKTOR] - Header Halaman dan Progress Bar disesuaikan dengan standar --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-1.293 1.293a1 1 0 001.414 1.414L6 12.586V8a4 4 0 018 0v4.586l1.293 1.293a1 1 0 001.414-1.414L14 11.586V8a6 6 0 00-6-6z"></path><path d="M10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 4: Selesaikan Pelayanan</h2>
                    <p class="text-base text-gray-600 mt-1">
                        Catat waktu selesai untuk menghitung total durasi pelayanan.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- [REFAKTOR] - Kartu Aksi Utama: Perekaman Waktu --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm text-center p-8">
                <h3 class="text-lg font-semibold text-gray-900">Waktu Penyelesaian</h3>
                
                @if($pelayanan->waktu_selesai_sesi)
                    {{-- State SETELAH selesai --}}
                    <p class="text-5xl font-bold text-gray-900 my-4">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i:s') }}</p>
                    <p class="text-base text-gray-500">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->translatedFormat('l, j F Y') }}</p>
                    <div class="mt-6 p-3 bg-emerald-50 text-emerald-800 rounded-lg inline-flex items-center space-x-2 text-base font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>Pelayanan Telah Selesai</span>
                    </div>
                @else
                    {{-- State SEBELUM selesai --}}
                    <p class="text-5xl font-bold text-gray-400 my-4">- - : - - : - -</p>
                    <p class="text-base text-gray-600">Tekan tombol di bawah untuk merekam waktu selesai pelayanan.</p>
                    <form action="{{ route('pelayanan.finish', $pelayanan->id) }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Rekam & Selesaikan Sekarang
                        </button>
                    </form>
                @endif
            </div>

            @if($pelayanan->waktu_selesai_sesi)
            {{-- [REFAKTOR] - Kartu-kartu ini hanya muncul SETELAH pelayanan selesai --}}
            
                {{-- Kartu Rincian Durasi --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">Rincian Durasi Pelayanan</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mt-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Waktu Mulai</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->format('H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Waktu Selesai</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi)->format('H:i') }}</p>
                                </div>
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                    <p class="text-sm font-medium text-orange-700 mb-1">Total Durasi</p>
                                    @php
                                        $mulai = \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi);
                                        $selesai = \Carbon\Carbon::parse($pelayanan->waktu_selesai_sesi);
                                        $durasi = $selesai->diffInMinutes($mulai);
                                    @endphp
                                    <p class="text-2xl font-bold text-orange-800">{{ $durasi }} <span class="text-lg font-medium">menit</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu Kode Survei --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                     <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <div class="flex-grow">
                             <h3 class="text-lg font-semibold text-gray-900">Langkah Selanjutnya: Survei Kepuasan</h3>
                             <p class="text-base text-gray-600 mt-1">Berikan Kode Survei di bawah ini kepada pengguna untuk diisi di PC Survei.</p>
                             <div class="mt-6 text-center">
                                 <div class="p-5 bg-orange-50 border-2 border-dashed border-orange-200 rounded-xl inline-block">
                                     <p class="text-5xl font-extrabold text-orange-700 tracking-wider">{{ $pelayanan->survey_token }}</p>
                                 </div>
                                 <p class="text-sm text-gray-500 mt-4">Alamat PC Survei: <a href="{{ route('survei.entry') }}" class="font-semibold text-orange-600 hover:underline" target="_blank">{{ route('survei.entry') }}</a></p>
                             </div>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- [REFAKTOR] - Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-200 mt-8">
                <a href="{{ route('pelayanan.hasil', $pelayanan->id) }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Halaman Sebelumnya</a>
                @if($pelayanan->waktu_selesai_sesi)
                <a href="{{ route('pelayanan.detail', $pelayanan->id) }}" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span>Halaman Selanjutnya</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection