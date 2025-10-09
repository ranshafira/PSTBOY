@extends('layouts.survei')
@section('title', 'Survei Kebutuhan Data')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full mx-auto text-center">

        <div class="bg-white p-8 sm:p-12 rounded-xl shadow-lg border border-gray-200">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-2xl font-bold text-gray-900">Pelayanan Telah Selesai</h2>
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-3 my-5">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-orange-50 border border-dashed border-orange-300 rounded-lg p-6 mt-8">
                <p class="text-gray-600 mt-2">
                    Mohon kesediaan Anda untuk mengisi Survei Kebutuhan Data (SKD) dengan scan barcode di bawah ini.
                </p>
                <p class="text-gray-600 mt-2">
                    s.bps.go.id/skd2025_3309
                </p>
                <div class="flex justify-center my-4">
                    <img src="{{ asset('build/assets/images/qr.png') }}" alt="qrcode SKD" class="w-48 h-auto">
                </div>
                <a href="https://s.bps.go.id/skd2025_3309" class="w-full sm:w-auto inline-block px-8 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition">
                    Lanjut ke Halaman SKD
                </a>
            </div>
        </div>
    </div>
</div>
@endsection