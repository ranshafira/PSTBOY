@extends('layouts.app')

@section('title', 'Dashboard PST')

@section('content')

<div class="max-w-screen-xl mx-auto w-full flex-grow">

    {{-- Header dengan Filter --}}
    <div class="mb-8">
        <div class="flex flex-col gap-6">
            <!-- Title Section dengan Gradient Background -->
            <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-red-600 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                            </svg>
                            <h1 class="text-3xl font-bold">Dashboard Pelayanan Statistik Terpadu</h1>
                        </div>
                        <p class="text-sm text-orange-100 ml-11">
                            <span class="font-semibold">BPS Kabupaten Boyolali</span> • {{ $dateRangeDisplay }}
                            <span class="inline-flex items-center ml-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                                {{ $daysDiff }} hari
                            </span>
                        </p>
                    </div>
                    <div class="hidden lg:block">
                        <svg class="w-24 h-24 text-white/10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Filter Form dengan Improved Design --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700">Filter Data</h3>
                    </div>
                </div>

                <div class="p-6">
                    <form id="filterForm" method="GET" action="{{ route('admin.dashboard') }}" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Tanggal Mulai
                                    </span>
                                </label>
                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    value="{{ $startDate }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Tanggal Akhir
                                    </span>
                                </label>
                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    value="{{ $endDate }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label for="petugas_id" class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Petugas
                                    </span>
                                </label>
                                <select
                                    id="petugas_id"
                                    name="petugas_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    <option value="all" {{ $petugasId == 'all' ? 'selected' : '' }}>Semua Petugas</option>
                                    @foreach($daftarPetugas as $petugas)
                                    <option value="{{ $petugas->id }}" {{ $petugasId == $petugas->id ? 'selected' : '' }}>
                                        {{ $petugas->nama_lengkap }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button
                                    type="submit"
                                    class="flex-1 px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filter
                                </button>

                                <button
                                    type="button"
                                    onclick="resetFilter()"
                                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Quick Filter Buttons --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide mr-2">Quick Filter:</span>
                                <button type="button" onclick="setQuickFilter(7)" class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 rounded-lg transition-all shadow-sm hover:shadow">7 Hari</button>
                                <button type="button" onclick="setQuickFilter(30)" class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-700 rounded-lg transition-all shadow-sm hover:shadow">30 Hari</button>
                                <button type="button" onclick="setQuickFilter(90)" class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 text-purple-700 rounded-lg transition-all shadow-sm hover:shadow">90 Hari</button>
                                <button type="button" onclick="setQuickFilter('ytd')" class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 text-amber-700 rounded-lg transition-all shadow-sm hover:shadow">Tahun Ini</button>
                                <button type="button" onclick="setQuickFilter('all')" class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-700 rounded-lg transition-all shadow-sm hover:shadow">Semua Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 1: KPI Cards dengan Enhanced Design --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-blue-600/10 rounded-bl-full"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total</span>
                </div>
                <h2 class="text-sm font-medium text-gray-600 mb-2">Total Pelayanan</h2>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ $totalPelayananAll }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    Semua status
                </p>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400/10 to-green-600/10 rounded-bl-full"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">Selesai</span>
                </div>
                <h2 class="text-sm font-medium text-gray-600 mb-2">Pelayanan Selesai</h2>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ $totalPelayananSelesai }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                    </svg>
                    {{ $persentasePenyelesaian }}% dari total
                </p>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-400/10 to-amber-600/10 rounded-bl-full"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Aktif</span>
                </div>
                <h2 class="text-sm font-medium text-gray-600 mb-2">Petugas Aktif</h2>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ count($kinerjaPetugas) }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    Melayani di periode ini
                </p>
            </div>
        </div>

        <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-400/10 to-purple-600/10 rounded-bl-full"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Rating</span>
                </div>
                <h2 class="text-sm font-medium text-gray-600 mb-2">Rating Layanan</h2>
                <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($ratingTahunan->first()->avg_rating_layanan ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 {{ $i <= ($ratingTahunan->first()->avg_rating_layanan ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                </div>
                </p>
            </div>
        </div>
    </div>

    {{-- ROW 2: Charts dengan Enhanced Design --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
        {{-- Chart Jenis Layanan --}}
        <div class="bg-white rounded-xl shadow-md lg:col-span-3 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Volume per Jenis Layanan</h3>
                        <p class="text-xs text-gray-600">Distribusi berdasarkan kategori layanan</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="relative h-64">
                    <canvas id="chartJenisLayanan"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart Profil Pengunjung --}}
        <div class="bg-white rounded-xl shadow-md lg:col-span-2 overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Profil Pengunjung</h3>
                        <p class="text-xs text-gray-600">Demografi pengunjung</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col items-center">
                        <div class="relative h-40 w-full">
                            <canvas id="chartJenisKelamin"></canvas>
                        </div>
                        <span class="mt-3 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Jenis Kelamin</span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="relative h-40 w-full">
                            <canvas id="chartPendidikan"></canvas>
                        </div>
                        <span class="mt-3 text-xs font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full">Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: Tables dengan Enhanced Design --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 flex-grow">

        {{-- Kinerja Petugas --}}
        <div class="bg-white rounded-xl shadow-md flex flex-col overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Kinerja Petugas</h3>
                            <p class="text-xs text-gray-600">Statistik pelayanan per petugas</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-orange-600 bg-orange-100 px-3 py-1 rounded-full">
                        {{ count($kinerjaPetugas) }} Petugas
                    </span>
                </div>
            </div>
            <div class="flex-grow overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 z-10">
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-6 font-semibold text-gray-700 text-left">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Nama Petugas
                                </div>
                            </th>
                            <th class="py-3 px-6 font-semibold text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    Total Layanan
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kinerjaPetugas as $index => $petugas)
                        <tr class="hover:bg-gradient-to-r hover:from-orange-50 hover:to-transparent transition-all group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center font-bold text-orange-700 text-sm group-hover:scale-110 transition-transform">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $petugas->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">Petugas PST</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center min-w-[60px] px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    {{ $petugas->total_layanan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 font-medium mb-1">Tidak Ada Data</p>
                                    <p class="text-sm text-gray-500">Tidak ada data petugas di periode ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Ringkasan Survei --}}
        <div class="bg-white rounded-xl shadow-md flex flex-col overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Ringkasan Survei</h3>
                            <p class="text-xs text-gray-600">Evaluasi kepuasan pelanggan</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-3 py-1 rounded-full">
                        Rating
                    </span>
                </div>
            </div>
            <div class="flex-grow overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-50 z-10">
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 font-semibold text-gray-700 text-left">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Tahun
                                </div>
                            </th>
                            <th class="py-3 px-3 font-semibold text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="hidden sm:inline">Survei</span>
                                </div>
                            </th>
                            <th class="py-3 px-3 font-semibold text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                    <span class="hidden sm:inline">Layanan</span>
                                </div>
                            </th>
                            <th class="py-3 px-3 font-semibold text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="hidden sm:inline">Petugas</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($ratingTahunan as $rating)
                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-transparent transition-all group">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $rating->tahun }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-800 group-hover:bg-blue-200 transition-colors">
                                    {{ $rating->jumlah }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm {{ $rating->avg_rating_layanan >= 4.5 ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : ($rating->avg_rating_layanan >= 4 ? 'bg-gradient-to-r from-green-400 to-green-500 text-white' : ($rating->avg_rating_layanan >= 3 ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white' : 'bg-gradient-to-r from-red-400 to-red-500 text-white')) }} group-hover:scale-105 transition-transform">
                                        {{ number_format($rating->avg_rating_layanan, 2) }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm {{ $rating->avg_rating_petugas >= 4.5 ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : ($rating->avg_rating_petugas >= 4 ? 'bg-gradient-to-r from-green-400 to-green-500 text-white' : ($rating->avg_rating_petugas >= 3 ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white' : 'bg-gradient-to-r from-red-400 to-red-500 text-white')) }} group-hover:scale-105 transition-transform">
                                        {{ number_format($rating->avg_rating_petugas, 2) }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 font-medium mb-1">Tidak Ada Data Survei</p>
                                    <p class="text-sm text-gray-500">Belum ada survei di periode ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Quick Filter Functions
    function setQuickFilter(days) {
        const endDate = new Date();
        let startDate = new Date();

        if (days === 'ytd') {
            startDate = new Date(endDate.getFullYear(), 0, 1);
        } else if (days === 'all') {
            startDate = new Date(endDate.getFullYear() - 5, 0, 1);
        } else {
            startDate.setDate(endDate.getDate() - days + 1);
        }

        document.getElementById('start_date').value = formatDateInput(startDate);
        document.getElementById('end_date').value = formatDateInput(endDate);
        document.getElementById('filterForm').submit();
    }

    function resetFilter() {
        setQuickFilter(30);
    }

    function formatDateInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Chart.js Configuration
    document.addEventListener('DOMContentLoaded', () => {
        const dataJenisLayanan = @json($chartPengunjung['menurut_jenis_layanan']);
        const dataJenisKelamin = @json($chartPengunjung['menurut_jenis_kelamin']);
        const dataPendidikan = @json($chartPengunjung['menurut_pendidikan']);

        // Enhanced Color Palettes
        const barColors = [
            'rgba(59, 130, 246, 0.85)',
            'rgba(16, 185, 129, 0.85)',
            'rgba(245, 158, 11, 0.85)',
            'rgba(139, 92, 246, 0.85)',
            'rgba(236, 72, 153, 0.85)',
            'rgba(6, 182, 212, 0.85)',
            'rgba(251, 146, 60, 0.85)'
        ];

        const doughnutColors = [
            'rgba(59, 130, 246, 0.9)',
            'rgba(236, 72, 153, 0.9)',
            'rgba(16, 185, 129, 0.9)',
            'rgba(245, 158, 11, 0.9)',
            'rgba(139, 92, 246, 0.9)',
            'rgba(6, 182, 212, 0.9)',
            'rgba(251, 146, 60, 0.9)'
        ];

        // Chart Defaults
        Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
        Chart.defaults.color = '#6B7280';

        // Chart Jenis Layanan
        new Chart(document.getElementById('chartJenisLayanan'), {
            type: 'bar',
            data: {
                labels: Object.keys(dataJenisLayanan),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: Object.values(dataJenisLayanan),
                    backgroundColor: barColors,
                    borderRadius: 8,
                    borderWidth: 0,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.x + ' pengunjung';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0,
                            color: '#6B7280',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#374151',
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        // Chart Jenis Kelamin
        new Chart(document.getElementById('chartJenisKelamin'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataJenisKelamin),
                datasets: [{
                    data: Object.values(dataJenisKelamin),
                    backgroundColor: doughnutColors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#374151',
                            padding: 15,
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return ' ' + context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Chart Pendidikan
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataPendidikan),
                datasets: [{
                    data: Object.values(dataPendidikan),
                    backgroundColor: doughnutColors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#374151',
                            padding: 15,
                            font: {
                                size: 11,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return ' ' + context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush