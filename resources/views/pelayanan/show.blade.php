{{-- resources/views/pelayanan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Mulai Pelayanan Baru')

@section('content')
<div class="font-sans antialiased bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-orange-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Mulai Layanan Baru</h1>
            <p class="text-md text-gray-500 mt-1">Langkah 1 dari 6: Inisialisasi dan timestamp mulai pelayanan.</p>
        </div>

        {{-- Stepper Sederhana --}}
        <div class="mb-10">
            <div class="flex items-center space-x-3 text-sm">
                <span class="px-4 py-1.5 bg-orange-500 text-white rounded-full font-semibold">Langkah 1/6</span>
                <span class="font-medium text-gray-700 text-base">Mulai Layanan</span>
            </div>
        </div>
        
        <form action="{{ route('pelayanan.start', $antrian->id) }}" method="POST" 
              x-data="{ 
                  waktuTerekam: false, 
                  waktuMulaiValue: '',
                  jenisLayananId: '',
                  jenisLayananText: 'Pilih jenis pelayanan',
                  dropdownOpen: false 
              }">
            @csrf
            <input type="hidden" name="waktu_mulai" x-model="waktuMulaiValue">
            <input type="hidden" name="jenis_layanan_id" x-model="jenisLayananId">

            <div class="space-y-8">
                {{-- Card Waktu & Panduan (Digabung agar lebih menyatu) --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7">
                    {{-- Waktu Live --}}
                    <div x-data="{
                            currentTime: '{{ now()->format('H:i:s') }}',
                            init() {
                                setInterval(() => {
                                    const now = new Date();
                                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g,':');
                                }, 1000);
                            }
                        }" class="text-center mb-8">
                        <p class="text-5xl font-bold text-gray-800 tracking-wider" x-text="currentTime"></p>
                        <p class="text-sm text-gray-500 mt-1">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>

                    <hr class="border-gray-200">

                    {{-- Panduan Petugas --}}
                    <div class="mt-8">
                        <div class="flex items-start space-x-4 mb-5">
                            <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800">Informasi Jenis Layanan (Panduan Petugas)</h3>
                                <p class="text-sm text-gray-600 mt-1">Gunakan panduan ini untuk memilih jenis layanan yang paling sesuai.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 space-y-4 pl-10">
                            <div><strong class="text-gray-900 font-semibold">Perpustakaan:</strong><p class="mt-1">Layanan peminjaman dan pencarian publikasi/buku statistik. Cocok untuk mahasiswa atau peneliti.</p></div>
                            <div><strong class="text-gray-900 font-semibold">Konsultasi Statistik:</strong><p class="mt-1">Konsultasi langsung dengan statistisi mengenai metodologi, kuesioner, atau interpretasi hasil statistik.</p></div>
                            <div><strong class="text-gray-900 font-semibold">Pojok Statistik:</strong><p class="mt-1">Layanan edukasi dan literasi data di lingkungan universitas untuk membantu pengerjaan tugas akhir.</p></div>
                        </div>
                    </div>
                </div>
            
                {{-- Card Inisialisasi Pelayanan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H20C20.5523 6 21 6.44772 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7C3 6.44772 3.44772 6 4 6H7ZM9 4V6H15V4H9Z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Inisialisasi Pelayanan</h3>
                            <p class="text-sm text-gray-600 mt-1">Isi informasi dasar untuk memulai proses pelayanan.</p>
                        </div>
                    </div>

                    <div class="space-y-6 pl-10">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jenis Pelayanan <span class="text-red-500">*</span></label>
                            <div class="relative" @click.away="dropdownOpen = false">
                                <button type="button" @click="dropdownOpen = !dropdownOpen" class="relative w-full cursor-default rounded-lg bg-white py-2.5 pl-4 pr-10 text-left border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition">
                                    <span class="block truncate" x-text="jenisLayananText"></span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"><svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3z" clip-rule="evenodd" transform="rotate(180 10 10)" /></svg></span>
                                </button>
                                <div x-show="dropdownOpen" x-transition class="absolute mt-2 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm z-10">
                                    @foreach($jenisLayanan as $layanan)
                                    <div @click="jenisLayananId = '{{ $layanan->id }}'; jenisLayananText = '{{ $layanan->nama_layanan }}'; dropdownOpen = false;" 
                                         class="text-gray-900 relative cursor-default select-none py-2 px-4 hover:bg-orange-50 hover:text-orange-700 transition"><span class="block truncate">{{ $layanan->nama_layanan }}</span></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="nomor_antrian" class="block text-sm font-medium text-gray-700 mb-2">Nomor Antrian</label>
                            <input type="text" id="nomor_antrian" value="{{ $antrian->nomor_antrian }}" readonly class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 font-mono py-2.5 px-4 focus:outline-none focus:ring-0">
                        </div>
                        <div>
                            <label for="waktu_mulai_display" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                            <div class="flex items-center gap-3">
                                <input type="text" id="waktu_mulai_display" x-ref="waktuMulaiDisplay" readonly class="flex-grow rounded-lg border-gray-300 bg-gray-100 text-gray-700 py-2.5 px-4 focus:outline-none focus:ring-0" placeholder="Klik tombol untuk merekam waktu">
                                <button type="button" @click="
                                    const now = new Date();
                                    const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g,':');
                                    $refs.waktuMulaiDisplay.value = timeString;
                                    waktuMulaiValue = timeString;
                                    waktuTerekam = true;"
                                    class="flex-shrink-0 text-sm text-white bg-orange-500 hover:bg-orange-600 font-medium rounded-lg px-5 py-2.5 transition">
                                    Rekam Waktu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6">
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" :disabled="!waktuTerekam || !jenisLayananId" class="px-7 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Mulai Pelayanan →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection