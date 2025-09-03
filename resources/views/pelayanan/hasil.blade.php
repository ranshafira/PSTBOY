@extends('layouts.app')

@section('title', 'Hasil Pelayanan')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('pelayanan.identitas', $pelayanan->id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Hasil Pelayanan</h1>
        <p class="text-gray-500">Langkah 4 dari 7: Pencatatan hasil dan output pelayanan</p>
    </div>

    {{-- Progress Step --}}
    <div class="mb-8">
        <div class="relative w-full h-2 bg-gray-200 rounded-full">
            <div class="absolute top-0 left-0 h-2 bg-orange-500 rounded-full" style="width: {{ (4/7)*100 }}%;"></div>
        </div>
        <p class="text-center text-sm text-gray-600 mt-2">Langkah 4/7: Hasil Pelayanan</p>
    </div>

    {{-- Info Klien --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-sm text-gray-500">Klien</p>
            <p class="font-semibold text-gray-800">{{ $pelayanan->nama_pelanggan }}</p>
            <p class="text-sm text-gray-600">{{ $pelayanan->instansi_pelanggan ?? 'Umum' }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <p class="text-sm text-gray-500">No. Antrian / Waktu Mulai</p>
            <p class="font-semibold text-gray-800">{{ $pelayanan->antrian->nomor_antrian }}</p>
            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->format('H.i') }}</p>
        </div>
    </div>


    <form action="{{ route('pelayanan.storeHasil', $pelayanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Status & Hasil --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status & Hasil</h3>
            <div class="space-y-5">
                <div>
                    <label for="status_penyelesaian" class="block text-sm font-medium text-gray-700 mb-1">Status Penyelesaian <span class="text-red-500">*</span></label>
                    <select name="status_penyelesaian" id="status_penyelesaian" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" required>
                        <option value="">Pilih status penyelesaian</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Selesai dengan tindak lanjut">Selesai dengan tindak lanjut</option>
                        <option value="Tidak dapat dipenuhi">Tidak dapat dipenuhi</option>
                        <option value="Dibatalkan klien">Dibatalkan klien</option>
                    </select>
                </div>
                <div>
                    <label for="deskripsi_hasil" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Hasil <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi_hasil" id="deskripsi_hasil" rows="4" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" required placeholder="Jelaskan secara detail hasil dari pelayanan yang diberikan..."></textarea>
                </div>
            </div>
        </div>

        {{-- Output/Deliverables --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Output/Deliverables</h3>
            <p class="text-sm text-gray-500 mb-4">Dokumen atau hasil yang diberikan kepada klien</p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @php $outputs = ['Surat keterangan', 'Informasi tertulis', 'Rujukan', 'Sertifikat', 'Dokumen resmi', 'Data/statistik', 'Formulir', 'Laporan']; @endphp
                @foreach ($outputs as $output)
                <div class="flex items-center">
                    <input id="output_{{ Str::slug($output) }}" name="jenis_output[]" type="checkbox" value="{{ $output }}" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="output_{{ Str::slug($output) }}" class="ml-2 block text-sm text-gray-900">{{ $output }}</label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Dokumen Hasil --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumen Hasil</h3>
            <p class="text-sm text-gray-500 mb-4">Upload dokumen hasil pelayanan (opsional)</p>
            <input type="file" name="path_dokumen_hasil" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, XLS, CSV, JPG, PNG. Maksimal 10MB.</p>
        </div>
        
        {{-- Tindak Lanjut --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6" x-data="{ requiresFollowUp: false }">
            <div class="flex items-center mb-4">
                <input id="perlu_tindak_lanjut" name="perlu_tindak_lanjut" type="checkbox" value="1" x-model="requiresFollowUp" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                <label for="perlu_tindak_lanjut" class="ml-2 block text-sm font-semibold text-gray-900">Diperlukan tindak lanjut</label>
            </div>
            <div x-show="requiresFollowUp" x-transition class="space-y-4 border-t pt-4 mt-4">
                 <div>
                    <label for="tanggal_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tindak Lanjut</label>
                    <input type="date" name="tanggal_tindak_lanjut" id="tanggal_tindak_lanjut" class="w-full md:w-1/2 rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition">
                </div>
                 <div>
                    <label for="catatan_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tindak Lanjut</label>
                    <textarea name="catatan_tindak_lanjut" id="catatan_tindak_lanjut" rows="3" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Jelaskan tindak lanjut yang diperlukan..."></textarea>
                </div>
            </div>
        </div>

        {{-- Catatan Tambahan --}}
         <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Catatan Tambahan</h3>
            <p class="text-sm text-gray-500 mb-4">Catatan internal atau informasi tambahan</p>
            <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-orange-200 focus:border-orange-400 transition" placeholder="Catatan internal atau informasi tambahan..."></textarea>
        </div>


        {{-- Alert Info --}}
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg flex items-center space-x-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <p>Pastikan semua hasil pelayanan telah dicatat dengan lengkap sebelum melanjutkan ke penutupan layanan.</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center pt-6">
            <a href="{{ route('pelayanan.identitas', $pelayanan->id) }}"
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

{{-- Tambahkan script AlpineJS jika belum ada di layout utama Anda --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection