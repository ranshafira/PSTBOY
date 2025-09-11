@extends('layouts.app')
@section('title', 'Formulir Survei Kepuasan')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- [REFAKTOR] - Header dan Info Ringkasan disesuaikan dengan standar --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <div class="flex-grow">
                    <h2 class="text-lg font-bold text-gray-900">Formulir Survei Kepuasan</h2>
                    <p class="text-base text-gray-600 mt-1">
                        Mohon berikan penilaian Anda terhadap pelayanan yang telah diterima.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-xl p-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-8">
            <div><p class="font-medium text-gray-500">Klien</p><p class="font-semibold text-base text-gray-800 truncate">{{ $pelayanan->nama_pelanggan }}</p></div>
            <div><p class="font-medium text-gray-500">No. Antrian</p><p class="font-semibold text-base text-gray-800">{{ $pelayanan->antrian->nomor_antrian }}</p></div>
            <div><p class="font-medium text-gray-500">Durasi</p><p class="font-semibold text-base text-gray-800">{{ $pelayanan->waktu_selesai_sesi ? $pelayanan->waktu_selesai_sesi->diffInMinutes($pelayanan->waktu_mulai_sesi) : '-' }} menit</p></div>
            <div><p class="font-medium text-gray-500">Jenis Layanan</p><p class="font-semibold text-base text-gray-800 truncate">{{ $pelayanan->jenisLayanan->nama_layanan }}</p></div>
        </div>


        <form action="{{ route('survei.store', $pelayanan->survey_token) }}" method="POST" class="space-y-6">
            @csrf

            {{-- [REFAKTOR] - Kartu Penilaian dengan Ikon SVG Minimalis & Interaktif --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-gray-900">
                    Penilaian Kepuasan <span class="text-red-500">*</span>
                </h3>
                <p class="text-base text-gray-600 mt-1">Berikan penilaian untuk berbagai aspek pelayanan kami.</p>
                
                @php
                $aspek = [
                    'skor_keseluruhan' => 'Kepuasan Keseluruhan', 'skor_kualitas' => 'Kualitas Layanan',
                    'skor_petugas' => 'Kinerja Petugas', 'skor_efisiensi' => 'Efisiensi Waktu', 'skor_fasilitas' => 'Fasilitas',
                ];
                $icons = [
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M16 16s-1.5-2-4-2-4 2-4 2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M16 16s-1.5-1-4-1-4 1-4 1"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="15" x2="16" y2="15"></line><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                    '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M8 13s1.5 3 4 3 4-3 4-3"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                ];
                $tooltips = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
                @endphp
                
                <div class="space-y-8 mt-6">
                    @foreach ($aspek as $name => $label)
                    <div>
                        <label class="block text-base font-medium text-gray-800">{{ $label }}</label>
                        <div class="mt-3 grid grid-cols-5 gap-2 sm:gap-4">
                            @for ($i = 1; $i <= 5; $i++)
                            <div class="relative">
                                <input type="radio" name="{{ $name }}" value="{{ $i }}" id="{{ $name }}_{{ $i }}" class="sr-only peer" required>
                                <label for="{{ $name }}_{{ $i }}" 
                                       title="{{ $tooltips[$i-1] }}"
                                       class="flex flex-col items-center justify-center p-2 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer transition-all duration-200 ease-in-out text-gray-500 hover:bg-orange-100 hover:border-orange-300 hover:text-orange-600 peer-checked:bg-orange-500 peer-checked:border-orange-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-orange-500">
                                    {!! $icons[$i-1] !!}
                                    <span class="mt-1 block text-sm font-semibold">{{ $i }}</span>
                                </label>
                            </div>
                            @endfor
                        </div>
                        @error($name) <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- [REFAKTOR] - Kartu Rekomendasi yang Interaktif --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                <h3 class="text-lg font-semibold text-gray-900">Rekomendasi</h3>
                <p class="text-base text-gray-600 mt-1">Apakah Anda akan merekomendasikan layanan kami kepada orang lain? <span class="text-red-500">*</span></p>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <input type="radio" name="rekomendasi" value="1" id="rekomendasi_ya" class="sr-only peer" required>
                        <label for="rekomendasi_ya" class="flex items-center justify-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer transition hover:bg-emerald-100 hover:border-emerald-300 peer-checked:bg-emerald-500 peer-checked:border-emerald-500 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-emerald-500">
                            <span class="text-base font-semibold text-gray-800 peer-checked:text-white">Ya, Tentu</span>
                        </label>
                    </div>
                     <div>
                        <input type="radio" name="rekomendasi" value="0" id="rekomendasi_tidak" class="sr-only peer">
                        <label for="rekomendasi_tidak" class="flex items-center justify-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer transition hover:bg-red-100 hover:border-red-300 peer-checked:bg-red-500 peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-red-500">
                            <span class="text-base font-semibold text-gray-800 peer-checked:text-white">Tidak</span>
                        </label>
                    </div>
                </div>
                @error('rekomendasi') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Kartu Masukan & Saran --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                 <h3 class="text-lg font-semibold text-gray-900">Masukan & Saran (Opsional)</h3>
                 <p class="text-base text-gray-600 mt-1">Berikan masukan atau saran untuk perbaikan pelayanan kami di masa depan.</p>
                 <div class="space-y-6 mt-6">
                     <div>
                         <label for="feedback_pelayanan" class="block text-sm font-medium text-gray-700 mb-1.5">Kesan & Pengalaman Anda</label>
                         <textarea name="feedback_pelayanan" id="feedback_pelayanan" rows="4" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Bagikan pengalaman Anda selama mendapatkan pelayanan..."></textarea>
                     </div>
                     <div>
                         <label for="saran_perbaikan" class="block text-sm font-medium text-gray-700 mb-1.5">Saran Perbaikan</label>
                         <textarea name="saran_perbaikan" id="saran_perbaikan" rows="4" class="w-full text-base rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 py-2.5 px-4 transition" placeholder="Saran untuk meningkatkan kualitas pelayanan di masa mendatang..."></textarea>
                     </div>
                 </div>
            </div>

            {{-- [REFAKTOR] - Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                <a href="{{ route('survei.entry') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 hover:bg-gray-100 transition text-center">Kembali</a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span>Kirim Survei</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection