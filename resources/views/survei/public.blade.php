@extends('layouts.survei')
@section('title', 'Survei Kepuasan Pelayanan')

@section('content')
<div class="antialiased font-sans bg-gray-50 min-h-screen py-8 md:py-12"

    x-data="{ ratingKeseluruhan: 0, ratingPetugas: 0 }">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="bg-orange-50 border border-orange-200 rounded-xl shadow-sm p-6 mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Survei Kepuasan Pelayanan</h2>
            <p class="text-base text-gray-600 mt-1">Penilaian Anda sangat berarti bagi kami.</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('survei.public.store', $pelayanan->kode_unik) }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 space-y-8">

                {{-- Pertanyaan 1 --}}
                <div>
                    <label class="block text-base font-semibold text-gray-800 text-center">
                        1. Bagaimana tingkat kepuasan Anda secara keseluruhan terhadap pelayanan yang diberikan?
                    </label>
                    <div class="mt-4 flex justify-center space-x-2 sm:space-x-3">
                        <template x-for="i in 5">
                            <label @click="ratingKeseluruhan = i"
                                class="text-4xl cursor-pointer transition duration-150"
                                :class="ratingKeseluruhan >= i ? 'text-orange-500' : 'text-gray-300'">
                                ★
                                <input type="radio" name="skor_keseluruhan" :value="i" class="hidden">
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Pertanyaan 2 --}}
                <div>
                    <label class="block text-base font-semibold text-gray-800 text-center">
                        2. Bagaimana Anda menilai keramahan dan profesionalisme petugas kami?
                    </label>
                    <div class="mt-4 flex justify-center space-x-2 sm:space-x-3">
                        <template x-for="i in 5">
                            <label @click="ratingPetugas = i"
                                class="text-4xl cursor-pointer transition duration-150"
                                :class="ratingPetugas >= i ? 'text-orange-500' : 'text-gray-300'">
                                ★
                                <input type="radio" name="skor_petugas" :value="i" class="hidden">
                            </label>
                        </template>
                    </div>
                </div>


                {{-- Kritik & Saran --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6"
                    x-show
                    x-transition>
                    <h3 class="text-lg font-semibold text-gray-900">Kritik & Saran</h3>
                    <div class="mt-4">
                        <textarea name="saran" id="saran" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-orange-500 py-2.5 px-4 text-base transition"
                            placeholder="Tuliskan masukan Anda di sini..."></textarea>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Kirim Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection