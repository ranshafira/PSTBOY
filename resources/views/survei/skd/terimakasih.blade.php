@extends('layouts.app')
@section('title', 'Survei Selesai')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-xl w-full mx-auto text-center">

        <div class="bg-white p-8 sm:p-12 rounded-xl shadow-lg border border-gray-200">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Survei Telah Dikirim</h2>
            <p class="text-gray-600 mt-2 text-base sm:text-lg">
                Terima kasih banyak atas waktu dan masukan yang telah Anda berikan untuk membantu kami meningkatkan kualitas data dan pelayanan.
            </p>

            <div class="mt-8">
                <a href="{{ route('antrian.index') }}" class="w-full sm:w-auto inline-block px-8 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>

    </div>
</div>
@endsection