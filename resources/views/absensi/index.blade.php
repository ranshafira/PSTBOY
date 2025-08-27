@extends('adminlte::page')

@section('title', 'Absensi')

@section('content_header')
    <h1>Absensi Petugas PST</h1>
    <p class="text-muted">Sistem pencatatan kehadiran dan kepulangan harian</p>
@stop

@section('content')
    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-lg-8">
            {{-- Card Status Hari Ini --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fw fa-clock"></i> Status Hari Ini
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y • H:i') }}</p>
                    <div class="row text-center">
                        <div class="col-md-6 border-right">
                            <p class="mb-0">→ Waktu Masuk</p>
                            <h3 class="font-weight-bold">
                                {{ $presensiHariIni && $presensiHariIni->waktu_datang ? \Carbon\Carbon::parse($presensiHariIni->waktu_datang)->format('H:i') : '-' }}
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">← Waktu Keluar</p>
                            <h3 class="font-weight-bold">
                                {{ $presensiHariIni && $presensiHariIni->waktu_pulang ? \Carbon\Carbon::parse($presensiHariIni->waktu_pulang)->format('H:i') : '-' }}
                            </h3>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        @if (!$presensiHariIni)
                            {{-- Belum Check In --}}
                            <form action="{{ route('absensi.checkin') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-lg btn-success">
                                    <i class="fas fa-sign-in-alt"></i> Check In
                                </button>
                            </form>
                        @elseif (!$presensiHariIni->waktu_pulang)
                            {{-- Sudah Check In tapi Belum Check Out --}}
                            <form action="{{ route('absensi.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-lg btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                </button>
                            </form>
                        @else
                            {{-- Sudah Check In dan Check Out --}}
                            <p class="text-success font-weight-bold">Anda sudah menyelesaikan absensi hari ini.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Riwayat Absensi --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fw fa-history"></i> Riwayat Absensi
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Data kehadiran dalam 10 hari terakhir</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Jam Kerja</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatAbsensi as $presensi)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($presensi->waktu_datang)->format('H:i') }}</td>
                                    <td>{{ $presensi->waktu_pulang ? \Carbon\Carbon::parse($presensi->waktu_pulang)->format('H:i') : '-' }}</td>
                                    <td>
                                        @if ($presensi->waktu_pulang)
                                            {{ \Carbon\Carbon::parse($presensi->waktu_datang)->diff(\Carbon\Carbon::parse($presensi->waktu_pulang))->format('%h jam %i mnt') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><span class="badge badge-success">Hadir</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada riwayat absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        {{-- Kolom Kanan --}}
        <div class="col-lg-4">
            {{-- Card Statistik --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fw fa-chart-pie"></i> Statistik Kehadiran
                    </h3>
                </div>
                <div class="card-body text-center">
                    <h1 class="font-weight-bold display-4 text-orange">{{ $statistik['tingkat_kehadiran'] }}%</h1>
                    <p class="text-muted mb-3">Tingkat Kehadiran</p>
                    <div class="d-flex justify-content-around">
                        <div>
                            <p class="mb-0">Hari Hadir</p>
                            <h4 class="font-weight-bold">{{ $statistik['hari_hadir'] }} hari</h4>
                        </div>
                        <div>
                            <p class="mb-0">Total Hari</p>
                            <h4 class="font-weight-bold">{{ $statistik['total_hari'] }} hari</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Informasi --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-fw fa-info-circle"></i> Informasi
                    </h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Jam kerja standar: 08:00 - 16:00</li>
                        <li>Check-in otomatis mencatat waktu kedatangan</li>
                        <li>Jangan lupa check-out saat pulang</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Tambahkan CSS kustom di sini jika diperlukan --}}
    <style>
        .text-orange {
            color: #fd7e14;
        }
    </style>
@stop

@section('js')
    {{-- Tambahkan JS di sini jika diperlukan, misalnya untuk notifikasi --}}
    @if(session('success'))
        <script>
            Swal.fire(
                'Berhasil!',
                '{{ session('success') }}',
                'success'
            )
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire(
                'Gagal!',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
@stop