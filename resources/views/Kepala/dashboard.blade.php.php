@extends('layouts.app') {{-- Sesuaikan dengan layout utama --}}

@section('title', 'Dashboard Kepala')

@section('content')
<div class="dark:bg-gray-900 p-4 flex flex-col">
    <div class="max-w-screen-xl mx-auto w-full flex-grow">

        {{-- Header --}}
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Kepala</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoring & Evaluasi Kinerja PST — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- ROW 1: KPI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Pelayanan Selesai</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalPelayananSelesai }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah Petugas Aktif</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ count($kinerjaPetugas) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Rata-Rata Rating Layanan ({{ $ratingTahunan->first()->tahun ?? 'N/A' }})</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                    {{ number_format($ratingTahunan->first()->avg_rating_layanan ?? 0, 2) }} <span class="text-xl text-gray-400">/ 5</span>
                </p>
            </div>
        </div>

        {{-- ROW 2: Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">
            {{-- Volume per Jenis Layanan --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm lg:col-span-3">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Volume per Jenis Layanan</h3>
                <div class="relative h-56">
                    <canvas id="chartJenisLayanan"></canvas>
                </div>
            </div>
            {{-- Profil Pengunjung --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm lg:col-span-2">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Profil Pengunjung</h3>
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="flex flex-col items-center">
                        <div class="relative h-36 w-full">
                            <canvas id="chartJenisKelamin"></canvas>
                        </div>
                        <span class="mt-2 text-xs font-medium text-gray-500">Jenis Kelamin</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="relative h-36 w-full">
                            <canvas id="chartPendidikan"></canvas>
                        </div>
                        <span class="mt-2 text-xs font-medium text-gray-500">Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Table Data --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-grow">
            {{-- Kinerja Petugas --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex flex-col">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Kinerja Petugas</h3>
                <div class="overflow-y-auto flex-grow">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400">Nama Petugas</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Total Layanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kinerjaPetugas as $petugas)
                            <tr class="border-b dark:border-gray-700 last:border-b-0">
                                <td class="py-1.5 text-gray-800 dark:text-gray-200">{{ $petugas->nama_lengkap }}</td>
                                <td class="py-1.5 text-gray-800 dark:text-gray-200 text-center font-bold">{{ $petugas->total_layanan }}</td>
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

            {{-- Ringkasan Survei Tahunan --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex flex-col">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Ringkasan Survei Tahunan</h3>
                <div class="overflow-y-auto flex-grow">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400">Tahun</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Jumlah Survei</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Avg. Layanan</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Avg. Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratingTahunan as $rating)
                            <tr class="border-b dark:border-gray-700 last:border-b-0">
                                <td class="py-1.5 text-gray-800 dark:text-gray-200">{{ $rating->tahun }}</td>
                                <td class="py-1.5 text-gray-800 dark:text-gray-200 text-center">{{ $rating->jumlah }}</td>
                                <td class="py-1.5 text-gray-800 dark:text-gray-200 text-center">{{ number_format($rating->avg_rating_layanan, 2) }}</td>
                                <td class="py-1.5 text-gray-800 dark:text-gray-200 text-center">{{ number_format($rating->avg_rating_petugas, 2) }}</td>
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

        const softColors = [
            'rgba(96, 165, 250, 0.8)',
            'rgba(52, 211, 153, 0.8)',
            'rgba(251, 191, 36, 0.8)',
            'rgba(167, 139, 250, 0.8)',
            'rgba(251, 113, 133, 0.8)',
            'rgba(148, 163, 184, 0.8)',
            'rgba(74, 222, 128, 0.8)'
        ];

        // Chart Jenis Layanan
        new Chart(document.getElementById('chartJenisLayanan'), {
            type: 'bar',
            data: {
                labels: Object.keys(dataJenisLayanan),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: Object.values(dataJenisLayanan),
                    backgroundColor: softColors,
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
                        beginAtZero: true,
                        ticks: {
                            precision: 0
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
                    backgroundColor: [softColors[0], softColors[3]],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Chart Pendidikan
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataPendidikan),
                datasets: [{
                    data: Object.values(dataPendidikan),
                    backgroundColor: [softColors[1], softColors[2], softColors[5]],
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