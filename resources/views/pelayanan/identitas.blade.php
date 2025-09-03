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
                {{-- ... (Icon dan Judul tetap sama) ... --}}
            </div>

            <div class="space-y-5 pl-9">
                {{-- Nama Pelanggan (Full-width) --}}
                <div>
                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan', $pelayanan->nama_pelanggan) }}" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" required placeholder="Masukkan nama lengkap">
                </div>

                {{-- Grid 2 Kolom untuk Instansi & Kontak --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="instansi_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Instansi/Organisasi</label>
                        <input type="text" name="instansi_pelanggan" id="instansi_pelanggan" value="{{ old('instansi_pelanggan', $pelayanan->instansi_pelanggan) }}" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Nama instansi atau organisasi">
                    </div>
                    <div>
                        <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kontak (HP / Email)</label>
                        <input type="text" name="kontak_pelanggan" id="kontak_pelanggan" value="{{ old('kontak_pelanggan', $pelayanan->kontak_pelanggan) }}" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="08xxx atau email@example.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- PERUBAHAN: Kartu Form 2: Kebutuhan Pelanggan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-start space-x-3 mb-6">
                <svg class="w-6 h-6 text-gray-500 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20ZM8 12V14H16V12H8ZM8 16V18H13V16H8Z"></path></svg>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Kebutuhan Layanan</h3>
                    <p class="text-sm text-gray-500">Jelaskan kebutuhan data atau layanan yang diperlukan oleh klien</p>
                </div>
            </div>

            <div class="pl-9">
                <textarea name="kebutuhan_pelanggan" id="kebutuhan_pelanggan" rows="4" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Contoh: Membutuhkan data PDRB Kabupaten X menurut lapangan usaha tahun 2020-2023 dalam bentuk excel.">{{ old('kebutuhan_pelanggan', $pelayanan->kebutuhan_pelanggan) }}</textarea>
            </div>
        </div>


        {{-- Kartu Form 3: Upload Dokumen --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
             <div class="flex items-start space-x-3 mb-6">
                <svg class="w-6 h-6 text-gray-500 mt-1" xmlns="http://www.w.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4C9.11 4 6.6 5.64 5.35 8.04C2.34 8.36 0 10.91 0 14C0 17.31 2.69 20 6 20H19C21.76 20 24 17.76 24 15C24 12.36 21.95 10.22 19.35 10.04ZM19 18H6C3.79 18 2 16.21 2 14C2 11.95 3.53 10.24 5.56 10.03L6.63 9.92L7.13 8.97C8.08 7.14 9.94 6 12 6C14.62 6 16.88 7.86 17.39 10.43L17.69 11.93L19.22 12.04C20.78 12.14 22 13.45 22 15C22 16.65 20.65 18 19 18Z"></path></svg>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Upload Dokumen Pendukung</h3>
                    <p class="text-sm text-gray-500">Upload surat atau dokumen yang diperlukan untuk pelayanan (opsional)</p>
                </div>
            </div>
            <div class="pl-9">
                 <div>
                    <label for="path_surat_pengantar" class="block text-sm font-medium text-gray-700 mb-1">Dokumen/Surat Pendukung</label>
                    <input type="file" name="path_surat_pengantar" id="path_surat_pengantar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB.</p>
                     
                     {{-- PERUBAHAN: Menampilkan link ke file yang sudah diupload --}}
                     @if($pelayanan->path_surat_pengantar)
                        <div class="mt-2 text-sm">
                            <a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" target="_blank" class="text-green-600 hover:text-green-800 hover:underline">
                                File sudah terunggah: {{ basename($pelayanan->path_surat_pengantar) }} (Lihat)
                            </a>
                        </div>
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