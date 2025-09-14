{{-- resources/views/pelayanan/hasil.blade.php --}}
@extends('layouts.app')
@section('title', 'Hasil Pelayanan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Card --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 3: Hasil Pelayanan</h2>
                    <p class="text-base text-gray-600 mt-1">Catat status penyelesaian, hasil, dan output dari pelayanan yang diberikan.</p>
                </div>
            </div>
        </div>

        {{-- Ringkasan Pengunjung --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm font-medium text-gray-500 mb-1">Pengunjung</p>
                <p class="font-semibold text-base text-gray-800 truncate">{{ $pelayanan->nama_pengunjung }}</p>
                <p class="text-sm text-gray-600">{{ $pelayanan->instansi_pengunjung ?? 'Umum' }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm font-medium text-gray-500 mb-1">No. Antrian / Waktu Mulai</p>
                <p class="font-semibold text-base text-gray-800">{{ $pelayanan->antrian->nomor_antrian }}</p>
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($pelayanan->waktu_mulai_sesi)->translatedFormat('l, j F Y - H:i') }} WIB</p>
            </div>
        </div>

        <form action="{{ route('pelayanan.storeHasil', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">

                {{-- Kartu 1: Status & Hasil --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900">Status & Hasil Pelayanan</h3>
                            <p class="text-base text-gray-600 mt-1">Pilih status penyelesaian dan deskripsikan hasilnya.</p>

                            <div class="space-y-6 mt-6" x-data="{
                                dropdownOpen: false,
                                selectedValue: '{{ old('status_penyelesaian', $pelayanan->status_penyelesaian) }}',
                                selectedText: '{{ old('status_penyelesaian', $pelayanan->status_penyelesaian) ?: 'Pilih status...' }}'
                            }">
                                <label for="status_penyelesaian" class="block text-sm font-medium text-gray-700 mb-1.5">Status Penyelesaian <span class="text-red-500">*</span></label>
                                <input type="hidden" name="status_penyelesaian" x-model="selectedValue">

                                <div class="relative" @click.away="dropdownOpen = false">
                                    <button type="button" @click="dropdownOpen = !dropdownOpen" class="relative w-full cursor-default rounded-lg bg-white py-2.5 pl-4 pr-10 text-left text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                        <span class="block truncate" x-text="selectedText"></span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div x-show="dropdownOpen" x-transition class="absolute mt-2 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                        @php
                                            $statuses = ['Selesai', 'Selesai dengan tindak lanjut', 'Tidak dapat dipenuhi', 'Dibatalkan Pengunjung'];
                                        @endphp
                                        @foreach ($statuses as $status)
                                            <div @click="selectedValue='{{ $status }}'; selectedText='{{ $status }}'; dropdownOpen=false;" class="text-gray-900 relative cursor-pointer select-none py-2 px-4 hover:bg-orange-50 hover:text-orange-700 transition">
                                                <span class="block truncate">{{ $status }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="deskripsi_hasil" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Hasil <span class="text-red-500">*</span></label>
                                    <textarea name="deskripsi_hasil" id="deskripsi_hasil" rows="4" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" required placeholder="Jelaskan secara detail hasil dari pelayanan yang diberikan...">{{ old('deskripsi_hasil', $pelayanan->deskripsi_hasil) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 2: Output & Dokumen --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8" x-data="{ requiresFollowUp: {{ old('perlu_tindak_lanjut', $pelayanan->perlu_tindak_lanjut ?? false) ? 'true' : 'false' }} }">
                    <div class="flex items-start space-x-4">
                        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                             <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2a1 1 0 011-1h8a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1V6z" clip-rule="evenodd"></path>
                             </svg>
                        </div>
                        <div class="flex-grow">
                             <h3 class="text-lg font-semibold text-gray-900">Output & Dokumen</h3>
                             <p class="text-base text-gray-600 mt-1">Pilih hasil, unggah dokumen, dan tentukan tindak lanjut jika perlu.</p>

                             <div class="space-y-8 mt-6">
                                 {{-- Jenis Output --}}
                                 <div>
                                     <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Output Diberikan</label>
                                     <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4">
                                         @php $outputs = ['Surat keterangan', 'Informasi tertulis', 'Rujukan', 'Sertifikat', 'Dokumen resmi', 'Data/statistik', 'Formulir', 'Laporan']; @endphp
                                         @foreach ($outputs as $output)
                                             <div class="flex items-center">
                                                 <input id="output_{{ Str::slug($output) }}" name="jenis_output[]" type="checkbox" value="{{ $output }}" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500" {{ in_array($output, old('jenis_output', $pelayanan->jenis_output ?? [])) ? 'checked' : '' }}>
                                                 <label for="output_{{ Str::slug($output) }}" class="ml-2 block text-base text-gray-800">{{ $output }}</label>
                                             </div>
                                         @endforeach
                                     </div>
                                 </div>

                                 {{-- File Upload --}}
                                 <div>
                                     <label for="path_dokumen_hasil" class="block text-sm font-medium text-gray-700 mb-1.5">Upload Dokumen Hasil (Opsional)</label>
                                     <input type="file" name="path_dokumen_hasil" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition cursor-pointer">
                                     @if($pelayanan->path_dokumen_hasil)
                                         <p class="text-sm text-gray-500 mt-2">File sebelumnya: <a href="{{ asset('storage/'.$pelayanan->path_dokumen_hasil) }}" target="_blank" class="underline text-orange-600">{{ basename($pelayanan->path_dokumen_hasil) }}</a></p>
                                     @endif
                                 </div>

                                 {{-- Tindak Lanjut --}}
                                 <div class="border-t border-gray-200 pt-6">
                                     <div class="flex items-center">
                                         <input id="perlu_tindak_lanjut" name="perlu_tindak_lanjut" type="checkbox" value="1" x-model="requiresFollowUp" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                         <label for="perlu_tindak_lanjut" class="ml-2 block text-base font-semibold text-gray-800">Perlu Tindak Lanjut?</label>
                                     </div>
                                 </div>

                                 <div x-show="requiresFollowUp" x-transition class="space-y-6">
                                     <div>
                                         <label for="tanggal_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Tindak Lanjut</label>
                                         <input type="date" name="tanggal_tindak_lanjut" id="tanggal_tindak_lanjut" class="w-full md:w-1/2 text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" value="{{ old('tanggal_tindak_lanjut', $pelayanan->tanggal_tindak_lanjut) }}">
                                     </div>
                                     <div>
                                         <label for="catatan_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tindak Lanjut</label>
                                         <textarea name="catatan_tindak_lanjut" id="catatan_tindak_lanjut" rows="3" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Jelaskan tindak lanjut yang diperlukan...">{{ old('catatan_tindak_lanjut', $pelayanan->catatan_tindak_lanjut) }}</textarea>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu 3: Catatan Tambahan --}}
                 <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                     <div class="flex items-start space-x-4">
                         <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0118 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 10a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0-3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0-3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                         </div>
                         <div class="flex-grow">
                              <h3 class="text-lg font-semibold text-gray-900">Catatan Tambahan (Internal)</h3>
                              <p class="text-base text-gray-600 mt-1">Catatan internal atau informasi tambahan untuk pelayanan ini.</p>
                              <div class="mt-6">
                                <label for="catatan_tambahan" class="sr-only">Catatan Tambahan</label>
                                <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Catatan internal atau informasi tambahan...">{{ old('catatan_tambahan', $pelayanan->catatan_tambahan) }}</textarea>
                              </div>
                         </div>
                     </div>
                 </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ route('pelayanan.identitas', $pelayanan->id) }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Halaman Sebelumnya</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition flex items-center justify-center gap-2">
                        <span>Halaman Selanjutnya</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
