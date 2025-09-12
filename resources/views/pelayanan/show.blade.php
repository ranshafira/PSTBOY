{{-- resources/views/pelayanan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Mulai Pelayanan Baru')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 8.625a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM10.875 18a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 1: Mulai Sesi Pelayanan</h2>
                    <p class="text-base text-gray-600 mt-1">
                        Inisialisasi dan catat waktu mulai untuk memulai sesi pelayanan baru.
                    </p>
                </div>
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

            <div class="space-y-6">
                {{-- [DIUBAH] - Card Waktu & Panduan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    {{-- Waktu Live --}}
                    <div x-data="{
                            currentTime: '{{ now()->format('H:i:s') }}',
                            init() {
                                setInterval(() => {
                                    const now = new Date();
                                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g,':');
                                }, 1000);
                            }
                        }" class="text-center p-8">
                        <p class="text-4xl font-bold text-gray-900 tracking-wider" x-text="currentTime"></p>
                        <p class="text-base text-gray-500 mt-2">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>

                    {{-- Panduan Petugas --}}
                    <div class="border-t border-gray-200 p-8">
                        <div class="flex items-start space-x-4">
                            <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-gray-900">Panduan Jenis Layanan</h3>
                                <p class="text-base text-gray-600 mt-1">Gunakan panduan ini untuk memilih jenis layanan yang paling sesuai.</p>
                                
                                <div class="text-base text-gray-600 space-y-4 mt-6">
                                    <div><strong class="font-semibold text-gray-800">Perpustakaan:</strong><p class="mt-1">Menyediakan layanan pencarian dan peminjaman publikasi statistik, baik secara online maupun offline. Pengguna dapat mengakses koleksi buku dan publikasi dalam format cetak dan digital (PDF).</p></div>
                                    <div><strong class="font-semibold text-gray-800">Konsultasi Statistik:</strong><p class="mt-1">Fasilitas untuk berkonsultasi langsung dengan statistisi BPS mengenai data, metadata, klasifikasi, metodologi, hingga interpretasi produk statistik. Layanan ini dapat diakses secara online melalui aplikasi SILASTIK atau secara langsung di kantor BPS.</p></div>
                                    <div><strong class="font-semibold text-gray-800">Rekomendasi Statistik:</strong><p class="mt-1">Layanan yang ditujukan bagi instansi pemerintah untuk mendapatkan rekomendasi dan masukan dari BPS terkait rancangan kegiatan statistik sektoral. Tujuannya adalah untuk menghindari duplikasi dan mewujudkan Sistem Statistik Nasional yang terpadu.</p></div>
                                    <div><strong class="font-semibold text-gray-800">Pengaduan Layanan:</strong><p class="mt-1">Saluran untuk menyampaikan pengaduan, kritik, atau saran terkait layanan BPS. Pengaduan dapat dilakukan melalui berbagai media seperti situs web khusus pengaduan, email, atau kotak saran yang tersedia di kantor layanan.</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H20C20.5523 6 21 6.44772 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7C3 6.44772 3.44772 6 4 6H7ZM9 4V6H15V4H9Z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">Formulir Inisialisasi</h3>
                            <p class="text-base text-gray-600 mt-1">Isi informasi dasar untuk memulai proses pelayanan.</p>
                            
                            <div class="space-y-6 mt-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Jenis Pelayanan <span class="text-red-500">*</span></label>
                                    <div class="relative" @click.away="dropdownOpen = false">
                                        <button type="button" @click="dropdownOpen = !dropdownOpen" class="relative w-full cursor-default rounded-lg bg-white py-2.5 pl-4 pr-10 text-left text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                            <span class="block truncate" x-text="jenisLayananText"></span>
                                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                {{-- Ikon Panah yang sudah diperbaiki --}}
                                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div x-show="dropdownOpen" x-transition class="absolute mt-2 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                            @foreach($jenisLayanan as $layanan)
                                            <div @click="jenisLayananId = '{{ $layanan->id }}'; jenisLayananText = '{{ $layanan->nama_layanan }}'; dropdownOpen = false;" 
                                                 class="text-gray-900 relative cursor-default select-none py-2 px-4 hover:bg-orange-50 hover:text-orange-700 transition"><span class="block truncate">{{ $layanan->nama_layanan }}</span></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="nomor_antrian" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Antrian</label>
                                    <input type="text" id="nomor_antrian" value="{{ $antrian->nomor_antrian }}" readonly class="w-full text-base rounded-lg border-gray-300 bg-gray-100 text-gray-700 font-mono py-2.5 px-4 focus:outline-none focus:ring-0">
                                </div>
                                <div>
                                    <label for="waktu_mulai_display" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                                    <div class="flex items-center gap-3">
                                        <input type="text" id="waktu_mulai_display" x-ref="waktuMulaiDisplay" readonly class="flex-grow text-base rounded-lg border-gray-300 bg-gray-100 text-gray-700 py-2.5 px-4 focus:outline-none focus:ring-0" placeholder="Klik tombol untuk merekam waktu">
                                        <button type="button" @click="
                                            const now = new Date();
                                            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g,':');
                                            $refs.waktuMulaiDisplay.value = timeString;
                                            waktuMulaiValue = timeString;
                                            waktuTerekam = true;"
                                            class="flex-shrink-0 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-lg px-5 py-2.5 transition">
                                            Rekam Waktu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Halaman Sebelumnya</a>
                    <button type="submit" :disabled="!waktuTerekam || !jenisLayananId" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Halaman Selanjutnya
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection