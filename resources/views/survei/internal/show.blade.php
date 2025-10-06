@extends('layouts.app')
@section('title', 'Formulir Survei Kepuasan Pelayanan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12" x-data="{ ratingKeseluruhan: 0, ratingPetugas: 0 }">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Survei Kepuasan Pelayanan</h2>
            <p class="text-base text-gray-600 mt-1">Penilaian Anda sangat berarti untuk kami.</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('survei.internal.store', $pelayanan->id) }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                <div class="space-y-8">

                    {{-- Pertanyaan 1: Kepuasan Keseluruhan --}}
                    <div>
                        <label class="block text-base font-semibold text-gray-800 text-center">1. Bagaimana tingkat kepuasan Anda secara keseluruhan terhadap pelayanan yang diberikan?</label>
                        <div class="mt-4 flex justify-center space-x-2 sm:space-x-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                <input type="radio" name="skor_keseluruhan" value="{{ $i }}" id="keseluruhan_{{ $i }}" class="sr-only peer" required @click="ratingKeseluruhan = {{ $i }}">
                                <label for="keseluruhan_{{ $i }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full border border-gray-200 bg-gray-50 cursor-pointer transition text-gray-400 hover:bg-orange-100 hover:border-orange-300 peer-checked:bg-orange-500 peer-checked:border-orange-500 peer-checked:text-white">
                                    <span class="text-xl font-bold">{{ $i }}</span>
                                </label>
                        </div>
                        @endfor
                    </div>
                </div>

                {{-- Pertanyaan 2: Pelayanan Petugas --}}
                <div>
                    <label class="block text-base font-semibold text-gray-800 text-center">2. Bagaimana Anda menilai keramahan dan profesionalisme petugas kami?</label>
                    <div class="mt-4 flex justify-center space-x-2 sm:space-x-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative">
                            <input type="radio" name="skor_petugas" value="{{ $i }}" id="petugas_{{ $i }}" class="sr-only peer" required @click="ratingPetugas = {{ $i }}">
                            <label for="petugas_{{ $i }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full border border-gray-200 bg-gray-50 cursor-pointer transition text-gray-400 hover:bg-orange-100 hover:border-orange-300 peer-checked:bg-orange-500 peer-checked:border-orange-500 peer-checked:text-white">
                                <span class="text-xl font-bold">{{ $i }}</span>
                            </label>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8" x-show=" (ratingKeseluruhan > 0 && ratingKeseluruhan < 4) || (ratingPetugas > 0 && ratingPetugas < 4) " x-transition>
                <h3 class="text-lg font-semibold text-gray-900">Kritik & Saran</h3>
                <div class="mt-4">
                    <label for="saran" class="sr-only">Kritik & Saran</label>
                    <textarea name="saran" id="saran" rows="4" class="antialiased w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" placeholder="Tuliskan masukan Anda di sini..."></textarea>
                </div>
            </div>
    </div>
</div>



<div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
    <a href="{{ route('pelayanan.langkah2.create', $pelayanan->id) }}" class="w-full sm:w-auto px-6 py-3 border rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 text-center">Halaman Sebelumnya</a>
    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
        Kirim Penilaian
    </button>
</div>
</form>
</div>
</div>
@endsection