@extends('layouts.app')

@section('title', 'Mulai Pelayanan')

@section('content')
<div class="max-w-3xl mx-auto py-10">
    <div class="mb-6 flex items-center gap-3">
        <span class="px-3 py-1 text-white bg-orange-400/90 rounded-full text-xs font-semibold">
            Langkah 1 / 7
        </span>
        <span class="text-gray-700 font-semibold">Mulai Layanan</span>
    </div>

    <div class="bg-white border rounded-xl shadow-sm p-6">
        <form action="{{ route('pelayanan.start', $nomor) }}" method="POST" x-data="{ started: false, time: '' }">
            @csrf

            {{-- Nomor Antrian --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Antrian</label>
                <input type="text" value="{{ $nomor }}" readonly
                    class="w-full border-gray-200 rounded-lg bg-gray-50 px-3 py-2 text-gray-600">
            </div>

            {{-- Dropdown Layanan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Layanan</label>
                <select name="jenis_layanan_id" required
                    class="w-full border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-orange-300">
                    <option value="">-- Pilih Jenis Layanan --</option>
                    @foreach($jenisLayanan as $layanan)
                        <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih sesuai kebutuhan pelanggan.</p>
            </div>

            {{-- Waktu Mulai --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                <input type="text" x-model="time" readonly name="waktu_mulai"
                    class="w-full border-gray-200 rounded-lg bg-gray-50 px-3 py-2 text-gray-600">

                <button type="button"
                    @click="started = true; time = new Date().toLocaleTimeString('id-ID');"
                    class="mt-2 bg-orange-400 text-white px-4 py-2 rounded-lg hover:bg-orange-500 transition">
                    Rekam Waktu Mulai
                </button>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-between items-center">
                <a href="{{ url('/dashboard') }}"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    ← Kembali
                </a>

                <button type="submit"
                    class="bg-orange-400 text-white font-semibold py-2 px-6 rounded-lg hover:bg-orange-500 transition">
                    Lanjutkan →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
