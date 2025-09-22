@extends('layouts.app')
@section('title', 'Formulir Survei Kebutuhan Dasar (SKD)')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">

        {{-- [DIPERBAIKI] Menggunakan 'skd_token' yang benar --}}
        <form action="{{ route('survei.skd.store', $pelayanan->skd_token) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            @csrf
            <div class="border-b border-gray-200 pb-5 mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Formulir Survei Kebutuhan Dasar (SKD)</h2>
                <p class="text-gray-600 mt-1">Terima kasih atas kesediaan Anda untuk membantu kami menjadi lebih baik.</p>
                <div class="mt-4 text-sm">
                    <span class="font-semibold">Pengunjung:</span> {{ $pelayanan->nama_pengunjung }} |
                    <span class="font-semibold">Layanan:</span> {{ $pelayanan->jenisLayanan->nama_layanan }}
                </div>
            </div>

            <div class="space-y-8">
                {{-- SESUAIKAN PERTANYAAN DI BAWAH INI DENGAN KEBUTUHAN SURVEI SKD ANDA --}}

                {{-- Contoh Pertanyaan 1: Pilihan Ganda --}}
                <div>
                    <label class="block text-base font-medium text-gray-800">1. Seberapa penting data yang Anda dapatkan untuk keperluan Anda?</label>
                    <div class="mt-3 space-y-2">
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_1" value="1" required class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Tidak Penting</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_1" value="2" class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Cukup Penting</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_1" value="3" class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Penting</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_1" value="4" class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Sangat Penting</span>
                        </label>
                    </div>
                </div>

                {{-- Contoh Pertanyaan 2: Pilihan Ganda --}}
                <div>
                    <label class="block text-base font-medium text-gray-800">2. Apakah data yang diberikan sudah sesuai dengan kebutuhan Anda?</label>
                    <div class="mt-3 space-y-2">
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_2" value="1" required class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Tidak Sesuai</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_2" value="2" class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Cukup Sesuai</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="skor_pertanyaan_2" value="3" class="h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                            <span class="ml-3 text-gray-700">Sangat Sesuai</span>
                        </label>
                    </div>
                </div>

                {{-- Contoh Pertanyaan 3: Jawaban Terbuka --}}
                <div>
                    <label for="jawaban_terbuka" class="block text-base font-medium text-gray-800">3. Data atau informasi apa lagi yang Anda butuhkan di masa mendatang yang belum tersedia?</label>
                    <textarea name="jawaban_terbuka" id="jawaban_terbuka" rows="4" class="mt-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500" placeholder="Tuliskan masukan Anda di sini..."></textarea>
                </div>

                <div class="pt-6 border-t border-gray-200 text-right">
                    <button type="submit" class="py-2.5 px-8 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                        Kirim Survei SKD
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection