@extends('layouts.app')

@section('content')

@if(session('success'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 2000)"
    x-transition:enter="transform ease-out duration-300"
    x-transition:enter-start="translate-y-[-20px] opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-[-20px] opacity-0"
    class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-white text-gray-800 px-5 py-3 rounded-lg shadow-lg flex items-center space-x-3 z-50 border border-gray-200">
    {{-- Icon centang hijau di lingkaran --}}
    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-green-500 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    {{-- Pesan --}}
    <span class="font-medium">{{ session('success') }}</span>

    {{-- Tombol close --}}
    <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">
        ✕
    </button>
</div>
@endif

<div class="max-w-5xl mx-auto px-4 py-4">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Profil Saya</h2>

    <!-- Profil Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">

        <!-- Header Card -->
        <div class=" px-8 py-6 ">
            <h3 class="text-xl font-semibold text-gray-800">Informasi Profil</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola informasi personal Anda</p>
        </div>

        <!-- Bagian utama grid 2 kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[400px]">

            <!-- Kiri: Avatar + Username + Status (rata tengah) -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 flex flex-col items-center justify-center text-center border-r border-gray-100">
                <div class="flex flex-col items-center space-y-6">
                    <!-- Avatar dengan styling yang lebih menarik -->
                    <div class="w-32 h-32 rounded-full bg-white shadow-lg flex items-center justify-center overflow-hidden border-4 border-white ring-2 ring-gray-200">
                        <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.8 1.6-9.8 4.9v2.4h19.6v-2.4c0-3.3-6.5-4.9-9.8-4.9z" />
                        </svg>
                    </div>

                    <!-- Username -->
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">
                            {{ Auth::user()->username ?? '-' }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Username</p>
                    </div>

                    <!-- Status Badge -->
                    <div class="inline-flex">
                        <span class="px-6 py-2 text-sm font-semibold rounded-full shadow-sm
                            @if(Auth::user()->role_id == 3)
                                bg-red-100 text-red-700 ring-2 ring-red-300
                            @elseif(Auth::user()->role_id == 1)
                                bg-blue-100 text-blue-700 ring-2 ring-blue-300
                            @else
                                bg-orange-100 text-orange-700 ring-2 ring-orange-300
                            @endif">

                            {{ Auth::user()->role->nama_role }}
                        </span>
                    </div>

                </div>
            </div>

            <!-- Kanan: Detail Profil -->
            <div class="p-8 bg-white">
                <div class="h-full flex flex-col justify-center space-y-4">

                    <!-- Info Items -->
                    <div class="space-y-1">
                        <div class="group hover:bg-gray-100 p-3 rounded-lg transition-colors duration-200 -mx-3">
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Nama Lengkap</p>
                            <p class="text-lg font-semibold text-gray-900">{{ Auth::user()->nama_lengkap ?? '-' }}</p>
                        </div>

                        <div class="group hover:bg-gray-100 p-3 rounded-lg transition-colors duration-200 -mx-3">
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">NIP</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono">{{ Auth::user()->nip ?? '-' }}</p>
                        </div>

                        <div class="group hover:bg-gray-100 p-3 rounded-lg transition-colors duration-200 -mx-3">
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Email</p>
                            <p class="text-lg font-semibold text-gray-900">{{ Auth::user()->email ?? '-' }}</p>
                        </div>

                        <div class="group hover:bg-gray-100 p-3 rounded-lg transition-colors duration-200 -mx-3">
                            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-1">Nomor HP</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono">{{ Auth::user()->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer dengan tombol aksi -->
        <div class="bg-gray-50 border-t border-gray-200 px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('profile.edit') }}"
                    class="w-full sm:w-auto px-6 py-3 text-sm font-semibold rounded-lg shadow-sm 
                          bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 
                          hover:border-gray-400 transition-all duration-200 text-center
                          focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <span class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ubah Biodata
                    </span>
                </a>

                <a href="{{ route('profile.password') }}"
                    class="w-full sm:w-auto px-6 py-3 text-sm font-semibold rounded-lg shadow-sm 
                          bg-orange-500 hover:bg-orange-600 text-white 
                          hover:shadow-md transition-all duration-200 text-center
                          focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    <span class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Info tambahan -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Terakhir diperbarui: {{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d M Y, H:i') : 'Belum pernah' }}
        </p>
    </div>
</div>

@endsection