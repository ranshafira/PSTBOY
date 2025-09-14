@extends('layouts.app')
@section('title', 'Mulai Pelayanan Baru')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Info Box --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 8.625a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM10.875 18a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd"/>
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
                  waktuTerekam: {{ $dataTerisi? 'true':'false' }}, 
                  waktuMulaiValue: '{{ $dataTerisi? \Carbon\Carbon::parse($dataTerisi->waktu_mulai_sesi)->format('H:i:s') : '' }}',
                  jenisLayananId: '{{ $dataTerisi->jenis_layanan_id ?? '' }}',
                  jenisLayananText: '{{ $dataTerisi->jenisLayanan->nama_layanan ?? 'Pilih jenis pelayanan' }}',
                  dropdownOpen: false 
              }">
            @csrf

            {{-- Hidden input sesuai field controller --}}
            <input type="hidden" name="waktu_mulai" x-model="waktuMulaiValue">
            <input type="hidden" name="jenis_layanan_id" x-model="jenisLayananId">

            <div class="space-y-6">
                {{-- Card Waktu --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
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
                </div>

                {{-- Formulir --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900">Formulir Inisialisasi</h3>
                    <p class="text-base text-gray-600 mt-1">Isi informasi dasar untuk memulai proses pelayanan.</p>

                    <div class="space-y-6 mt-6">
                        {{-- Pilih Layanan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Jenis Pelayanan <span class="text-red-500">*</span></label>
                            <div class="relative" @click.away="dropdownOpen = false">
                                <button type="button" @click="dropdownOpen = !dropdownOpen" class="relative w-full rounded-lg bg-white py-2.5 pl-4 pr-10 text-left text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                    <span x-text="jenisLayananText"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 0 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </button>
                                <div x-show="dropdownOpen" x-transition class="absolute mt-2 max-h-60 w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                                    @foreach($jenisLayanan as $layanan)
                                        <div @click="jenisLayananId = '{{ $layanan->id }}'; jenisLayananText = '{{ $layanan->nama_layanan }}'; dropdownOpen = false;" class="cursor-pointer py-2 px-4 hover:bg-orange-50 hover:text-orange-700">
                                            {{ $layanan->nama_layanan }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Nomor Antrian --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Antrian</label>
                            <input type="text" value="{{ $antrian->nomor_antrian }}" readonly class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 py-2.5 px-4">
                        </div>

                        {{-- Rekam Waktu --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                            <div class="flex items-center gap-3">
                                <input type="text" x-ref="waktuMulaiDisplay" readonly 
                                       value="{{ $dataTerisi? \Carbon\Carbon::parse($dataTerisi->waktu_mulai_sesi)->format('H:i:s') : '' }}"
                                       placeholder="Klik tombol untuk merekam waktu"
                                       class="flex-grow rounded-lg border-gray-300 bg-gray-100 text-gray-700 py-2.5 px-4">
                                <button type="button" @click="
                                    const now = new Date();
                                    const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g,':');
                                    $refs.waktuMulaiDisplay.value = timeString;
                                    waktuMulaiValue = timeString;
                                    waktuTerekam = true;" 
                                    class="bg-orange-500 hover:bg-orange-600 text-white rounded-lg px-5 py-2.5">
                                    Rekam Waktu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Navigasi --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-6 py-3 border rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100">Halaman Sebelumnya</a>
                    <button type="submit" :disabled="!waktuTerekam || !jenisLayananId" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 disabled:bg-gray-300">
                        Halaman Selanjutnya
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
