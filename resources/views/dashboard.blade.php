@extends('layouts.app')

@section('title', 'Dashboard Antrian PST')

@section('content')
  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Antrian</h1>
    <p class="text-gray-600">Monitoring sistem antrian hari ini - {{ now()->format('d F Y') }}</p>
  </div>

  <!-- Cards Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Antrian Hari Ini -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">Total Antrian</p>
          <p class="text-3xl font-bold text-gray-800">{{ $totalAntrianHariIni }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
      </div>
    </div>

    <!-- Sudah Dilayani -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">Sudah Dilayani</p>
          <p class="text-3xl font-bold text-green-600">{{ $sudahDilayani }}</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
    </div>

    <!-- Antrian Berjalan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">Sedang Dilayani</p>
          @if($antrianBerjalan)
            <p class="text-3xl font-bold text-red-600">{{ $antrianBerjalan->nomor_antrian }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $antrianBerjalan->jenisLayanan->nama ?? 'Layanan' }}</p>
          @else
            <p class="text-lg font-medium text-gray-400">Tidak Ada</p>
          @endif
        </div>
        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 12.714l-4.95-2.475a1 1 0 00-1.414.95v4.95a1 1 0 001.414.95l4.95-2.475a1 1 0 000-1.9z"></path>
          </svg>
        </div>
      </div>
    </div>

    <!-- Kunjungan Buku Tamu -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">Pelayanan Non-PST Hari Ini</p>
          <p class="text-3xl font-bold text-blue-600">{{ $bukuTamuCount }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Row untuk Chart dan Antrian per Layanan (diperbaiki) -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
  <!-- KIRI: area chart (ambil 2 kolom) -->
<div class="col-span-2 grid grid-cols-2 gap-4">
  
  <!-- Chart 1: Antrian Per Layanan -->
  <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Antrian Per Layanan Hari Ini</h3>
    <div class="space-y-2">
      @forelse($mediaLayananHariIni as $layanan)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
          <h4 class="font-medium text-gray-700 text-sm">{{ $layanan->mediaLayanan }}</h4>
          <div class="flex items-center space-x-1">
            <span class="text-xl font-bold text-orange-600">{{ $layanan->antrian }}</span>
            <span class="text-xs text-gray-500">antrian</span>
          </div>
        </div>
      @empty
        <div class="text-center py-6 text-gray-500 text-sm">Belum ada data layanan</div>
      @endforelse
    </div>
  </div>

  <!-- Chart 2: Bar Harian -->
  <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Total Layanan oleh {{ $user->username }} Bulan Ini</h3>
    <div class="relative h-48">
      <canvas id="trendChart"></canvas>
    </div>
  </div>

  <!-- Chart 3: Bar Jenis Layanan  -->
  <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Jenis Layanan oleh {{ $user->username }} Bulan Ini</h3>
    <div class="relative h-48">
      <canvas id="chartJenisLayanan"></canvas>
    </div>
  </div>

  <!-- Chart 4: Pie -->
  <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Proporsi Layanan oleh {{ $user->username }} Bulan Ini</h3>
    <div class="relative h-48 w-full flex justify-center items-center">
      <canvas id="pieChart" class="max-h-56 max-w-80"></canvas>
    </div>
  </div>

</div>

  <!-- KANAN: tabel riwayat (memanjang vertikal sejajar 2 baris chart kiri) -->
<div class="col-span-1 row-span-2">
  <div class="bg-white rounded-xl border border-gray-100 h-full overflow-hidden">
    <!-- Header -->
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
      <h3 class="text-base font-semibold text-gray-800">Riwayat layanan hari ini</h3>
    </div>

    <!-- Isi Tabel -->
    <div class="overflow-x-auto overflow-y-auto max-h-[80vh]">
      <table class="min-w-full border-collapse text-[13px] text-gray-700">
        <thead class="bg-gray-50 sticky top-0 text-gray-600">
          <tr class="border-b border-gray-200">
            <th class="px-3 py-2 text-center font-medium">Antrian</th>
            <th class="px-3 py-2 text-center font-medium">Nama</th>
            <th class="px-3 py-2 text-center font-medium">Status</th>
            <th class="px-3 py-2 text-center font-medium">Waktu</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayatGabungan as $item)
            @php
              $rawStatus = is_string($item->status) ? strtolower($item->status) : $item->status;

              $statusColors = [
                  'pending'          => 'bg-yellow-100 text-yellow-800',
                  'menunggu'         => 'bg-yellow-100 text-yellow-800',
                  'dipanggil'        => 'bg-blue-100 text-blue-800',
                  'sedang_dilayani'  => 'bg-purple-100 text-purple-800',
                  'selesai'          => 'bg-green-100 text-green-800',
                  'batal'            => 'bg-red-100 text-red-800',
              ];

              $statusText = [
                  'pending'          => 'Menunggu',
                  'menunggu'         => 'Menunggu',
                  'dipanggil'        => 'Dipanggil',
                  'sedang_dilayani'  => 'Sedang dilayani',
                  'selesai'          => 'Selesai',
                  'batal'            => 'Batal',
              ];
            @endphp

            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
              <td class="px-3 py-3 text-center text-gray-800 ">{{ $item->nomor_antrian }}</td>
              <td class="px-3 py-3 text-center truncate max-w-[90px]">{{ $item->nama }}</td>
              <td class="px-3 py-3 text-center">
                <span class="px-3 py-0.5 rounded-full text-xs {{ $statusColors[$rawStatus] ?? 'bg-gray-100 text-gray-800' }}">
                  {{ $statusText[$rawStatus] ?? ucfirst((string) $item->status) }}
                </span>
              </td>
              <td class="px-3 py-3 text-center text-gray-500 text-xs">
                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-6 text-center text-gray-400 text-sm">
                Belum ada antrian hari ini
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!-- Akhir Row Chart & Riwayat -->

