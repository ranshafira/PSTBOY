@extends('layouts.app')
@section('title', 'Langkah 2: Hasil Pelayanan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Card --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Detail Pelayanan</h2>
                    <p class="text-base text-gray-600 mt-1">Catat kebutuhan, hasil, dan output dari pelayanan yang diberikan.</p>
                </div>
            </div>
        </div>

        @if(isset($pelayanan) && request()->routeIs('pelayanan.langkah2.edit'))
            <form action="{{ route('pelayanan.langkah2.update', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
        @else
            <form action="{{ route('pelayanan.langkah2.store', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
        @endif

            @csrf
            <div class="space-y-6">

                {{-- Kartu Kebutuhan & Dokumen Pendukung --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Detail Kebutuhan & Dokumen Pendukung</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Detail Kebutuhan Pengunjung<span class="text-red-500">*</span></label>
                            <textarea name="kebutuhan_pengunjung" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" required placeholder="Contoh: Membutuhkan data PDRB Kabupaten X ...">{{ old('kebutuhan_pengunjung', $pelayanan->kebutuhan_pengunjung) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Dokumen Pendukung (Opsional)</label>
                            <input type="file" name="path_surat_pengantar" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            @if($pelayanan->path_surat_pengantar)
                            <a href="{{ Storage::url($pelayanan->path_surat_pengantar) }}" target="_blank" class="text-sm text-orange-600 hover:underline mt-2 inline-block">📎 Lihat file terunggah</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kartu Status & Hasil --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Hasil Pelayanan</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="status_penyelesaian" class="block text-sm font-medium text-gray-700 mb-1.5">Status Penyelesaian <span class="text-red-500">*</span></label>
                            <select name="status_penyelesaian" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500" required>
                                @php $statuses = ['Selesai', 'Selesai dengan tindak lanjut', 'Tidak dapat dipenuhi', 'Dibatalkan Pengunjung']; @endphp
                                @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ old('status_penyelesaian', $pelayanan->status_penyelesaian) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="deskripsi_hasil" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Hasil Pelayanan<span class="text-red-500">*</span></label>
                            <textarea name="deskripsi_hasil" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" required placeholder="Jelaskan secara detail hasil dari pelayanan...">{{ old('deskripsi_hasil', $pelayanan->deskripsi_hasil) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Kartu Output & Tindak Lanjut --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8" x-data="{ requiresFollowUp: {{ old('perlu_tindak_lanjut', $pelayanan->perlu_tindak_lanjut) ? 'true' : 'false' }} }">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Output & Dokumen Hasil</h3>
                    <div class="space-y-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Output Diberikan</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @php $outputs = ['Surat keterangan', 'Informasi tertulis', 'Rujukan', 'Data', 'Informasi Lisan']; @endphp
                                @foreach ($outputs as $output)
                                <div class="flex items-center">
                                    <input id="output_{{ Str::slug($output) }}" name="jenis_output[]" type="checkbox" value="{{ $output }}" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500" {{ in_array($output, old('jenis_output', $pelayanan->jenis_output ?? [])) ? 'checked' : '' }}>
                                    <label for="output_{{ Str::slug($output) }}" class="ml-2 block text-base text-gray-800">{{ $output }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="path_dokumen_hasil" class="block text-sm font-medium text-gray-700 mb-1.5">Upload Dokumen Hasil (Opsional)</label>
                            <input type="file" name="path_dokumen_hasil" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            @if($pelayanan->path_dokumen_hasil)
                            <a href="{{ asset('storage/'.$pelayanan->path_dokumen_hasil) }}" target="_blank" class="text-sm text-orange-600 hover:underline mt-2 inline-block">📎 Lihat file terunggah</a>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center">
                                <input id="perlu_tindak_lanjut" name="perlu_tindak_lanjut" type="checkbox" value="1" x-model="requiresFollowUp" class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                <label for="perlu_tindak_lanjut" class="ml-2 block text-base font-semibold text-gray-800">Perlu Tindak Lanjut?</label>
                            </div>
                        </div>
                        <div x-show="requiresFollowUp" x-transition class="space-y-6">
                            <div>
                                <label for="tanggal_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Tindak Lanjut</label>
                                <input type="date" name="tanggal_tindak_lanjut" class="w-full md:w-1/2 rounded-lg border-gray-300" value="{{ old('tanggal_tindak_lanjut', $pelayanan->tanggal_tindak_lanjut?->format('Y-m-d')) }}">
                            </div>
                            <div>
                                <label for="catatan_tindak_lanjut" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tindak Lanjut</label>
                                <textarea name="catatan_tindak_lanjut" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" placeholder="Jelaskan tindak lanjut yang diperlukan...">{{ old('catatan_tindak_lanjut', $pelayanan->catatan_tindak_lanjut) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row {{ request()->routeIs('pelayanan.langkah2.edit') ? 'justify-end' : 'justify-between' }} items-center gap-4 pt-4">
                    @unless(request()->routeIs('pelayanan.langkah2.edit'))
                        <a href="{{ route('pelayanan.langkah1.create', $pelayanan->antrian_id) }}?mode=mulai"
                        class="w-full sm:w-auto px-6 py-3 border rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 text-center">
                            Halaman Sebelumnya
                        </a>
                    @endunless

                    {{-- Tombol submit --}}
                    @if(request()->routeIs('pelayanan.langkah2.edit'))
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                            Simpan Perubahan
                        </button>
                    @else
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                            Lanjutkan ke Survei Internal
                        </button>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>
@endsection