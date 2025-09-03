@extends('layouts.app')
@section('title', 'Formulir Survei Kepuasan')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('survei.entry') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Survei Kepuasan</h1>
        <p class="text-gray-500">Langkah 6 dari 7: Evaluasi kepuasan pelayanan dari klien</p>
    </div>

    {{-- Progress & Info Klien --}}
    <div class="mb-8 space-y-4">
        <div class="relative w-full h-2 bg-gray-200 rounded-full">
            <div class="absolute top-0 left-0 h-2 bg-orange-500 rounded-full" style="width: {{ (6/7)*100 }}%;"></div>
        </div>
        <div class="bg-white border rounded-xl p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><p class="text-gray-500">Klien</p><p class="font-semibold">{{ $pelayanan->nama_pelanggan }}</p></div>
            <div><p class="text-gray-500">No. Antrian</p><p class="font-semibold">{{ $pelayanan->antrian->nomor_antrian }}</p></div>
            <div><p class="text-gray-500">Durasi</p><p class="font-semibold">{{ $pelayanan->waktu_selesai_sesi ? $pelayanan->waktu_selesai_sesi->diffInMinutes($pelayanan->waktu_mulai_sesi) : '-' }} menit</p></div>
            <div><p class="text-gray-500">Jenis Layanan</p><p class="font-semibold">{{ $pelayanan->jenisLayanan->nama_layanan }}</p></div>
        </div>
    </div>

    <form action="{{ route('survei.store', $pelayanan->survey_token) }}" method="POST" class="space-y-6">
        @csrf

        {{-- BAGIAN 1: PENILAIAN KEPUASAN --}}
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">⭐ Penilaian Kepuasan <span class="text-red-500">*</span></h3>
            <p class="text-sm text-gray-500 mb-6">Berikan penilaian untuk berbagai aspek pelayanan.</p>

            @php
            $aspek = [
                'skor_keseluruhan' => 'Kepuasan Keseluruhan',
                'skor_kualitas' => 'Kualitas Layanan',
                'skor_petugas' => 'Kinerja Petugas',
                'skor_efisiensi' => 'Efisiensi Waktu',
                'skor_fasilitas' => 'Fasilitas',
            ];
            $emojis = ['😠', '😕', '😐', '😊', '😀'];
            @endphp
            
            <div class="space-y-6">
                @foreach ($aspek as $name => $label)
                <div>
                    <label class="block text-base font-semibold text-gray-700">{{ $label }}</label>
                    <div class="mt-3 flex items-center justify-between px-2">
                        @for ($i = 1; $i <= 5; $i++)
                        <div class="text-center">
                            <input type="radio" name="{{ $name }}" value="{{ $i }}" id="{{ $name }}_{{ $i }}" class="w-6 h-6 text-orange-500 focus:ring-orange-500 focus:ring-2 border-gray-300">
                            <label for="{{ $name }}_{{ $i }}" class="mt-2 block text-2xl cursor-pointer">{{ $emojis[$i-1] }}</label>
                            <label for="{{ $name }}_{{ $i }}" class="block text-xs text-gray-500 cursor-pointer">{{ $i }}</label>
                        </div>
                        @endfor
                    </div>
                    @error($name) <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endforeach
            </div>
        </div>

        {{-- BAGIAN 2: REKOMENDASI --}}
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">👍 Rekomendasi</h3>
            <p class="text-sm text-gray-500 mb-4">Apakah Anda akan merekomendasikan layanan ini kepada orang lain? <span class="text-red-500">*</span></p>
            <div class="flex items-center space-x-6">
                 <label class="flex items-center"><input type="radio" name="rekomendasi" value="1" class="h-4 w-4 text-orange-600"> <span class="ml-2">Ya</span></label>
                 <label class="flex items-center"><input type="radio" name="rekomendasi" value="0" class="h-4 w-4 text-orange-600"> <span class="ml-2">Tidak</span></label>
            </div>
             @error('rekomendasi') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- BAGIAN 3: MASUKAN & SARAN --}}
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">💬 Masukan & Saran</h3>
            <p class="text-sm text-gray-500 mb-6">Berikan masukan atau saran untuk perbaikan pelayanan.</p>
            <div class="space-y-4">
                <div>
                    <label for="feedback_pelayanan" class="block text-sm font-medium text-gray-700">Feedback Pelayanan</label>
                    <textarea name="feedback_pelayanan" id="feedback_pelayanan" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500" placeholder="Bagikan pengalaman Anda selama mendapatkan pelayanan..."></textarea>
                </div>
                 <div>
                    <label for="saran_perbaikan" class="block text-sm font-medium text-gray-700">Saran Perbaikan</label>
                    <textarea name="saran_perbaikan" id="saran_perbaikan" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500" placeholder="Saran untuk meningkatkan kualitas pelayanan di masa mendatang..."></textarea>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center pt-6">
            <a href="{{ route('survei.entry') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                Simpan & Selesai →
            </button>
        </div>
    </form>
</div>
@endsection