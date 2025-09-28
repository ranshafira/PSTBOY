@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Dashboard PST')

@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8 flex flex-col">
    <div class="max-w-screen-xl mx-auto w-full flex-grow">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Pelayanan Statistik Terpadu</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Analisis Kinerja PST BPS Kabupaten Boyolali — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>

        {{-- ROW 1: Key Performance Indicators (KPI) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pelayanan Selesai</h2>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $totalPelayananSelesai }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas Aktif</h2>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ count($kinerjaPetugas) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating Layanan Rata-Rata ({{ $ratingTahunan->first()->tahun ?? 'N/A' }})</h2>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ number_format($ratingTahunan->first()->avg_rating_layanan ?? 0, 2) }} <span class="text-2xl text-gray-400">/ 5</span></p>
            </div>
        </div>

        {{-- ROW 2: Visualisasi Data (Charts) --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            {{-- Chart Jenis Layanan --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm lg:col-span-3">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Volume per Jenis Layanan</h3>
                <div class="h-64">
                    <canvas id="chartJenisLayanan"></canvas>
                </div>
            </div>
            {{-- Chart Jenis Kelamin & Pendidikan --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm lg:col-span-2">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Profil Pengunjung</h3>
                {{-- 1. Hapus tinggi tetap h-64 dari sini --}}
                <div class="grid grid-cols-2 gap-6 pt-4">
                    <div class="flex flex-col items-center">
                        {{-- 2. Beri 'rumah' yang jelas untuk canvas dengan tinggi yang responsif --}}
                        <div class="relative h-48 w-full">
                            <canvas id="chartJenisKelamin"></canvas>
                        </div>
                        <span class="mt-3 text-sm font-medium text-gray-500">Jenis Kelamin</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="relative h-48 w-full">
                            <canvas id="chartPendidikan"></canvas>
                        </div>
                        <span class="mt-3 text-sm font-medium text-gray-500">Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Data Detail (Tables) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Tabel Kinerja Petugas --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Kinerja Petugas</h3>
                <div class="overflow-y-auto max-h-64">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400">Nama Petugas</th>
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400 text-center">Total Layanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kinerjaPetugas as $petugas)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $petugas->nama_lengkap }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200 text-center font-bold">{{ $petugas->total_layanan }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Rating Tahunan --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Ringkasan Survei Tahunan</h3>
                <div class="overflow-y-auto max-h-64">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400">Tahun</th>
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400 text-center">Jml Survei</th>
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400 text-center">Avg. Layanan</th>
                                <th class="py-2 font-medium text-gray-500 dark:text-gray-400 text-center">Avg. Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratingTahunan as $rating)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $rating->tahun }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200 text-center">{{ $rating->jumlah }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200 text-center">{{ number_format($rating->avg_rating_layanan, 2) }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200 text-center">{{ number_format($rating->avg_rating_petugas, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dataJenisLayanan = @json($chartPengunjung['menurut_jenis_layanan']);
        const dataJenisKelamin = @json($chartPengunjung['menurut_jenis_kelamin']);
        const dataPendidikan = @json($chartPengunjung['menurut_pendidikan']);

        const chartColors = ['#2563eb', '#f97316', '#16a34a', '#ef4444', '#9333ea', '#64748b'];

        // 1. Chart Jenis Layanan (Horizontal Bar)
        new Chart(document.getElementById('chartJenisLayanan'), {
            type: 'bar',
            data: {
                labels: Object.keys(dataJenisLayanan),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: Object.values(dataJenisLayanan),
                    backgroundColor: chartColors,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // 2. Chart Jenis Kelamin (Doughnut)
        new Chart(document.getElementById('chartJenisKelamin'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataJenisKelamin),
                datasets: [{
                    data: Object.values(dataJenisKelamin),
                    backgroundColor: [chartColors[0], chartColors[4]],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // 3. Chart Pendidikan (Doughnut)
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataPendidikan),
                datasets: [{
                    data: Object.values(dataPendidikan),
                    backgroundColor: [chartColors[1], chartColors[2], chartColors[5]],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush