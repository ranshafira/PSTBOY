{{-- resources/views/pelayanan/create.blade.php (atau nama file yang sesuai) --}}
@extends('layouts.app')
@section('title', 'Mulai Pelayanan Baru')

@section('content')
<div class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        {{-- Header & Stepper --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Mulai Sesi Pelayanan</h1>
            <p class="mt-2 text-base text-slate-600">Langkah 1 dari 6: Inisialisasi layanan untuk nomor antrian yang dipilih.</p>
        </div>
        <div class="flex justify-center mb-12">
            @include('partials._stepper', ['currentStep' => 1])
        </div>

        {{-- Form Card --}}
        <form action="{{ route('pelayanan.start', $antrian->id) }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-8 space-y-6">
                    <div>
                        <label for="nomor_antrian" class="block text-sm font-semibold leading-6 text-slate-900">Nomor Antrian</label>
                        <div class="mt-2">
                            <input type="text" id="nomor_antrian" value="{{ $antrian->nomor_antrian }}" readonly class="block w-full rounded-xl border-0 py-3 px-4 bg-slate-100 text-slate-900 font-mono text-lg ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-bps-blue-600">
                        </div>
                    </div>

                    <div>
                        <label for="jenis_layanan_id" class="block text-sm font-semibold leading-6 text-slate-900">Jenis Pelayanan <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <select id="jenis_layanan_id" name="jenis_layanan_id" required class="block w-full rounded-xl border-0 py-3 px-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-bps-blue-600 transition">
                                <option value="" disabled selected>Pilih jenis layanan yang sesuai</option>
                                @foreach($jenisLayanan as $layanan)
                                    <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        {{-- Hidden input to autopopulate time without needing AlpineJS for this simple case --}}
                        <input type="hidden" name="waktu_mulai" value="{{ now()->format('H:i:s') }}">
                        <p class="text-sm text-slate-500">Waktu mulai akan direkam secara otomatis saat tombol "Mulai & Lanjutkan" ditekan: <strong class="text-slate-700">{{ now()->translatedFormat('d F Y, H:i:s') }}</strong></p>
                    </div>
                </div>

                {{-- Footer Aksi --}}
                <div class="bg-slate-50/50 px-8 py-5 border-t border-slate-200 flex justify-between items-center">
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-bps-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-bps-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-bps-blue-600 transition">
                        Mulai & Lanjutkan
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection