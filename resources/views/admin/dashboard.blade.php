@extends('layouts.app') <!-- Ganti dengan layout yang kamu pakai -->

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard Admin</h1>
    <p class="text-gray-600">Selamat datang di panel administrator.</p>

    <!-- Contoh konten dashboard -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow p-6 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Total Antrian Hari Ini</h2>
            <p class="text-2xl font-bold text-orange-500 mt-2">123</p>
        </div>
        <div class="bg-white shadow p-6 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Petugas</h2>
            <p class="text-2xl font-bold text-orange-500 mt-2">5</p>
        </div>
        <div class="bg-white shadow p-6 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Laporan Terakhir</h2>
            <p class="text-2xl font-bold text-orange-500 mt-2">8</p>
        </div>
    </div>
</div>
@endsection
