{{-- resources/views/pelayanan/hasil.blade.php --}}
@extends('layouts.app')
@section('title', 'Hasil Pelayanan')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('pelayanan.identitas', $pelayanan->id) }}" class="text-sm font-medium text-gray-600 hover:text-orange-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Hasil Pelayanan</h1>
            <p class="text-md text-gray-500 mt-1">Langkah 3 dari 6: Pencatatan hasil dan output pelayanan.</p>
        </div>

        <div class="mb-10">
            <p class="text-sm text-gray-600 mb-2">Progress: Langkah 3 dari 6</p>
            <div class="relative w-full h-2 bg-gray-200 rounded-full"><div class="absolute top-0 left-0 h-2 bg-orange-500 rounded-full" style="width: {{ (3/6)*100 }}%;"></div></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5"><p class="text-sm text-gray-500 mb-1">Klien</p><p class="font-semibold text-lg text-gray-800">{{ $pelayanan->nama_pelanggan }}</p><p class="text-sm text-gray-600">{{ $pelayanan->instansi_pelanggan ?? 'Umum' }}</p></div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5"><p class="text-sm text-gray-500 mb-1">No. Antrian / Waktu Mulai</p><p class="font-semibold text-lg text-gray-800">{{ $pelayanan->antrian->nomor_antrian }}</p><p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->format('H:i') }}</p></div>
        </div>

        <form action="{{ route('pelayanan.storeHasil', $pelayanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-7 space-y-8">
                <div>
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0"><svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>
                        <div><h3 class="text-xl font-semibold text-gray-800">Status & Hasil</h3><p class="text-sm text-gray-600 mt-1">Pilih status penyelesaian dan deskripsikan hasilnya.</p></div>
                    </div>
                    <div class="space-y-6 pl-10">
                        <div>
                            <label for="status_penyelesaian" class="block text-sm font-medium text-gray-700 mb-2">Status Penyelesaian <span class="text-red-500">*</span></label>
                            <select name="status_penyelesaian" id="status_penyelesaian" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" required><option value="">Pilih status penyelesaian</option><option value="Selesai">Selesai</option><option value="Selesai_dengan_tindak_lanjut">Selesai dengan tindak lanjut</option><option value="Tidak_dapat_dipenuhi">Tidak dapat dipenuhi</option><option value="Dibatalkan_klien">Dibatalkan klien</option></select>
                        </div>
                        <div>
                            <label for="deskripsi_hasil" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Hasil <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi_hasil" id="deskripsi_hasil" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" required placeholder="Jelaskan secara detail hasil dari pelayanan yang diberikan..."></textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0"><svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2a1 1 0 011-1h8a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1V6z" clip-rule="evenodd"></path></svg></div>
                        <div><h3 class="text-xl font-semibold text-gray-800">Output, Dokumen & Tindak Lanjut</h3><p class="text-sm text-gray-600 mt-1">Pilih hasil, unggah dokumen, dan tentukan tindak lanjut.</p></div>
                    </div>
                    <div class="space-y-6 pl-10" x-data="{ requiresFollowUp: false }">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Output Diberikan</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                                @php $outputs = ['Surat keterangan', 'Informasi tertulis', 'Rujukan', 'Sertifikat', 'Dokumen resmi', 'Data/statistik', 'Formulir', 'Laporan']; @endphp
                                @foreach ($outputs as $output)
                                <div class="flex items-center"><input id="output_{{ Str::slug($output) }}" name="jenis_output[]" type="checkbox" value="{{ $output }}" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"><label for="output_{{ Str::slug($output) }}" class="ml-2 block text-sm text-gray-900">{{ $output }}</label></div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="path_dokumen_hasil" class="block text-sm font-medium text-gray-700 mb-2">Upload Dokumen Hasil (Opsional)</label>
                            <input type="file" name="path_dokumen_hasil" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition">
                            <p class="text-xs text-gray-500 mt-2">Format: PDF, DOC, XLS, CSV, JPG, PNG. Maksimal 10MB.</p>
                        </div>
                        <div class="pt-4">
                            <div class="flex items-center"><input id="perlu_tindak_lanjut" name="perlu_tindak_lanjut" type="checkbox" value="1" x-model="requiresFollowUp" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"><label for="perlu_tindak_lanjut" class="ml-2 block text-sm font-semibold text-gray-900">Diperlukan tindak lanjut</label></div>
                        </div>
                        <div x-show="requiresFollowUp" x-transition class="space-y-6 border-t pt-6 mt-4 -ml-10 px-10">
                            <div>
                                <label for="tanggal_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tindak Lanjut</label>
                                <input type="date" name="tanggal_tindak_lanjut" id="tanggal_tindak_lanjut" class="w-full md:w-1/2 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition">
                            </div>
                            <div>
                                <label for="catatan_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tindak Lanjut</label>
                                <textarea name="catatan_tindak_lanjut" id="catatan_tindak_lanjut" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Jelaskan tindak lanjut yang diperlukan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">
                
                <div>
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0"><svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0118 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 10a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0-3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0-3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg></div>
                        <div><h3 class="text-xl font-semibold text-gray-800">Catatan Tambahan</h3><p class="text-sm text-gray-600 mt-1">Catatan internal atau informasi tambahan untuk pelayanan ini.</p></div>
                    </div>
                    <div class="pl-10">
                        <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Catatan internal atau informasi tambahan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6">
                <a href="{{ route('pelayanan.identitas', $pelayanan->id) }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Kembali</a>
                <button type="submit" class="px-7 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">Lanjutkan →</button>
            </div>
        </form>
    </div>
</div>
@endsection