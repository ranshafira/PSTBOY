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
          <p class="text-sm font-medium text-gray-600 mb-1">Kunjungan Buku Tamu Hari Ini</p>
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

  <!-- Row untuk Chart dan Antrian per Layanan -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Chart Trend Harian -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Trend Antrian Bulan Ini</h3>
      <div class="relative h-64">
        <canvas id="trendChart"></canvas>
      </div>
    </div>
  
    <!-- Card 2: Pie Chart Layanan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Proporsi Layanan Bulan Ini</h3>
        <div class="relative h-64 w-full flex justify-center items-center">
            <canvas id="pieChart" class="max-h-64 max-w-64"></canvas>
        </div>
    </div>

    <!-- Antrian Per Layanan -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Antrian Per Layanan Hari Ini</h3>
      <div class="space-y-3">
        @forelse($antrianPerLayanan as $layanan)
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-800">{{ $layanan->nama_layanan }}</h4>
            <div class="flex items-center space-x-1">
              <span class="text-2xl font-bold text-orange-600">{{ $layanan->antrian_count }}</span>
              <span class="text-sm text-gray-500">antrian</span>
            </div>
          </div>
        @empty
          <div class="text-center py-8 text-gray-500">Belum ada data layanan</div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Riwayat Layanan -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800">Riwayat Layanan Hari Ini</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full table-fixed border-collapse">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($riwayatGabungan as $item)
            @php
              $rawStatus  = is_string($item->status) ? strtolower($item->status) : $item->status;
              $isBukuTamu = ($item->nomor_antrian === '-' || strtolower($item->nama_layanan) === 'buku tamu');
              $statusColors = [
                'pending'   => 'bg-yellow-100 text-yellow-800',
                'menunggu'  => 'bg-yellow-100 text-yellow-800',
                'dipanggil' => 'bg-blue-100 text-blue-800',
                'sedang_dilayani' => 'bg-purple-100 text-purple-800',
                'selesai'   => 'bg-green-100 text-green-800',
                'batal'     => 'bg-red-100 text-red-800',
              ];
              $statusText = [
                'pending'   => 'Menunggu',
                'menunggu'  => 'Menunggu',
                'dipanggil' => 'Dipanggil',
                'sedang_dilayani' => 'Sedang Dilayani',
                'selesai'   => 'Selesai',
                'batal'     => 'Batal',
              ];
            @endphp

            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 text-center">{{ $item->nomor_antrian }}</td>
              <td class="px-6 py-4 text-center">{{ $item->nama }}</td>
              <td class="px-6 py-4 text-center">{{ $item->nama_layanan }}</td>
              <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$rawStatus] ?? 'bg-gray-100 text-gray-800' }}">
                  {{ $statusText[$rawStatus] ?? ucfirst((string) $item->status) }}
                </span>
              </td>
              <td class="px-6 py-4 text-center text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
              </td>
              <td class="px-6 py-4 text-center">
                @if ($isBukuTamu)
                    <span class="text-gray-400 text-xs">-</span>
                @endif
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada antrian hari ini</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Chart.js & DataLabels plugin -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      //Trend Chart
      const trendData = @json($trendHarian);
      const dates = Object.keys(trendData);
      const totals = Object.values(trendData);

      const trendCtx = document.getElementById('trendChart').getContext('2d');
      new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: dates.map(date => {
            const d = new Date(date);
            return d.getDate() + '/' + (d.getMonth() + 1);
          }),
          datasets: [{
            label: 'Jumlah Antrian',
            data: totals,
            borderColor: '#f97316',
            backgroundColor: 'rgba(249, 115, 22, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
      });

    //Pie Chart Layanan
    const pieData = @json($pieLayananPersen); 
    const labelsFull = Object.keys(pieData);
    const dataValues = Object.values(pieData);

    //Label sesuai nama layanan
    const labelAbbr = labelsFull.map(name => {
        switch(name) {
            case 'Perpustakaan': return 'PRP';
            case 'Konsultasi Statistik': return 'KST';
            case 'Rekomendasi Statistik': return 'RST';
            case 'Pengaduan': return 'PLS';
            case 'Buku Tamu': return 'BT';
            default: return name;
        }
    });

    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labelAbbr,
            datasets: [{
                data: dataValues,
                backgroundColor: [
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 112, 102, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
                borderColor: '#ffffffff',
                borderWidth: 1,
                hoverBorderWidth: 4,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '35%', // Untuk doughnut effect
            plugins: {
                legend: {
                    display: true,
                    position: 'right', 
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 15,
                        font: { 
                            size: 12,
                            weight: '500'
                        },
                        color: '#000000ff' 
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
                        label: function(context) {
                            return labelsFull[context.dataIndex] + ': ' + context.raw + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    formatter: (value, context) => {
                        return value > 5 ? value + '%' : ''; 
                    },
                    font: { 
                        size: 11,
                        weight: 'bold'
                    },
                    textAlign: 'center',
                    textShadowColor: 'rgba(0,0,0,0.5)',
                    textShadowBlur: 3
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1000
            }
        },
        plugins: [ChartDataLabels]
    });
    });
  </script>
@endpush