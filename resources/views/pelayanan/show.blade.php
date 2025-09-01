@extends('layouts.app')

@section('title', 'Mulai Pelayanan Baru')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Mulai Layanan Baru</h1>
        <p class="text-gray-500">Langkah 1 dari 7: Inisialisasi dan timestamp mulai pelayanan</p>
    </div>

    {{-- Progress Step --}}
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm">
            <span class="px-4 py-1.5 bg-orange-500 text-white rounded-full font-semibold">
                Langkah 1/7
            </span>
            <span class="font-medium text-gray-700">Mulai Layanan</span>
        </div>
    </div>
    
    <div class="space-y-6">
        {{-- Kartu Waktu Saat Ini --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5" 
             x-data="realtimeClock()" x-init="start()">
            <div class="flex items-center space-x-3">
                <div class="bg-orange-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-500 text-sm">Waktu Saat Ini</h3>
                    <div class="flex items-baseline space-x-4">
                        <p class="text-3xl font-bold text-gray-900 tracking-wider" x-text="time"></p>
                        <p class="text-sm text-gray-500" x-text="date"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Penjelasan Layanan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Jenis Layanan (Panduan Petugas)</h3>
            <div class="text-sm text-gray-600 space-y-3">
                <div>
                    <strong class="text-gray-900">Perpustakaan:</strong>
                    <p class="pl-2">Layanan untuk peminjaman dan pencarian publikasi, buku, atau referensi statistik yang tersedia di perpustakaan. Cocok untuk mahasiswa atau peneliti yang butuh referensi fisik.</p>
                </div>
                <div>
                    <strong class="text-gray-900">Konsultasi Statistik:</strong>
                    <p class="pl-2">Layanan konsultasi langsung dengan statistisi mengenai metodologi, kuesioner, cara pengolahan data, atau interpretasi hasil statistik. Biasanya membutuhkan surat pengantar.</p>
                </div>
                {{-- TYPO SUDAH DIPERBAIKI DI SINI --}}
                <div> 
                    <strong class="text-gray-900">Pojok Statistik:</strong>
                    <p class="pl-2">Layanan edukasi dan literasi data statistik yang disediakan di lingkungan universitas atau pusat pendidikan untuk membantu mahasiswa dalam pengerjaan tugas akhir.</p>
                </div>
            </div>
        </div>

        {{-- Kartu Form Inisialisasi --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <form action="{{ route('pelayanan.start', $nomor) }}" method="POST" 
                  x-data="{ waktuMulai: '' }">
                @csrf
                
                <div class="flex items-start space-x-3 mb-6">
                    <svg class="w-6 h-6 text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H20C20.5523 6 21 6.44772 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7C3 6.44772 3.44772 6 4 6H7ZM9 4V6H15V4H9Z"></path></svg>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Inisialisasi Pelayanan</h3>
                        <p class="text-sm text-gray-500">Isi informasi dasar untuk memulai proses pelayanan.</p>
                    </div>
                </div>

                <div class="space-y-5 pl-9">
                    {{-- Form Fields --}}
                    <div>
                        <label for="jenis_layanan_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jenis Pelayanan</label>
                        <select id="jenis_layanan_id" name="jenis_layanan_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-orange-200 focus:border-orange-400 transition">
                            <option value="" disabled selected>-- Pilih jenis layanan --</option>
                            @foreach($jenisLayanan as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nomor_antrian" class="block text-sm font-medium text-gray-700 mb-1">Nomor Antrian</label>
                        <input type="text" id="nomor_antrian" value="{{ $nomor }}" readonly
                            class="w-full border-gray-300 rounded-lg bg-gray-100 px-3 py-2 text-gray-700 font-mono focus:outline-none">
                    </div>
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                        <input type="text" id="waktu_mulai" name="waktu_mulai" x-model="waktuMulai" readonly
                            class="w-full border-gray-300 rounded-lg bg-gray-100 px-3 py-2" placeholder="Klik tombol untuk merekam waktu">
                        <div class="mt-2">
                            <template x-if="!waktuMulai">
                                <button type="button"
                                    @click="waktuMulai = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':')"
                                    class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                                    Rekam waktu sekarang
                                </button>
                            </template>
                            <template x-if="waktuMulai">
                                <button type="button" @click="waktuMulai = ''"
                                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                                    Batalkan
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="window.location.href='{{ url('/dashboard') }}'"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        :disabled="!waktuMulai.trim()"
                        class="px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Mulai Pelayanan →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function realtimeClock() {
        return {
            time: '...',
            date: '...',
            start() {
                const update = () => {
                    const now = new Date();
                    this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
                    this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                };
                update();
                setInterval(update, 1000);
            }
        }
    }
</script>
@endpush