{{-- resources/views/survei/entry.blade.php --}}
@extends('layouts.app') {{-- Atau layout publik jika ada --}}
@section('title', 'Survei Kepuasan Pelayanan')

@section('content')
{{-- [REFAKTOR TOTAL] - Mengubah layout dari kartu mengambang menjadi panel terintegrasi --}}
<div class="antialiased bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- Sisi Kiri: Branding & Informasi --}}
        <div class="bg-orange-50 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center md:text-left">
                {{-- Logo BPS --}}
                {{-- <img src="{{ asset('path/ke/logo-bps.svg') }}" alt="Logo BPS" class="h-16 w-auto mx-auto md:mx-0 mb-6"> --}}
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Selamat Datang di Survei Kepuasan Pelayanan</h1>
                <p class="text-base text-gray-600 mt-3">
                    Terima kasih telah menggunakan layanan kami. Mohon kesediaannya untuk mengisi survei singkat ini sebagai bahan evaluasi untuk kami.
                </p>
            </div>
            {{-- Placeholder untuk ilustrasi, bisa diisi dengan gambar yang relevan --}}
            <div class="mt-8 text-center">
                <svg class="w-48 h-48 mx-auto text-orange-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0118 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        {{-- Sisi Kanan: Form Input --}}
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Masukkan Kode Survei</h2>
                <p class="text-base text-gray-600 mt-1">Kode unik yang Anda dapatkan dari petugas.</p>
            </div>

            <form action="{{ route('survei.find') }}" method="POST" class="mt-6">
                @csrf
                <div>
                    <label for="pin" class="block text-sm font-medium text-gray-700 mb-1.5 sr-only">Kode Survei</label>
                    <input type="text" name="pin" id="pin"
                        class="w-full text-base sm:text-xl text-center font-semibold tracking-[0.2em] uppercase rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-3 px-4 transition"
                        placeholder="XXX-XXX" value="{{ old('pin') }}" required>
                </div>

                @if ($errors->any())
                <div class="mt-4 flex items-start space-x-3 bg-red-50 text-red-800 p-3 rounded-lg border border-red-200">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium">
                        {{ $errors->first() }}
                    </p>
                </div>
                @endif

                <div class="mt-6">
                    <button type="submit" class="w-full px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span>Mulai Survei</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection