@extends('layouts.app')
@section('title', 'Pelayanan Selesai')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full mx-auto text-center">

        <div class="bg-white p-8 sm:p-12 rounded-xl shadow-lg border border-gray-200">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Pelayanan Telah Selesai</h2>
            <p class="text-gray-600 mt-2 text-base sm:text-lg">
                Terima kasih telah menggunakan layanan kami.
            </p>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-3 my-5">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-orange-50 border border-dashed border-orange-300 rounded-lg p-6 mt-8">
                <h3 class="font-semibold text-gray-800">Langkah Selanjutnya: Survei Kebutuhan Dasar (SKD)</h3>
                <p class="text-gray-600 mt-2">
                    Untuk melengkapi proses, mohon kesediaan Anda untuk mengisi Survei Kebutuhan Dasar (SKD) menggunakan token di bawah ini.
                </p>
                <div class="my-4">
                    <p class="text-sm text-gray-500">Token SKD Anda:</p>
                    <p class="text-3xl font-mono font-bold tracking-widest text-orange-600 bg-white py-2 px-4 rounded-md inline-block border border-orange-200 mt-1">
                        {{ $pelayanan->skd_token }}
                    </p>
                </div>
                <a href="{{ route('survei.skd.entry') }}" class="w-full sm:w-auto inline-block px-8 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition">
                    Lanjut ke Halaman SKD
                </a>
            </div>
        </div>
    </div>
</div>
@endsection