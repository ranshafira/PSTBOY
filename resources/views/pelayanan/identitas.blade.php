{{-- resources/views/pelayanan/identitas.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengisian Identitas Pengunjung')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.5a5.5 5.5 0 0 1 3.1 10 9 9 0 0 1 5.9 8.2.75.75 0 1 1-1.5 0A7.5 7.5 0 0 0 5.5 20.7a.75.75 0 0 1-1.5 0 9 9 0 0 1 5.9-8.2A5.5 5.5 0 0 1 12 2.5Z"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 2: Identitas & Dokumen</h2>
                    <p class="text-base text-gray-600 mt-1">Lengkapi data identitas pengunjung dan upload dokumen pendukung jika ada.</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('pelayanan.storeIdentitas', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">

                {{-- Identitas --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Data Identitas Pengunjung</h3>
                    <p class="text-base text-gray-600 mb-6">Lengkapi informasi dasar mengenai pengunjung.</p>

                    <div class="space-y-6">
                        {{-- Nama --}}
                        <div>
                            <label for="nama_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pengunjung" id="nama_pengunjung"
                                value="{{ old('nama_pengunjung', $pelayanan->nama_pengunjung) }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 text-base transition"
                                required placeholder="Masukkan nama lengkap">
                        </div>

                        {{-- Instansi + Gender --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="instansi_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">Instansi/Organisasi</label>
                                <input type="text" name="instansi_pengunjung" id="instansi_pengunjung"
                                    value="{{ old('instansi_pengunjung', $pelayanan->instansi_pengunjung) }}"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 text-base transition"
                                    placeholder="Nama instansi atau organisasi">
                            </div>
                           {{-- Jenis Kelamin --}}
                        <div x-data="{ 
                            open: false, 
                            selectedValue: '{{ old('jenis_kelamin', $pelayanan->jenis_kelamin) }}', 
                            selectedText: '{{ old('jenis_kelamin', $pelayanan->jenis_kelamin) ?: '-- Pilih --' }}' 
                        }" class="relative">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Kelamin
                            </label>

                            {{-- Hidden input --}}
                            <input type="hidden" name="jenis_kelamin" x-model="selectedValue">

                            {{-- Trigger --}}
                            <button type="button" @click="open = !open" class="relative w-full cursor-default rounded-lg bg-white py-2.5 pl-4 pr-10 text-left text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                <span class="block truncate" x-text="selectedText"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.92l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            {{-- Options --}}
                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                @foreach (['Laki-laki', 'Perempuan'] as $option)
                                    <div @click="selectedValue='{{ $option }}'; selectedText='{{ $option }}'; open=false"
                                        class="cursor-pointer select-none py-2 px-4 text-gray-900 hover:bg-orange-50 hover:text-orange-700 transition">
                                        {{ $option }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Pendidikan + No HP --}}
                        <div x-data="{ 
                            open: false, 
                            selectedValue: '{{ old('pendidikan', $pelayanan->pendidikan) }}', 
                            selectedText: '{{ old('pendidikan', $pelayanan->pendidikan) ?: '-- Pilih --' }}' 
                        }" class="relative">
                            <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pendidikan
                            </label>

                            <input type="hidden" name="pendidikan" x-model="selectedValue">

                            <button type="button" @click="open = !open" class="relative w-full cursor-default rounded-lg bg-white py-2.5 pl-4 pr-10 text-left text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                <span class="block truncate" x-text="selectedText"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.92l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                @foreach (['<=SMA','Diploma','S1','S2','S3'] as $option)
                                    <div @click="selectedValue='{{ $option }}'; selectedText='{{ $option }}'; open=false"
                                        class="cursor-pointer select-none py-2 px-4 text-gray-900 hover:bg-orange-50 hover:text-orange-700 transition">
                                        {{ $option }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP</label>
                                <input type="text" name="no_hp" id="no_hp"
                                    value="{{ old('no_hp', $pelayanan->no_hp) }}"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 text-base transition"
                                    placeholder="08xxxxxx">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $pelayanan->email) }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 text-base transition"
                                placeholder="email@example.com">
                        </div>
                    </div>
                </div>

                {{-- Kebutuhan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Detail Kebutuhan Layanan</h3>
                    <p class="text-base text-gray-600 mb-6">Jelaskan kebutuhan data atau layanan yang diperlukan oleh pengunjung.</p>
                    <textarea name="kebutuhan_pengunjung" id="kebutuhan_pengunjung" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 text-base transition"
                        required placeholder="Contoh: Membutuhkan data PDRB Kabupaten X ...">{{ old('kebutuhan_pengunjung', $pelayanan->kebutuhan_pengunjung) }}</textarea>
                </div>

                {{-- Upload Dokumen --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Upload Dokumen Pendukung</h3>
                    <p class="text-base text-gray-600 mb-6">Upload surat atau dokumen yang diperlukan (opsional).</p>
                    <input type="file" name="path_surat_pengantar" id="path_surat_pengantar"
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full
                               file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700
                               hover:file:bg-orange-100 transition cursor-pointer">
                    <p class="text-sm text-gray-500 mt-2">Format: PDF, DOCX, JPG, PNG. Maksimal 5MB.</p>
                    @if($pelayanan->path_surat_pengantar)
                        <div class="mt-4 text-sm">
                            <a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" target="_blank"
                               class="text-orange-600 hover:underline flex items-center gap-1.5">
                                📎 File sudah terunggah: <b>{{ basename($pelayanan->path_surat_pengantar) }}</b>
                            </a>
                        </div>
                    @endif
                </div>
                
                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Halaman Sebelumnya</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                        Halaman Selanjutnya
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
