@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Administrator</h1>
        <p class="text-gray-600">Analisis dan monitoring sistem pelayanan - {{ now()->format('d F Y') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Antrian Hari Ini</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalAntrianHariIni }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Petugas</p>
            <p class="text-3xl font-bold text-gray-800">{{ $jumlahPetugas }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600 mb-1">Petugas Bertugas Hari Ini</p>
            <div class="mt-2 space-y-1">
                @forelse($petugasHariIni as $jadwal)
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">{{ $jadwal->user->nama_lengkap }}</span>
                @empty
                <p class="text-sm text-gray-500">Tidak ada jadwal hari ini</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Proporsi Pelayanan</h3>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <select name="filter_pie" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 text-sm">
                        <option value="minggu_ini" {{ $filterPie == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ $filterPie == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="triwulan_ini" {{ $filterPie == 'triwulan_ini' ? 'selected' : '' }}>Triwulan Ini</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-orange-500 text-white text-sm rounded-md hover:bg-orange-600">Filter</button>
                </form>
            </div>
            <div class="relative h-72 w-full flex justify-center items-center">
                @if($proporsiLayanan->isNotEmpty())
                    <canvas id="proporsiLayananChart"></canvas>
                @else
                    <p class="text-gray-500">Tidak ada data untuk periode ini.</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Petugas Terbaik (All-Time)</h3>
            <ol class="space-y-4">
                @forelse($topPetugas as $index => $item)
                <li class="flex items-center">
                    <span class="text-lg font-bold text-orange-500 mr-4">{{ $index + 1 }}</span>
                    <div>
                        <p class="font-semibold text-gray-700">{{ $item->petugas->nama_lengkap }}</p>
                        <p class="text-sm text-gray-500">{{ $item->jumlah_layanan }} pelayanan selesai</p>
                    </div>
                </li>
                @empty
                 <p class="text-gray-500">Belum ada data pelayanan.</p>
                @endforelse
            </ol>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Rata-Rata Skor Kepuasan</h3>
            <div class="flex items-center space-x-2">
                 <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <select name="filter_survei" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 text-sm">
                        <option value="bulanan" {{ $filterSurvei == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="triwulanan" {{ $filterSurvei == 'triwulanan' ? 'selected' : '' }}>Triwulanan</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-orange-500 text-white text-sm rounded-md hover:bg-orange-600">Filter</button>
                </form>
                <a href="{{ route('admin.dashboard.exportSurvei', ['filter_survei' => $filterSurvei]) }}" class="px-3 py-1.5 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">Ekspor</a>
            </div>
        </div>
        <div class="relative h-80">
            @if($hasilSurvei->isNotEmpty())
                <canvas id="hasilSurveiChart"></canvas>
            @else
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500">Tidak ada data survei untuk periode ini.</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Tren Pelayanan 12 Bulan Terakhir</h3>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <select name="filter_tren_layanan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 text-sm">
                    <option value="">Semua Layanan</option>
                    @foreach($daftarJenisLayanan as $layanan)
                    <option value="{{ $layanan->id }}" {{ $filterTrenLayananId == $layanan->id ? 'selected' : '' }}>{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-1.5 bg-orange-500 text-white text-sm rounded-md hover:bg-orange-600">Filter</button>
            </form>
        </div>
        <div class="relative h-80">
             @if($trenTahunan->isNotEmpty())
                <canvas id="trenTahunanChart"></canvas>
            @else
                <div class="flex items-center justify-center h-full">
                     <p class="text-gray-500">Tidak ada data pelayanan.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Chart Proporsi Layanan (Pie/Doughnut)
    const proporsiData = @json($proporsiLayanan);
    if (Object.keys(proporsiData).length > 0) {
        const proporsiCtx = document.getElementById('proporsiLayananChart').getContext('2d');
        new Chart(proporsiCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(proporsiData),
                datasets: [{
                    data: Object.values(proporsiData),
                    backgroundColor: ['#f97316', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }

    // 2. Chart Hasil Survei (Bar)
    const surveiData = @json($hasilSurvei);
    if (Object.keys(surveiData).length > 0) {
        const surveiCtx = document.getElementById('hasilSurveiChart').getContext('2d');
        new Chart(surveiCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(surveiData),
                datasets: [{
                    label: 'Rata-rata Skor (dari 5)',
                    data: Object.values(surveiData),
                    backgroundColor: 'rgba(249, 115, 22, 0.6)',
                    borderColor: 'rgba(249, 115, 22, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    
    // 3. Chart Tren Tahunan (Line)
    const trenData = @json($trenTahunan);
    if (Object.keys(trenData).length > 0) {
        const trenCtx = document.getElementById('trenTahunanChart').getContext('2d');
        const labels = Object.keys(trenData).map(bulan => {
            const [year, month] = bulan.split('-');
            return new Date(year, month - 1).toLocaleString('default', { month: 'short', year: 'numeric' });
        });
        
        new Chart(trenCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pelayanan',
                    data: Object.values(trenData),
                    fill: true,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

});
</script>
@endpush