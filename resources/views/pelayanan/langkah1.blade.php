@extends('layouts.app')
@section('title', 'Langkah 1: Data Pengunjung')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.5a5.5 5.5 0 0 1 3.1 10 9 9 0 0 1 5.9 8.2.75.75 0 1 1-1.5 0A7.5 7.5 0 0 0 5.5 20.7a.75.75 0 0 1-1.5 0 9 9 0 0 1 5.9-8.2A5.5 5.5 0 0 1 12 2.5Z" />
                    </svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Langkah 1: Inisiasi & Data Pengunjung</h2>
                    <p class="text-base text-gray-600 mt-1">Isi data pengunjung untuk memulai sesi pelayanan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('pelayanan.langkah1.store') }}" method="POST">
            @csrf
            <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">
            <div class="space-y-6">

                {{-- Card Nomor Antrian & Jenis Layanan --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor_antrian" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Antrian</label>
                            <input type="text" id="nomor_antrian" value="{{ $antrian->nomor_antrian }}" readonly class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 py-2.5 px-4">
                        </div>
                        <div>
                            <label for="jenis_layanan_id" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Pelayanan <span class="text-red-500">*</span></label>
                            <select id="jenis_layanan_id" name="jenis_layanan_id" required class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition">
                                <option value="" disabled selected>Pilih jenis layanan</option>
                                @foreach($jenisLayanan as $layanan)
                                <option value="{{ $layanan->id }}" {{ old('jenis_layanan_id', $pelayanan?->jenis_layanan_id) == $layanan->id ? 'selected' : '' }}>
                                    {{ $layanan->nama_layanan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Card Identitas --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Data Identitas Pengunjung</h3>
                    <p class="text-base text-gray-600 mb-6">Lengkapi informasi dasar mengenai pengunjung.</p>

                    <div class="space-y-6">
                        <div>
                            <label for="nama_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pengunjung" id="nama_pengunjung" value="{{ old('nama_pengunjung', $pelayanan?->nama_pengunjung) }}" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" required placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="instansi_pengunjung" class="block text-sm font-medium text-gray-700 mb-1.5">Instansi/Organisasi</label>
                                <input type="text" name="instansi_pengunjung" id="instansi_pengunjung" value="{{ old('instansi_pengunjung', $pelayanan?->instansi_pengunjung) }}" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" placeholder="Nama instansi atau organisasi">
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition">
                                    <option value="" {{ old('jenis_kelamin', $pelayanan?->jenis_kelamin) == '' ? 'selected' : '' }}>-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $pelayanan?->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $pelayanan?->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-1.5">Pendidikan</label>
                                <select name="pendidikan" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition">
                                    @foreach (['<=SMA','Diploma','S1','S2','S3'] as $option)
                                        <option value="{{ $option }}" {{ old('pendidikan', $pelayanan?->pendidikan) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP</label>
                                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $pelayanan?->no_hp) }}" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" placeholder="08xxxxxx">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $pelayanan?->email) }}" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition" placeholder="email@example.com">
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a href="{{ route('pelayanan.index') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Batal</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600">
                        Lanjutkan ke Detail Layanan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection