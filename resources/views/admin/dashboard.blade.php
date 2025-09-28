@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Dashboard PST')

@section('content')
<div class="dark:bg-gray-900 p-4 flex flex-col">
    <div class="max-w-screen-xl mx-auto w-full flex-grow">

        {{-- Header --}}
        <div class="mb-4"> {{-- margin-bottom sedikit dikurangi --}}
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Pelayanan Statistik Terpadu</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Analisis Kinerja PST BPS Kabupaten Boyolali — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>

        {{-- ROW 1: Key Performance Indicators (KPI) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4"> {{-- gap dan mb dikurangi --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm"> {{-- padding dan rounded dikurangi --}}
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Pelayanan Selesai</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalPelayananSelesai }}</p> {{-- text-3xl --}}
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Petugas Aktif</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ count($kinerjaPetugas) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Rating Layanan Rata-Rata ({{ $ratingTahunan->first()->tahun ?? 'N/A' }})</h2>
                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ number_format($ratingTahunan->first()->avg_rating_layanan ?? 0, 2) }} <span class="text-xl text-gray-400">/ 5</span></p> {{-- text-xl --}}
            </div>
        </div>

        {{-- ROW 2: Visualisasi Data (Charts) --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4"> {{-- gap dan mb dikurangi --}}
            {{-- Chart Jenis Layanan --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm lg:col-span-3"> {{-- padding dan rounded dikurangi --}}
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Volume per Jenis Layanan</h3> {{-- mb dikurangi --}}
                <div class="relative h-56"> {{-- Tinggi chart disesuaikan --}}
                    <canvas id="chartJenisLayanan"></canvas>
                </div>
            </div>
            {{-- Chart Jenis Kelamin & Pendidikan --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm lg:col-span-2"> {{-- padding dan rounded dikurangi --}}
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Profil Pengunjung</h3> {{-- mb dikurangi --}}
                <div class="grid grid-cols-2 gap-4 pt-2"> {{-- pt dikurangi --}}
                    <div class="flex flex-col items-center">
                        <div class="relative h-36 w-full"> {{-- Tinggi chart disesuaikan --}}
                            <canvas id="chartJenisKelamin"></canvas>
                        </div>
                        <span class="mt-2 text-xs font-medium text-gray-500">Jenis Kelamin</span> {{-- text-xs --}}
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="relative h-36 w-full"> {{-- Tinggi chart disesuaikan --}}
                            <canvas id="chartPendidikan"></canvas>
                        </div>
                        <span class="mt-2 text-xs font-medium text-gray-500">Pendidikan</span> {{-- text-xs --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Data Detail (Tables) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-grow"> {{-- gap dikurangi, flex-grow untuk mengisi sisa ruang --}}
            {{-- Tabel Kinerja Petugas --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex flex-col"> {{-- padding dan rounded dikurangi, flex flex-col --}}
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Kinerja Petugas</h3> {{-- mb dikurangi --}}
                <div class="overflow-y-auto flex-grow"> {{-- max-h-64 dihilangkan, pakai flex-grow --}}
                    <table class="w-full text-left text-xs"> {{-- text-xs --}}
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400">Nama Petugas</th> {{-- py dikurangi --}}
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Total Layanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kinerjaPetugas as $petugas)
                            <tr class="border-b dark:border-gray-700 last:border-b-0"> {{-- last:border-b-0 untuk tabel lebih rapi --}}
                                <td class="py-1.5 text-gray-800 dark:text-gray-200">{{ $petugas->nama_lengkap }}</td> {{-- py dikurangi --}}
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

            {{-- Tabel Rating Tahunan --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex flex-col"> {{-- padding dan rounded dikurangi, flex flex-col --}}
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Ringkasan Survei Tahunan</h3> {{-- mb dikurangi --}}
                <div class="overflow-y-auto flex-grow"> {{-- max-h-64 dihilangkan, pakai flex-grow --}}
                    <table class="w-full text-left text-xs"> {{-- text-xs --}}
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400">Tahun</th> {{-- py dikurangi --}}
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Jml Survei</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Avg. Layanan</th>
                                <th class="py-1.5 font-semibold text-gray-500 dark:text-gray-400 text-center">Avg. Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratingTahunan as $rating)
                            <tr class="border-b dark:border-gray-700 last:border-b-0"> {{-- last:border-b-0 untuk tabel lebih rapi --}}
                                <td class="py-1.5 text-gray-800 dark:text-gray-200">{{ $rating->tahun }}</td> {{-- py dikurangi --}}
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

        // Palet Warna Soft Baru
        const softColors = [
            'rgba(96, 165, 250, 0.8)', // sky-400
            'rgba(52, 211, 153, 0.8)', // emerald-400
            'rgba(251, 191, 36, 0.8)', // amber-400
            'rgba(167, 139, 250, 0.8)', // violet-400
            'rgba(251, 113, 133, 0.8)', // rose-400
            'rgba(148, 163, 184, 0.8)', // slate-400
            'rgba(74, 222, 128, 0.8)' // green-400
        ];

        // 1. Chart Jenis Layanan (Horizontal Bar)
        new Chart(document.getElementById('chartJenisLayanan'), {
            type: 'bar',
            data: {
                labels: Object.keys(dataJenisLayanan),
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: Object.values(dataJenisLayanan),
                    backgroundColor: softColors, // Menggunakan palet soft
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
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)' // Warna grid soft
                        },
                        ticks: {
                            precision: 0,
                            color: 'rgb(107, 114, 128)' // Warna ticks soft
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)' // Warna grid soft
                        },
                        ticks: {
                            color: 'rgb(107, 114, 128)' // Warna ticks soft
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
                    backgroundColor: [softColors[0], softColors[3]], // Menggunakan palet soft
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            color: 'rgb(107, 114, 128)' // Warna legend soft
                        }
                    }
                }
            }
        });

        // 3. Chart Pendidikan (Doughnut)
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(dataPendidikan),
                datasets: [{
                    data: Object.values(dataPendidikan),
                    backgroundColor: [softColors[1], softColors[2], softColors[5]], // Menggunakan palet soft
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            color: 'rgb(107, 114, 128)' // Warna legend soft
                        }
                    }
                }
            }
        });
    });
</script>
@endpush