@endsection

@push('scripts')
<!-- Chart.js & DataLabels plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    /* =======================
       1. Bar CHART TREND
    ======================= */
    const trendData = @json($trendHarian); // data sudah per pegawai login
    const dates = Object.keys(trendData);
    const totals = Object.values(trendData);

    new Chart(document.getElementById('trendChart'), {
        type: 'bar', // ganti line -> bar
        data: {
            labels: dates.map(d => {
                const date = new Date(d);
                return `${date.getDate()}/${date.getMonth() + 1}`;
            }),
            datasets: [{
                label: 'Jumlah Layanan',
                data: totals,
                backgroundColor: totals.map(v => v > 0 ? 'rgba(249, 115, 22, 0.8)' : 'rgba(203, 213, 225, 0.5)'), // oranye jika ada layanan, abu-abu jika 0
                borderColor: 'rgba(249, 115, 22, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `${context.raw} layanan`
                    }
                },
                datalabels: {
                    anchor: 'end',      // posisi label
                    align: 'end',
                    offset: -4,
                    color: '#000',
                    font: { weight: 'bold', size: 12 },
                    formatter: value => value
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    suggestedMax: Math.max(...totals) + 2,
                    ticks: { stepSize: 1 }
                },
                x: {
                    ticks: { color: 'rgb(107, 114, 128)' }
                }
            }
        },
        plugins: [ChartDataLabels]
    });


    /* =======================
       2. PIE CHART LAYANAN
    ======================= */
    const pieData = @json($pieLayananPersen);
    const labelsFull = Object.keys(pieData);
    const dataValues = Object.values(pieData);

    const labelAbbr = labelsFull.map(name =>
        name === 'Layanan Langsung' ? 'Langsung' : name
    );

    const softColors = [
        'rgba(96, 165, 250, 0.8)',   // sky-400
        'rgba(52, 211, 153, 0.8)',   // emerald-400
        'rgba(251, 191, 36, 0.8)',   // amber-400
        'rgba(167, 139, 250, 0.8)',  // violet-400
        'rgba(251, 113, 133, 0.8)',  // rose-400
        'rgba(148, 163, 184, 0.8)'   // slate-400
    ];

    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: labelAbbr,
            datasets: [{
                data: dataValues,
                backgroundColor: softColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '35%',
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'center',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 12,
                        padding: 10,
                        color: 'rgb(107, 114, 128)',
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1F2937',
                    titleColor: '#F9FAFB',
                    bodyColor: '#F9FAFB',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => `${labelsFull[context.dataIndex]}: ${context.raw}%`
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    formatter: v => v > 5 ? `${v}%` : '',
                    font: { size: 11, weight: 'bold' },
                    textAlign: 'center'
                }
            },
            animation: { duration: 1000, animateRotate: true, animateScale: true }
        },
        plugins: [ChartDataLabels]
    });


    /* =======================
       3. BAR CHART JENIS LAYANAN
    ======================= */
    const dataJenisLayanan = @json($menurutJenisLayanan);

    new Chart(document.getElementById('chartJenisLayanan'), {
        type: 'bar',
        data: {
            labels: Object.keys(dataJenisLayanan),
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: Object.values(dataJenisLayanan),
                backgroundColor: softColors
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(200, 200, 200, 0.2)' },
                    ticks: { precision: 0, color: 'rgb(107, 114, 128)' }
                },
                y: {
                    grid: { color: 'rgba(200, 200, 200, 0.2)' },
                    ticks: { color: 'rgb(107, 114, 128)' }
                }
            }
        }
    });
});
</script>
@endpush
