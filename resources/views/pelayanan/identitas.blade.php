{{-- resources/views/pelayanan/identitas.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengisian Identitas Pelanggan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- [REFAKTOR] - Header Halaman diganti dengan komponen standar --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.5a5.5 5.5 0 0 1 3.096 10.047 9.005 9.005 0 0 1 5.9 8.181.75.75 0 1 1-1.499.044 7.5 7.5 0 0 0-14.993 0 .75.75 0 0 1-1.5-.045 9.005 9.005 0 0 1 5.9-8.18A5.5 5.5 0 0 1 12 2.5ZM8 8a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 2: Identitas & Dokumen</h2>
                    <p class="text-base text-gray-600 mt-1">
                        Lengkapi data identitas klien dan upload dokumen pendukung jika ada.
                    </p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('pelayanan.storeIdentitas', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- [REFAKTOR] - Jarak antar kartu dibuat konsisten --}}
            <div class="space-y-6">
                
                {{-- [REFAKTOR] - Kartu 1: Data Identitas Klien --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5a5.5 5.5 0 0 1 3.096 10.047 9.005 9.005 0 0 1 5.9 8.181.75.75 0 1 1-1.499.044 7.5 7.5 0 0 0-14.993 0 .75.75 0 0 1-1.5-.045 9.005 9.005 0 0 1 5.9-8.18A5.5 5.5 0 0 1 12 2.5ZM8 8a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z" /></svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">Data Identitas Klien</h3>
                            <p class="text-base text-gray-600 mt-1">Lengkapi informasi dasar mengenai klien.</p>
                            
                            {{-- [REFAKTOR] - Konten form diratakan dan diberi jarak konsisten --}}
                            <div class="space-y-6 mt-6">
                                <div>
                                    <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan', $pelayanan->nama_pelanggan) }}" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" required placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="instansi_pelanggan" class="block text-sm font-medium text-gray-700 mb-1.5">Instansi/Organisasi</label>
                                        <input type="text" name="instansi_pelanggan" id="instansi_pelanggan" value="{{ old('instansi_pelanggan', $pelayanan->instansi_pelanggan) }}" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Nama instansi atau organisasi">
                                    </div>
                                    <div>
                                        <label for="kontak_pelanggan" class="block text-sm font-medium text-gray-700 mb-1.5">Kontak (HP / Email)</label>
                                        <input type="text" name="kontak_pelanggan" id="kontak_pelanggan" value="{{ old('kontak_pelanggan', $pelayanan->kontak_pelanggan) }}" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="08xxx atau email@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [REFAKTOR] - Kartu 2: Detail Kebutuhan Layanan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                             <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20ZM8 12V14H16V12H8ZM8 16V18H13V16H8Z"></path></svg>
                        </div>
                        <div class="flex-grow">
                             <h3 class="text-lg font-semibold text-gray-900">Detail Kebutuhan Layanan</h3>
                             <p class="text-base text-gray-600 mt-1">Jelaskan kebutuhan data atau layanan yang diperlukan oleh klien.</p>
                             <div class="mt-6">
                                <label for="kebutuhan_pelanggan" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Kebutuhan <span class="text-red-500">*</span></label>
                                <textarea name="kebutuhan_pelanggan" id="kebutuhan_pelanggan" rows="4" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" required placeholder="Contoh: Membutuhkan data PDRB Kabupaten X menurut lapangan usaha tahun 2020-2023 dalam bentuk excel.">{{ old('kebutuhan_pelanggan', $pelayanan->kebutuhan_pelanggan) }}</textarea>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- [REFAKTOR] - Kartu 3: Upload Dokumen --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                     <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4C9.11 4 6.6 5.64 5.35 8.04C2.34 8.36 0 10.91 0 14C0 17.31 2.69 20 6 20H19C21.76 20 24 17.76 24 15C24 12.36 21.95 10.22 19.35 10.04ZM19 18H6C3.79 18 2 16.21 2 14C2 11.95 3.53 10.24 5.56 10.03L6.63 9.92L7.13 8.97C8.08 7.14 9.94 6 12 6C14.62 6 16.88 7.86 17.39 10.43L17.69 11.93L19.22 12.04C20.78 12.14 22 13.45 22 15C22 16.65 20.65 18 19 18Z"></path></svg>
                        </div>
                         <div class="flex-grow">
                             <h3 class="text-lg font-semibold text-gray-900">Upload Dokumen Pendukung</h3>
                             <p class="text-base text-gray-600 mt-1">Upload surat atau dokumen yang diperlukan (opsional).</p>
                             <div class="mt-6">
                                <label for="path_surat_pengantar" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih File</label>
                                <input type="file" name="path_surat_pengantar" id="path_surat_pengantar" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition cursor-pointer">
                                <p class="text-sm text-gray-500 mt-2">Format: PDF, DOCX, JPG, PNG. Maksimal 5MB.</p>
                                @if($pelayanan->path_surat_pengantar)
                                    <div class="mt-4 text-sm"><a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" target="_blank" class="text-orange-600 hover:underline flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path></svg>File sudah terunggah: <b>{{ basename($pelayanan->path_surat_pengantar) }}</b></a></div>
                                @endif
                             </div>
                         </div>
                     </div>
                </div>
                
                {{-- [REFAKTOR] - Tombol Aksi dibuat konsisten --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Halaman Sebelumnya</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span>Halaman Selanjutnya</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection