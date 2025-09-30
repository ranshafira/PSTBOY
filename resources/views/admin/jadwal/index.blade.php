@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-10 p-6">
    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 mb-6 relative overflow-hidden border border-gray-100">
        <div class="absolute top-0 right-0 w-40 h-40 -mt-10 -mr-10 bg-orange-50 rounded-full opacity-30 z-0"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 -mb-8 -ml-8 bg-orange-50 rounded-full opacity-30 z-0"></div>

        <div class="relative z-10 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg class="h-6 w-6 mr-2 text-black-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Generate Jadwal Bulanan
            </h2>
            <p class="text-gray-600 mt-1">Buat jadwal kerja bulanan secara otomatis dan terstruktur</p>
        </div>

        <form id="generateForm" method="POST" action="{{ route('admin.jadwal.generate') }}" class="mb-8 relative z-10" onsubmit="return confirmGenerate(event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        Bulan
                    </label>
                    <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm hover:border-orange-300 transition-colors duration-200" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        Tahun
                    </label>
                    <input type="number" name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent shadow-sm hover:border-orange-300 transition-colors duration-200" value="{{ request('tahun', date('Y')) }}" required>
                </div>
            </div>

            <button type="submit" id="generateButton" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-md flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="btn-text">Generate Jadwal</span>
            </button>
        </form>
    </div>

    <!-- Simple Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm mx-4 text-center">
            <!-- Spinner -->
            <div class="flex justify-center mb-4">
                <svg class="animate-spin h-10 w-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 100 20 10 10 0 010-20z" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-dasharray="60 40" />
                </svg>
            </div>



            <!-- Text -->
            <h3 class="text-lg font-semibold text-gray-800">Memproses...</h3>
            <p class="text-gray-500 text-sm mt-1">Sedang membuat jadwal bulanan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 mb-6 relative overflow-hidden border border-gray-100">
        <div class="absolute top-0 right-0 w-40 h-40 -mt-10 -mr-10 bg-orange-50 rounded-full opacity-30 z-0"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 -mb-8 -ml-8 bg-orange-50 rounded-full opacity-30 z-0"></div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 relative z-10">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center mb-4 md:mb-0">
                <svg class="h-5 w-5 mr-2 text-black-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="relative">
                    Jadwal Bulanan Petugas PST
                </span>
            </h3>

            <div class="flex items-center">
                <button id="toggleLegendBtn" class="flex items-center text-sm text-gray-600 hover:text-orange-500 transition-colors duration-200 mr-4">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tampilkan Legenda
                </button>

                <!-- Tombol Ekspor CSV -->
                <button id="exportCsvBtn" class="flex items-center text-sm bg-green-600 text-white hover:bg-green-700 transition-colors duration-200 rounded-md px-3 py-2 mr-4">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="btn-text">Ekspor CSV</span>
                </button>

                <div class="flex items-center">
                    <form id="filterForm" class="flex items-center">
                        <select id="filterBulan" class="text-xs border border-gray-300 rounded-md mr-2 px-2 py-1">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                                @endfor
                        </select>
                        <input id="filterTahun" type="number" class="text-xs border border-gray-300 rounded-md mr-2 px-2 py-1 w-16" value="{{ date('Y') }}">
                        <button type="submit" class="text-xs bg-orange-500 text-white rounded-md px-2 py-1 hover:bg-orange-600 transition-colors">
                            <span class="btn-text">Filter</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Color Legend -->
        <div id="userLegend" class="hidden mb-6 p-4 bg-orange-50 rounded-lg border border-orange-100 shadow-sm">
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <span class="inline-block w-2 h-4 bg-orange-500 rounded-sm mr-2"></span>
                Warna Petugas:
            </h4>
            <div id="legendContent" class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <div id="calendar" class="min-h-screen bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
            <!-- Calendar will be rendered here -->
        </div>
    </div>
</div>

<!-- Enhanced Modal -->
<div id="modalBackdrop" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>
<div id="eventModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div id="modalDialog" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-18 0h18" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle">Detail Jadwal</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Petugas:</span>
                                <span class="text-sm text-gray-900" id="modalPetugas">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Tanggal:</span>
                                <span class="text-sm text-gray-900" id="modalDate">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Shift:</span>
                                <span class="text-sm text-gray-900" id="modalShift">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Waktu:</span>
                                <span class="text-sm text-gray-900" id="modalTime">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <button type="button" id="deleteJadwalBtn" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="btn-text">Hapus</span>
                </button>

                <a id="editJadwalBtn" href="#" class="inline-flex w-full justify-center rounded-md border border-transparent bg-orange-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>

                <button type="button" onclick="hideEventModal()" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Toast -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-y-4 opacity-0">
    <div class="flex p-4 items-center">
        <div id="toastIcon" class="flex-shrink-0 bg-white rounded-full p-1 shadow-sm">
            <!-- Icon will be inserted here -->
        </div>
        <div class="ml-3 w-0 flex-1">
            <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button onclick="hideToast()" class="bg-white rounded-full p-1 inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
    <div class="h-1 bg-gradient-to-r from-orange-500 to-orange-300 rounded-b-lg animate-pulse"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
    /* Enhanced Loading Animations */
    @keyframes modernSpin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }

        50% {
            transform: scale(1.05);
        }

        70% {
            transform: scale(0.9);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes progressBar {
        0% {
            width: 0%;
        }

        25% {
            width: 25%;
        }

        50% {
            width: 50%;
        }

        75% {
            width: 75%;
        }

        100% {
            width: 100%;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    @keyframes floatUpDown {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-15px);
        }
    }

    @keyframes dotPulse {

        0%,
        80%,
        100% {
            transform: scale(0.5);
            opacity: 0.5;
        }

        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Modern Spinner */
    .modern-spinner {
        width: 64px;
        height: 64px;
        border: 4px solid rgba(249, 115, 22, 0.1);
        border-left: 4px solid #F97316;
        border-top: 4px solid #FB923C;
        border-radius: 50%;
        animation: modernSpin 1.2s linear infinite;
        position: relative;
    }

    .modern-spinner::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        right: 2px;
        bottom: 2px;
        border: 2px solid transparent;
        border-left: 2px solid rgba(249, 115, 22, 0.5);
        border-radius: 50%;
        animation: modernSpin 0.8s linear infinite reverse;
    }

    /* Pulse Ring */
    .pulse-ring {
        width: 80px;
        height: 80px;
        border: 3px solid rgba(249, 115, 22, 0.2);
        border-radius: 50%;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Loading Card */
    .loading-card {
        animation: bounceIn 0.6s ease-out;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Loading Text */
    .loading-text {
        background: linear-gradient(45deg, #F97316, #FB923C, #F97316, #FB923C);
        background-size: 300% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 2s linear infinite;
        font-weight: 700;
    }

    /* Progress Bar */
    .progress-bar {
        width: 100%;
        height: 6px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 3px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #F97316, #FB923C, #F97316);
        background-size: 200% 100%;
        animation: progressBar 3s ease-in-out infinite, shimmer 1.5s linear infinite;
        border-radius: 3px;
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 1.5s linear infinite;
    }

    /* Status Dot */
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .status-processing {
        background: #F59E0B;
        animation: dotPulse 2s infinite;
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
    }

    .status-success {
        background: #10B981;
    }

    .status-error {
        background: #EF4444;
    }

    /* Float Animation */
    .float-animation {
        animation: floatUpDown 3s ease-in-out infinite;
    }

    /* Info Fade In */
    .info-fade-in {
        animation: slideInUp 0.8s ease-out 0.3s both;
    }

    /* Enhanced Button Loading States */
    .btn-loading {
        position: relative;
        pointer-events: none;
        overflow: hidden;
    }

    .btn-loading::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-right: 2px solid transparent;
        border-radius: 50%;
        animation: modernSpin 1s linear infinite;
        z-index: 1;
    }

    .btn-loading .btn-text {
        opacity: 0;
    }

    /* Calendar Loading Modern */
    .calendar-loading-modern {
        position: relative;
    }

    .calendar-loading-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(249, 115, 22, 0.05));
        backdrop-filter: blur(8px);
        z-index: 20;
        border-radius: 0.75rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .calendar-loading-modern::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 4px solid rgba(249, 115, 22, 0.2);
        border-top-color: #F97316;
        border-right-color: #FB923C;
        z-index: 21;
        animation: modernSpin 1.2s linear infinite;
    }

    /* Toast Improvements */
    .toast-success {
        background: linear-gradient(135deg, #10B981, #059669);
        border: none;
        color: white;
    }

    .toast-error {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        border: none;
        color: white;
    }

    .toast-enter {
        animation: slideInUp 0.4s ease-out;
    }

    .toast-exit {
        animation: slideInUp 0.3s ease-in reverse;
    }

    /* Enhanced Calendar Styles */
    .fc-theme-standard .fc-scrollgrid {
        border-radius: 8px;
        overflow: hidden;
    }

    .fc-col-header-cell {
        background-color: #ff8c42 !important;
        color: white !important;
        font-weight: bold;
        text-transform: uppercase;
        padding: 10px 0;
    }

    .fc-col-header-cell-cushion {
        color: white !important;
        font-weight: bold;
        padding: 8px;
    }

    .fc-daygrid-day-number {
        font-weight: bold;
        color: #333;
    }

    .fc-event {
        border-radius: 6px !important;
        border-width: 0 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .fc-toolbar-title {
        color: #ff8c42;
        font-weight: bold;
        font-size: 1.5rem !important;
        text-transform: uppercase;
    }

    .fc-button-primary {
        background-color: #ffffff !important;
        border-color: #e5e7eb !important;
        color: #374151 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.2s ease !important;
    }

    .fc-button-primary:hover {
        background-color: #f9fafb !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-1px);
    }

    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: #111827 !important;
        box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.2) !important;
        transform: translateY(0);
    }

    .fc-button-primary:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.2) !important;
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        .loading-card {
            padding: 1.5rem;
            margin: 1rem;
        }

        .modern-spinner {
            width: 50px;
            height: 50px;
        }

        .pulse-ring {
            width: 66px;
            height: 66px;
        }

        .loading-text {
            font-size: 1.5rem;
        }

        .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }

        .fc-button {
            padding: 0.5rem 0.875rem !important;
            font-size: 0.75rem !important;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }
</style>

<script type="text/javascript">
    // Function to confirm schedule generation
    function confirmGenerate(event) {
        if (!confirm('Apakah anda yakin untuk menggenerate jadwal?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Status messages untuk loading yang realistis
        const statusMessages = [
            "Menganalisis data petugas...",
            "Mengecek ketersediaan shift...",
            "Mengatur jadwal bulanan...",
            "Memvalidasi konflik waktu...",
            "Menyimpan jadwal baru...",
            "Memperbarui database...",
            "Hampir selesai..."
        ];

        let statusIndex = 0;
        let statusInterval;
        let loadingStartTime;
        let userColors = {};

        // Get current date for default values
        const currentDate = new Date();

        // Initialize current month and year variables for filtering
        const filterBulanEl = document.getElementById('filterBulan');
        const filterTahunEl = document.getElementById('filterTahun');

        let currentMonth, currentYear;

        if (filterBulanEl && filterBulanEl.value) {
            currentMonth = parseInt(filterBulanEl.value);
        } else {
            currentMonth = currentDate.getMonth() + 1;
            if (filterBulanEl) filterBulanEl.value = currentMonth;
        }

        if (filterTahunEl && filterTahunEl.value) {
            currentYear = parseInt(filterTahunEl.value);
        } else {
            currentYear = currentDate.getFullYear();
            if (filterTahunEl) filterTahunEl.value = currentYear;
        }

        // Function untuk menampilkan loading overlay yang diperbaiki
        function showLoadingOverlay(message = 'Sedang memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const subtextEl = document.getElementById('loadingSubtext');
            const statusTextEl = document.getElementById('statusText');

            if (subtextEl) subtextEl.textContent = message;

            overlay.classList.remove('hidden');
            loadingStartTime = Date.now();
            statusIndex = 0;

            if (statusTextEl) {
                statusTextEl.textContent = statusMessages[statusIndex];
            }

            // Cycle melalui status messages
            statusInterval = setInterval(() => {
                statusIndex = (statusIndex + 1) % statusMessages.length;
                if (statusTextEl) {
                    statusTextEl.textContent = statusMessages[statusIndex];
                }
            }, 1500);
        }

        function hideLoadingOverlay() {
            const overlay = document.getElementById('loadingOverlay');

            // Pastikan loading minimal 2 detik untuk UX yang baik
            const elapsedTime = Date.now() - loadingStartTime;
            const minLoadingTime = 2000;
            const remainingTime = Math.max(0, minLoadingTime - elapsedTime);

            setTimeout(() => {
                overlay.classList.add('hidden');
                if (statusInterval) {
                    clearInterval(statusInterval);
                }
            }, remainingTime);
        }

        // Enhanced button loading state
        function setButtonLoading(buttonElement, isLoading = true, loadingText = null) {
            if (!buttonElement) return;

            if (isLoading) {
                buttonElement.classList.add('btn-loading', 'opacity-75');
                buttonElement.disabled = true;

                const textSpan = buttonElement.querySelector('.btn-text');
                if (textSpan) {
                    textSpan.setAttribute('data-original-text', textSpan.textContent);
                    if (loadingText) {
                        textSpan.textContent = loadingText;
                    }
                }
            } else {
                buttonElement.classList.remove('btn-loading', 'opacity-75');
                buttonElement.disabled = false;

                const textSpan = buttonElement.querySelector('.btn-text');
                if (textSpan) {
                    const originalText = textSpan.getAttribute('data-original-text') || textSpan.textContent;
                    textSpan.textContent = originalText;
                }
            }
        }

        // Enhanced calendar loading
        function setCalendarLoading(isLoading = true) {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            if (isLoading) {
                calendarEl.classList.add('calendar-loading-modern');
                calendarEl.setAttribute('data-loading', 'true');
            } else {
                calendarEl.classList.remove('calendar-loading-modern');
                calendarEl.removeAttribute('data-loading');
            }
        }

        // Enhanced toast notification
        function showToast(message, type = 'success', duration = 5000) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            if (!toast || !toastMessage || !toastIcon) return;

            toastMessage.textContent = message;

            // Reset classes
            toast.className = 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-xl transform transition-all duration-300 ease-in-out';

            if (type === 'success') {
                toastIcon.innerHTML = `
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
                toast.classList.add('toast-success', 'toast-enter');
                toastIcon.className = 'flex-shrink-0 bg-green-500 rounded-full p-2 shadow-sm';
                toastMessage.className = 'text-sm font-medium text-white ml-3 w-0 flex-1';
            } else if (type === 'error') {
                toastIcon.innerHTML = `
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
                toast.classList.add('toast-error', 'toast-enter');
                toastIcon.className = 'flex-shrink-0 bg-red-500 rounded-full p-2 shadow-sm';
                toastMessage.className = 'text-sm font-medium text-white ml-3 w-0 flex-1';
            } else {
                toastIcon.innerHTML = `
                <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
                toast.classList.add('border-l-4', 'border-orange-500', 'bg-orange-50');
                toastIcon.className = 'flex-shrink-0 bg-white rounded-full p-1 shadow-sm';
                toastMessage.className = 'text-sm font-medium text-gray-900 ml-3 w-0 flex-1';
            }

            // Show toast
            toast.classList.remove('hidden', 'translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            // Auto hide
            setTimeout(() => {
                hideToast();
            }, duration);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            if (!toast) return;

            toast.classList.add('toast-exit', 'translate-y-4', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');

            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('toast-success', 'toast-error', 'toast-enter', 'toast-exit');
            }, 300);
        }

        // Helper function untuk nama bulan
        function getMonthName(monthNum) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return months[monthNum - 1];
        }

        // Format date helper
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Setup loading animation untuk generate form
        const generateForm = document.getElementById('generateForm');
        const generateButton = document.getElementById('generateButton');

        if (generateForm) {
            generateForm.addEventListener('submit', function(e) {
                showLoadingOverlay('Sedang menggenerate jadwal bulanan...');
                setButtonLoading(generateButton, true, 'Memproses...');
            });
        }

        // Initialize Calendar
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay,dayGridMonth'
            },
            height: 'auto',

            // PERUBAHAN UTAMA: Waktu disesuaikan dari 15:00 ke 15:30
            slotMinTime: '07:00:00',
            slotMaxTime: '16:00:00', // Diperpanjang sedikit untuk menampung shift siang sampai 15:30
            slotDuration: '00:30:00', // Ubah ke 30 menit untuk proporsi yang lebih baik
            slotLabelInterval: '01:00:00',

            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false
            },

            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false
            },

            weekends: false,
            hiddenDays: [0, 6],

            initialDate: new Date(currentYear, currentMonth - 1, 1),

            events: function(info, successCallback, failureCallback) {
                const startDate = new Date(info.start);
                const endDate = new Date(info.end);

                let viewMonth = startDate.getMonth() + 1;
                let viewYear = startDate.getFullYear();

                if (currentMonth && currentYear) {
                    viewMonth = currentMonth;
                    viewYear = currentYear;
                }

                console.log('Fetching events for date range:', {
                    start: info.start,
                    end: info.end,
                    viewMonth: viewMonth,
                    viewYear: viewYear
                });

                userColors = {};

                // Gunakan fungsi loading yang diperbaiki
                setCalendarLoading(true);

                // Validate input
                if (!viewMonth || isNaN(viewMonth) || viewMonth < 1 || viewMonth > 12) {
                    console.error('Invalid month value:', viewMonth);
                    viewMonth = new Date().getMonth() + 1;
                }
                if (!viewYear || isNaN(viewYear)) {
                    console.error('Invalid year value:', viewYear);
                    viewYear = new Date().getFullYear();
                }

                const startDateStr = formatDate(startDate);
                const endDateStr = formatDate(endDate);

                const eventsUrl = `{{ route('admin.jadwal.events') }}?start=${startDateStr}&end=${endDateStr}&month=${viewMonth}&year=${viewYear}`;
                console.log('Fetching from URL:', eventsUrl);

                fetch(eventsUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(`Events loaded for period:`, {
                            start: startDateStr,
                            end: endDateStr,
                            count: data.length
                        });

                        const processedEvents = data.map(event => {
                            if (!event.start || !event.title) {
                                console.warn('Invalid event format:', event);
                                return null;
                            }

                            if (event.user_id || event.extendedProps?.userId) {
                                const userId = event.user_id || event.extendedProps.userId;
                                userColors[userId] = {
                                    name: event.title || event.user_name || event.extendedProps?.userName,
                                    color: event.backgroundColor || event.color || '#888888',
                                    shift: event.shift || event.extendedProps?.shift
                                };
                            }

                            return event;
                        }).filter(event => event !== null);

                        successCallback(processedEvents);

                        // Update legend setelah delay yang cukup
                        setTimeout(updateLegend, 500);

                        // Hide loading dengan delay yang sesuai
                        setTimeout(() => {
                            setCalendarLoading(false);
                        }, 800);
                    })
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);

                        // Hide loading dan tampilkan error
                        setCalendarLoading(false);
                        showToast('Gagal memuat jadwal. Silakan coba lagi.', 'error');
                    });
            },

            datesSet: function(dateInfo) {
                console.log('Calendar view changed to:', dateInfo);

                const midDate = new Date(dateInfo.start.getTime() + (dateInfo.end.getTime() - dateInfo.start.getTime()) / 2);
                const viewMonth = midDate.getMonth() + 1;
                const viewYear = midDate.getFullYear();

                currentMonth = viewMonth;
                currentYear = viewYear;

                console.log('Updated current values:', {
                    currentMonth,
                    currentYear
                });

                const bulanSelect = document.querySelector('select[name="bulan"]');
                const tahunInput = document.querySelector('input[name="tahun"]');
                const filterBulan = document.getElementById('filterBulan');
                const filterTahun = document.getElementById('filterTahun');

                if (bulanSelect) bulanSelect.value = viewMonth;
                if (tahunInput) tahunInput.value = viewYear;
                if (filterBulan) filterBulan.value = viewMonth;
                if (filterTahun) filterTahun.value = viewYear;

                userColors = {};
            },

            eventDisplay: 'block',
            displayEventTime: true,

            eventClick: function(info) {
                info.jsEvent.preventDefault();
                showEventModal(info.event);
            },

            eventDidMount: function(info) {
                const shift = info.event.extendedProps.shift;

                if (info.event.extendedProps.userId) {
                    const userId = info.event.extendedProps.userId;
                    const userName = info.event.extendedProps.userName || info.event.extendedProps.petugas;

                    if (!userColors[userId]) {
                        userColors[userId] = {
                            name: userName,
                            color: info.event.backgroundColor,
                            shift: shift
                        };
                    }
                }

                info.el.style.color = 'white';
                info.el.style.borderRadius = '6px';
                info.el.style.border = '2px solid';
                info.el.style.fontSize = '12px';
                info.el.style.fontWeight = '500';
                info.el.style.padding = '4px 8px';
                info.el.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1)';
            },

            locale: 'id',
            firstDay: 1,

            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },

            allDaySlot: false,

            dayHeaderFormat: {
                weekday: 'long',
            }
        });

        calendar.render();

        // Toggle legenda
        document.getElementById('toggleLegendBtn').addEventListener('click', function() {
            const legend = document.getElementById('userLegend');
            if (legend.classList.contains('hidden')) {
                legend.classList.remove('hidden');
                this.innerHTML = '<svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Sembunyikan Legenda';
                updateLegend();
            } else {
                legend.classList.add('hidden');
                this.innerHTML = '<svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Tampilkan Legenda';
            }
        });

        // Update legenda warna user
        function updateLegend() {
            const legendContent = document.getElementById('legendContent');
            if (!legendContent) {
                console.error('Legend content element not found');
                return;
            }

            console.log('Updating legend with user colors:', userColors);
            legendContent.innerHTML = '';

            const legendHeader = document.createElement('div');
            legendHeader.className = 'col-span-full mb-2 pb-2 border-b border-gray-200';
            legendHeader.innerHTML = `
            <h4 class="text-sm font-semibold text-gray-800">Legenda Petugas</h4>
            <p class="text-xs text-gray-500">Warna unik untuk setiap petugas</p>
        `;
            legendContent.appendChild(legendHeader);

            const usersContainer = document.createElement('div');
            usersContainer.className = 'grid grid-cols-2 md:grid-cols-3 gap-2';

            const uniqueUsers = {};

            Object.keys(userColors).forEach(userId => {
                const userInfo = userColors[userId];

                if (typeof userInfo === 'string') {
                    const events = calendar.getEvents();
                    let userName = null;

                    for (let i = 0; i < events.length; i++) {
                        if (events[i].extendedProps && events[i].extendedProps.userId == userId) {
                            userName = events[i].extendedProps.userName || events[i].extendedProps.petugas;
                            break;
                        }
                    }

                    uniqueUsers[userId] = {
                        name: userName || `Petugas ${userId}`,
                        color: userInfo
                    };
                } else if (typeof userInfo === 'object' && userInfo !== null) {
                    if (!uniqueUsers[userId]) {
                        uniqueUsers[userId] = {
                            name: userInfo.name || `Petugas ${userId}`,
                            color: userInfo.color || '#888888'
                        };
                    }
                }
            });

            console.log('Unique users for legend:', uniqueUsers);

            Object.values(uniqueUsers).forEach(user => {
                const legendItem = document.createElement('div');
                legendItem.className = 'flex items-start p-1 rounded hover:bg-gray-100';
                legendItem.innerHTML = `
                <span class="inline-block w-4 h-4 rounded-full mr-2 mt-1" style="background-color: ${user.color}; border: 1px solid rgba(0,0,0,0.1);"></span>
                <span class="text-xs font-medium text-black" style="white-space: pre-wrap;">${user.name.replace(/ \(/, '\n(')}</span>
            `;
                usersContainer.appendChild(legendItem);
            });

            legendContent.appendChild(usersContainer);

            if (Object.keys(uniqueUsers).length === 0) {
                legendContent.innerHTML = '<p class="text-xs text-gray-500 col-span-full p-2">Tidak ada data jadwal untuk ditampilkan</p>';
            }
        }

        // Refresh calendar setelah generate jadwal dengan delay
        @if(session('success'))
        setTimeout(function() {
            console.log('Refreshing calendar after successful generation');
            calendar.refetchEvents();
            setTimeout(updateLegend, 1000);
        }, 1000);
        @endif

        // Store calendar reference globally
        window.calendarInstance = calendar;

        // Event listener untuk filter form dengan loading state
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let bulan = parseInt(document.getElementById('filterBulan').value);
            let tahun = parseInt(document.getElementById('filterTahun').value);

            console.log('Filter form submitted:', {
                bulan,
                tahun
            });

            // Validate input
            if (isNaN(bulan) || bulan < 1 || bulan > 12) {
                console.error('Invalid month value:', bulan);
                bulan = new Date().getMonth() + 1;
                document.getElementById('filterBulan').value = bulan;
            }

            if (isNaN(tahun) || tahun < 2000 || tahun > 2100) {
                console.error('Invalid year value:', tahun);
                tahun = new Date().getFullYear();
                document.getElementById('filterTahun').value = tahun;
            }

            // Show loading dengan pesan yang sesuai
            showLoadingOverlay('Memfilter jadwal...');
            setCalendarLoading(true);

            const submitBtn = this.querySelector('button[type="submit"]');
            setButtonLoading(submitBtn, true, 'Loading...');

            // Update global variables
            currentMonth = bulan;
            currentYear = tahun;

            console.log('Updated filter values:', {
                currentMonth,
                currentYear
            });

            userColors = {};

            const targetDate = new Date(tahun, bulan - 1, 15);

            // Navigate calendar
            calendar.gotoDate(targetDate);

            // Force refetch dengan delay
            setTimeout(() => {
                console.log('Force refetching events after filter');
                calendar.refetchEvents();
            }, 500);

            // Hide loading setelah proses selesai
            setTimeout(function() {
                hideLoadingOverlay();
                setCalendarLoading(false);
                setButtonLoading(submitBtn, false);
                showToast(`Jadwal bulan ${getMonthName(bulan)} ${tahun} berhasil dimuat`, 'success');
            }, 2500);
        });

        // Event listener untuk tombol ekspor CSV
        document.getElementById('exportCsvBtn').addEventListener('click', function() {
            const bulan = document.getElementById('filterBulan').value;
            const tahun = document.getElementById('filterTahun').value;

            // Validasi
            if (!bulan || !tahun) {
                showToast('Pilih bulan dan tahun terlebih dahulu', 'error');
                return;
            }

            // Tampilkan loading state
            const btn = this;
            const originalText = btn.querySelector('.btn-text').textContent;
            btn.querySelector('.btn-text').textContent = 'Mengekspor...';
            btn.disabled = true;

            // Build URL untuk ekspor
            const exportUrl = `{{ route('admin.jadwal.export.csv') }}?bulan=${bulan}&tahun=${tahun}`;

            // Buat elemen <a> sementara untuk download
            const downloadLink = document.createElement('a');
            downloadLink.href = exportUrl;
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);

            // Trigger download
            downloadLink.click();

            // Cleanup
            document.body.removeChild(downloadLink);

            // Reset button state setelah delay
            setTimeout(() => {
                btn.querySelector('.btn-text').textContent = originalText;
                btn.disabled = false;
                showToast(`File CSV jadwal ${getMonthName(bulan)} ${tahun} berhasil diunduh`, 'success');
            }, 2000);
        });

        // Helper function untuk nama bulan (tambahkan jika belum ada)
        function getMonthName(monthNum) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return months[monthNum - 1];
        }

        // Global functions
        window.showLoadingOverlay = showLoadingOverlay;
        window.hideLoadingOverlay = hideLoadingOverlay;
        window.setCalendarLoading = setCalendarLoading;
        window.showToast = showToast;
        window.hideToast = hideToast;
    });

    // Modal elements
    const modalBackdrop = document.getElementById('modalBackdrop');
    const eventModal = document.getElementById('eventModal');
    const modalDialog = document.getElementById('modalDialog');

    let currentJadwalId = null;

    function showEventModal(event) {
        currentJadwalId = event.extendedProps.jadwal_id;

        document.getElementById('modalPetugas').textContent = event.title;
        document.getElementById('modalDate').textContent = event.start.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        const shiftText = event.extendedProps.shift ? event.extendedProps.shift.charAt(0).toUpperCase() + event.extendedProps.shift.slice(1) : '-';
        document.getElementById('modalShift').textContent = shiftText;

        // PERUBAHAN: Waktu shift disesuaikan dari 15:00 ke 15:30
        let startTime, endTime;
        if (event.extendedProps.shift === 'pagi') {
            startTime = '08:00';
            endTime = '11:30';
        } else {
            startTime = '11:30';
            endTime = '15:30'; // Ubah dari 15:00 ke 15:30
        }
        document.getElementById('modalTime').textContent = `${startTime} - ${endTime}`;

        const editBtn = document.getElementById('editJadwalBtn');
        if (currentJadwalId) {
            let editUrl = `{{ url('admin/jadwal') }}/${currentJadwalId}/edit`;
            editBtn.setAttribute('href', editUrl);
            editBtn.style.display = 'inline-flex';
        } else {
            console.error('Jadwal ID not found in event extendedProps');
            editBtn.style.display = 'none';
        }

        const deleteBtn = document.getElementById('deleteJadwalBtn');
        deleteBtn.style.display = currentJadwalId ? 'inline-flex' : 'none';

        modalBackdrop.classList.remove('hidden');
        eventModal.classList.remove('hidden');
        setTimeout(() => {
            modalBackdrop.classList.remove('opacity-0');
            modalDialog.classList.remove('opacity-0', 'scale-95');
            modalDialog.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function hideEventModal() {
        modalBackdrop.classList.add('opacity-0');
        modalDialog.classList.remove('opacity-100', 'scale-100');
        modalDialog.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            modalBackdrop.classList.add('hidden');
            eventModal.classList.add('hidden');
            currentJadwalId = null;
        }, 300);
    }

    // Delete functionality dengan loading state
    document.getElementById('deleteJadwalBtn').addEventListener('click', function() {
        if (!currentJadwalId) {
            showToast('Error: Jadwal ID tidak ditemukan', 'error');
            return;
        }

        const petugasName = document.getElementById('modalPetugas').textContent;
        const tanggal = document.getElementById('modalDate').textContent;

        if (confirm(`Apakah Anda yakin ingin menghapus jadwal ${petugasName} pada ${tanggal}?`)) {
            const deleteBtn = this;
            const setButtonLoading = window.setButtonLoading || function(btn, loading, text) {
                if (loading) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50');
                    if (text) {
                        const textEl = btn.querySelector('.btn-text');
                        if (textEl) textEl.textContent = text;
                    }
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50');
                    const textEl = btn.querySelector('.btn-text');
                    if (textEl) textEl.textContent = 'Hapus';
                }
            };

            setButtonLoading(deleteBtn, true, 'Menghapus...');

            fetch(`{{ url('admin/jadwal') }}/${currentJadwalId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    setButtonLoading(deleteBtn, false);

                    if (data.success) {
                        showToast(data.message, 'success');
                        hideEventModal();

                        // Refresh calendar dengan loading
                        if (window.setCalendarLoading) window.setCalendarLoading(true);
                        setTimeout(() => {
                            if (window.calendarInstance) {
                                window.calendarInstance.refetchEvents();
                            }
                            if (window.setCalendarLoading) window.setCalendarLoading(false);
                        }, 1000);
                    } else {
                        showToast(data.message || 'Gagal menghapus jadwal', 'error');
                    }
                })
                .catch(error => {
                    setButtonLoading(deleteBtn, false);
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus jadwal', 'error');
                });
        }
    });

    // Event listener untuk menutup modal saat mengklik backdrop
    modalBackdrop.addEventListener('click', hideEventModal);
</script>
@endsection