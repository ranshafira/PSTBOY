@extends('layouts.app')

@section('title', 'Pengisian Identitas Pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Identitas & Dokumen</h1>
        <p class="text-gray-500">Langkah 2 dari 7: Pengisian data identitas klien dan upload dokumen pendukung</p>
    </div>

    {{-- Progress Step --}}
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm">
            <span class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-full font-medium">
                Langkah 1/7
            </span>
            <span class="px-4 py-1.5 bg-orange-500 text-white rounded-full font-semibold">
                Langkah 2/7
            </span>
            <span class="font-medium text-gray-700">Identitas & Dokumen</span>
        </div>
    </div>

    <form action="{{ route('pelayanan.storeIdentitas', $pelayanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Kartu Form 1: Data Identitas Klien --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-start space-x-3 mb-6">
                <svg class="w-6 h-6 text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 11C10.8954 11 10 10.1046 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9C14 10.1046 13.1046 11 12 11ZM12 13C14.2091 13 16 14.7909 16 17H8C8 14.7909 9.79086 13 12 13Z"></path></svg>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Data Identitas Klien</h3>
                    <p class="text-sm text-gray-500">Lengkapi informasi identitas klien yang akan dilayani</p>
                </div>
            </div>

            <div class="space-y-5 pl-9">
                {{-- Nama Pelanggan (Full-width) --}}
                <div>
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                           value="{{ old('nama_pelanggan', $pelayanan->nama_pelanggan) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" required placeholder="Masukkan nama lengkap">
                </div>

                {{-- Grid 2 Kolom untuk Instansi & Kontak --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="instansi_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Instansi/Organisasi</label>
                        <input type="text" name="instansi_pelanggan" id="instansi_pelanggan" 
                               value="{{ old('instansi_pelanggan', $pelayanan->instansi_pelanggan) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Nama instansi atau organisasi">
                    </div>
                    <div>
                        <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kontak (HP / Email)</label>
                        <input type="text" name="kontak_pelanggan" id="kontak_pelanggan" 
                               value="{{ old('kontak_pelanggan', $pelayanan->kontak_pelanggan) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="08xxx atau email@example.com">
                    </div>
                </div>
                
                {{-- Kebutuhan Pelanggan (Full-width) --}}
                <div>
                    <label for="kebutuhan_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kebutuhan Pelanggan</label>
                    <textarea name="kebutuhan_pelanggan" id="kebutuhan_pelanggan" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Jelaskan kebutuhan data atau layanan yang diperlukan">{{ old('kebutuhan_pelanggan', $pelayanan->kebutuhan_pelanggan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Kartu Form 2: Upload Dokumen --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
             <div class="flex items-start space-x-3 mb-6">
                <svg class="w-6 h-6 text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10H11V4H13V10ZM13 12H11V14H13V12ZM19.35 10.04C18.67 6.59 15.64 4 12 4C9.11 4 6.6 5.64 5.35 8.04C2.34 8.36 0 10.91 0 14C0 17.31 2.69 20 6 20H19C21.76 20 24 17.76 24 15C24 12.36 21.95 10.22 19.35 10.04ZM19 18H6C3.79 18 2 16.21 2 14C2 11.95 3.53 10.24 5.56 10.03L6.63 9.92L7.13 8.97C8.08 7.14 9.94 6 12 6C14.62 6 16.88 7.86 17.39 10.43L17.69 11.93L19.22 12.04C20.78 12.14 22 13.45 22 15C22 16.65 20.65 18 19 18Z"></path></svg>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Upload Dokumen Pendukung</h3>
                    <p class="text-sm text-gray-500">Upload surat atau dokumen yang diperlukan untuk pelayanan (opsional)</p>
                </div>
            </div>
            <div class="pl-9">
                 <div>
                    <label for="path_surat_pengantar" class="block text-sm font-medium text-gray-700 mb-1">Dokumen/Surat</label>
                    <input type="file" name="path_surat_pengantar" id="path_surat_pengantar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB per file.</p>
                     @if($pelayanan->path_surat_pengantar)
                        <p class="mt-2 text-xs text-green-600">File sudah terunggah: {{ basename($pelayanan->path_surat_pengantar) }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert Info --}}
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg flex items-center space-x-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <p>Pastikan semua data yang dimasukkan sudah benar sebelum melanjutkan ke tahap selanjutnya.</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center pt-6">
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                Kembali
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                Lanjutkan →
            </button>
        </div>

    </form>
</div>
@endsection