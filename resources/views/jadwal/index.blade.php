@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="flex items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ $user->nama_lengkap }}!</h2>
            <p class="text-sm text-gray-500 mt-1">Berikut adalah jadwal kerja Anda</p>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg mr-3">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Jadwal Kerja Saya</h3>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Filter Form -->
                    <form id="filterForm" class="flex items-center gap-2">
                        <select id="filterBulan" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                                @endfor
                        </select>
                        <input id="filterTahun" type="number" class="text-sm border border-gray-200 rounded-lg px-3 py-2 w-20 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent" value="{{ date('Y') }}">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg px-4 py-2 transition-colors duration-200">
                            <span class="btn-text">Filter</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="p-6">
            <div id="calendar" class="bg-white border border-gray-100 rounded-lg overflow-hidden shadow-sm">
                <!-- Calendar will be rendered here -->
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal -->
<div id="modalBackdrop" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-40 backdrop-blur-sm"></div>
<div id="eventModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div id="modalDialog" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-18 0h18" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modalTitle">Detail Jadwal</h3>
                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">Petugas:</span>
                                <span class="text-sm font-semibold text-gray-900" id="modalPetugas">-</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">Tanggal:</span>
                                <span class="text-sm text-gray-900" id="modalDate">-</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">Shift:</span>
                                <span class="text-sm text-gray-900" id="modalShift">-</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm font-medium text-gray-500">Waktu:</span>
                                <span class="text-sm text-gray-900" id="modalTime">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="showSwapModal()" class="inline-flex w-full justify-center rounded-lg border border-transparent bg-orange-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:w-auto sm:ml-3 transition-all duration-200">
                    Tukar Jadwal
                </button>
                <button type="button" onclick="hideEventModal()" class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 sm:mt-0 sm:w-auto transition-all duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tukar Jadwal Modal - ORANGE THEME -->
<div id="swapModalBackdrop" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-40 backdrop-blur-sm"></div>
<div id="swapModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div id="swapModalDialog" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">Tukar Jadwal</h3>
                        <div class="mt-6 space-y-4">
                            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                                <h4 class="text-sm font-medium text-orange-800">Jadwal Anda</h4>
                                <div class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-orange-600">Tanggal:</span>
                                        <span class="text-sm font-medium text-orange-800" id="swapMyDate">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-orange-600">Shift:</span>
                                        <span class="text-sm font-medium text-orange-800" id="swapMyShift">-</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Petugas untuk Ditukar</label>
                                <select id="petugasSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">Pilih petugas...</option>
                                </select>
                                <div id="petugasLoading" class="hidden text-sm text-orange-600 mt-1">
                                    <div class="flex items-center">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-orange-600 mr-2"></div>
                                        Memuat daftar petugas...
                                    </div>
                                </div>
                                <div id="petugasError" class="hidden text-sm text-red-600 mt-1"></div>
                            </div>

                            <div id="petugasJadwalInfo" class="hidden bg-orange-50 rounded-lg p-4 border border-orange-200">
                                <h4 class="text-sm font-medium text-orange-800">Jadwal Petugas Terpilih</h4>
                                <div class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-orange-600">Tanggal:</span>
                                        <span class="text-sm font-medium text-orange-800" id="swapTheirDate">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-orange-600">Shift:</span>
                                        <span class="text-sm font-medium text-orange-800" id="swapTheirShift">-</span>
                                    </div>
                                </div>
                            </div>

                            <div id="swapWarning" class="hidden bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-amber-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <p class="text-sm text-amber-700" id="swapWarningText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitSwapRequest()" class="inline-flex w-full justify-center rounded-lg border border-transparent bg-orange-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:w-auto sm:ml-3 transition-all duration-200">
                    <span class="btn-text">Ajukan Tukar Jadwal</span>
                </button>
                <button type="button" onclick="hideSwapModal()" class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 sm:mt-0 sm:w-auto transition-all duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Toast -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="flex p-4 items-center">
        <div id="toastIcon" class="flex-shrink-0 rounded-full p-2">
            <!-- Icon will be inserted here -->
        </div>
        <div class="ml-3 flex-1">
            <p id="toastMessage" class="text-sm font-medium text-gray-900 whitespace-normal break-words"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button onclick="hideToast()" class="bg-transparent rounded-full p-1 inline-flex text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
    /* Modern Loading Animations */
    @keyframes modernSpin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
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

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
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

    /* Enhanced Calendar Styles - ORANGE THEME */
    .fc-theme-standard .fc-scrollgrid {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .fc-col-header-cell {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
        color: white !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border: none !important;
    }

    .fc-col-header-cell-cushion {
        color: white !important;
        padding: 12px 8px;
    }

    .fc-daygrid-day-number {
        font-weight: 600;
        color: #f97316;
        padding: 4px;
    }

    .fc-daygrid-day.fc-day-today {
        background-color: #fed7aa !important;
    }

    .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background-color: #f97316;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .fc-event {
        border-radius: 6px !important;
        border: none !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.2s ease !important;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 2px 6px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15) !important;
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%) !important;
    }

    .fc-button-primary {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #374151 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        padding: 0.625rem 1rem !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin: 0 2px !important;
    }

    .fc-button-primary:hover {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        color: #111827 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 2px 6px rgba(0, 0, 0, 0.06) !important;
        transform: translateY(-2px) !important;
    }

    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        border: 1px solid rgba(249, 115, 22, 0.3) !important;
        color: #ea580c !important;
        transform: translateY(0) !important;
    }

    .fc-button-primary:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2) !important;
    }

    /* Calendar Loading */
    .calendar-loading-modern::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(249, 115, 22, 0.2);
        border-top-color: #f97316;
        z-index: 21;
        animation: modernSpin 1s linear infinite;
    }

    /* Swap Modal Orange Theme */
    .swap-orange-theme {
        border-left: 4px solid #f97316;
        background: linear-gradient(135deg, #fef3e2 0%, #fef7ed 100%);
    }

    .swap-orange-theme #toastIcon {
        background-color: #f97316;
    }

    /* Button Loading dengan Orange */
    .btn-loading::before {
        border-top: 2px solid #f97316;
    }

    /* Styling untuk toolbar */
    .fc-toolbar {
        margin-bottom: 1.5rem !important;
        padding: 0.5rem !important;
        background: rgba(255, 255, 255, 0.5) !important;
        backdrop-filter: blur(10px) !important;
        border-radius: 16px !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .fc-toolbar-chunk {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }

    /* Toast Improvements */
    .toast-success {
        border-left: 4px solid #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    }

    .toast-success #toastIcon {
        background-color: #10b981;
    }

    .toast-error {
        border-left: 4px solid #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #fef2f2 100%);
    }

    .toast-error #toastIcon {
        background-color: #ef4444;
    }

    /* Swap Modal Styles */
    .toast-info {
        border-left: 4px solid #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
    }

    .toast-info #toastIcon {
        background-color: #3b82f6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }

        .fc-button {
            padding: 0.5rem !important;
            font-size: 0.75rem !important;
        }

        .fc-toolbar-title {
            font-size: 1.125rem !important;
            text-align: center;
        }
    }
</style>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
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

        // Enhanced toast notification - ORANGE THEME
        function showToast(message, type = 'success', duration = 5000) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            if (!toast || !toastMessage || !toastIcon) return;

            toastMessage.textContent = message.replace(/\n/g, ' ');

            // Reset classes
            toast.className = 'fixed top-4 right-4 z-50 max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 transform transition-all duration-300 ease-in-out';

            if (type === 'success') {
                toastIcon.innerHTML = `
        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`;
                toast.classList.add('swap-orange-theme');
                toastIcon.className = 'flex-shrink-0 rounded-full p-2 bg-orange-500';
            } else if (type === 'error') {
                toastIcon.innerHTML = `
        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`;
                toast.classList.add('toast-error');
                toastIcon.className = 'flex-shrink-0 rounded-full p-2 bg-red-500';
            } else if (type === 'info') {
                toastIcon.innerHTML = `
        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`;
                toast.classList.add('swap-orange-theme');
                toastIcon.className = 'flex-shrink-0 rounded-full p-2 bg-orange-500';
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 100);

            setTimeout(() => {
                hideToast();
            }, duration);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            if (!toast) return;

            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-2', 'opacity-0');

            setTimeout(() => {
                toast.classList.add('hidden');
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

            slotMinTime: '07:00:00',
            slotMaxTime: '16:00:00',
            slotDuration: '00:30:00',
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
                setCalendarLoading(true);

                const startDateStr = formatDate(startDate);
                const endDateStr = formatDate(endDate);

                const eventsUrl = `{{ route('petugas.jadwal.events') }}?start=${startDateStr}&end=${endDateStr}&month=${viewMonth}&year=${viewYear}`;
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

                        setTimeout(() => {
                            setCalendarLoading(false);
                        }, 800);
                    })
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);
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

                const filterBulan = document.getElementById('filterBulan');
                const filterTahun = document.getElementById('filterTahun');

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
                setCalendarLoading(false);
                setButtonLoading(submitBtn, false);
                showToast(`Jadwal bulan ${getMonthName(bulan)} ${tahun} berhasil dimuat`, 'success');
            }, 1500);
        });

        // Global functions
        window.setCalendarLoading = setCalendarLoading;
        window.showToast = showToast;
        window.hideToast = hideToast;
        window.formatDate = formatDate;
        window.setButtonLoading = setButtonLoading;
    });

    // Modal elements
    const modalBackdrop = document.getElementById('modalBackdrop');
    const eventModal = document.getElementById('eventModal');
    const modalDialog = document.getElementById('modalDialog');

    // Modal elements for swap
    const swapModalBackdrop = document.getElementById('swapModalBackdrop');
    const swapModal = document.getElementById('swapModal');
    const swapModalDialog = document.getElementById('swapModalDialog');

    let currentJadwalId = null;
    let currentEventData = null;

    function showEventModal(event) {
        currentJadwalId = event.extendedProps.jadwal_id;
        currentEventData = {
            id: event.extendedProps.jadwal_id,
            tanggal: event.start,
            shift: event.extendedProps.shift,
            petugas: event.title
        };

        // HILANGKAN (PAGI/SIANG) DARI TITLE
        const petugasName = event.title.replace(/ \(.*\)$/, '');
        document.getElementById('modalPetugas').textContent = petugasName;

        document.getElementById('modalDate').textContent = event.start.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Tetap tampilkan shift di modal detail (opsional)
        const shiftText = event.extendedProps.shift ? event.extendedProps.shift.charAt(0).toUpperCase() + event.extendedProps.shift.slice(1) : '-';
        document.getElementById('modalShift').textContent = shiftText;

        let startTime, endTime;
        if (event.extendedProps.shift === 'pagi') {
            startTime = '08:00';
            endTime = '11:30';
        } else {
            startTime = '11:30';
            endTime = '15:30';
        }
        document.getElementById('modalTime').textContent = `${startTime} - ${endTime}`;

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
            currentEventData = null;
        }, 300);
    }

    function showSwapModal() {
        if (!currentEventData) {
            showToast('Data jadwal tidak valid', 'error');
            return;
        }

        // Set data jadwal sendiri
        document.getElementById('swapMyDate').textContent = currentEventData.tanggal.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('swapMyShift').textContent = currentEventData.shift.charAt(0).toUpperCase() + currentEventData.shift.slice(1);

        // Load daftar petugas
        loadAvailablePetugas();

        swapModalBackdrop.classList.remove('hidden');
        swapModal.classList.remove('hidden');
        setTimeout(() => {
            swapModalBackdrop.classList.remove('opacity-0');
            swapModalDialog.classList.remove('opacity-0', 'scale-95');
            swapModalDialog.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function hideSwapModal() {
        swapModalBackdrop.classList.add('opacity-0');
        swapModalDialog.classList.remove('opacity-100', 'scale-100');
        swapModalDialog.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            swapModalBackdrop.classList.add('hidden');
            swapModal.classList.add('hidden');

            // Reset form
            document.getElementById('petugasSelect').value = '';
            document.getElementById('petugasJadwalInfo').classList.add('hidden');
            document.getElementById('swapWarning').classList.add('hidden');
        }, 300);
    }

    function loadAvailablePetugas() {
        const selectEl = document.getElementById('petugasSelect');
        const loadingEl = document.getElementById('petugasLoading');
        const errorEl = document.getElementById('petugasError');

        // Reset state
        selectEl.innerHTML = '<option value="">Memuat petugas...</option>';
        selectEl.disabled = true;
        loadingEl.classList.remove('hidden');
        errorEl.classList.add('hidden');
        errorEl.textContent = '';

        try {
            // Validasi data sebelum request
            if (!currentEventData || !currentJadwalId) {
                throw new Error('Data jadwal tidak valid');
            }

            const tanggal = formatDate(currentEventData.tanggal);
            if (!tanggal) {
                throw new Error('Format tanggal tidak valid');
            }

            console.log('Loading petugas for:', {
                tanggal: tanggal,
                shift: currentEventData.shift,
                exclude_jadwal: currentJadwalId
            });

            // Build URL
            const baseUrl = '{{ route("petugas.jadwal.available-petugas") }}';
            const params = new URLSearchParams({
                tanggal: tanggal,
                shift: currentEventData.shift,
                exclude_jadwal: currentJadwalId
            });

            const url = `${baseUrl}?${params}`;

            console.log('Fetching from URL:', url);

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response received:', response.status);

                    if (response.status === 500) {
                        throw new Error('Server mengalami masalah. Silakan coba lagi.');
                    }

                    if (!response.ok) {
                        return response.text().then(text => {
                            let errorMsg = `Error ${response.status}`;
                            try {
                                const jsonData = JSON.parse(text);
                                errorMsg = jsonData.error || jsonData.message || errorMsg;
                            } catch (e) {
                                errorMsg = text || errorMsg;
                            }
                            throw new Error(errorMsg);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);

                    loadingEl.classList.add('hidden');
                    selectEl.disabled = false;

                    // Handle different response formats
                    if (data && data.error) {
                        throw new Error(data.error);
                    }

                    if (!data || !Array.isArray(data)) {
                        throw new Error('Format response tidak valid');
                    }

                    selectEl.innerHTML = '<option value="">Pilih petugas...</option>';

                    if (data.length === 0) {
                        selectEl.innerHTML = '<option value="">Tidak ada petugas tersedia</option>';
                        showToast('Tidak ada petugas PST lain yang tersedia untuk shift ini', 'info', 3000);
                    } else {
                        console.log('Processing', data.length, 'petugas');

                        data.forEach((petugas, index) => {
                            console.log('Petugas', index, ':', petugas);

                            // Validasi data petugas lebih lengkap
                            if (petugas.id && petugas.nama_lengkap) {
                                const option = document.createElement('option');
                                option.value = petugas.id;

                                // Tampilkan status yang berbeda dengan icon
                                let statusText = '';
                                if (petugas.status === 'sudah_ada_jadwal') {
                                    statusText = ' ⚡'; // Petugas sudah punya jadwal
                                } else if (petugas.status === 'tersedia') {
                                    statusText = ' ✅'; // Petugas tersedia
                                }

                                // Format tampilan
                                let displayText = `${petugas.nama_lengkap}${statusText}`;
                                if (petugas.email) {
                                    displayText += ` (${petugas.email})`;
                                }

                                option.textContent = displayText;
                                option.setAttribute('data-jadwal-id', petugas.jadwal_id || '');
                                option.setAttribute('data-shift', petugas.shift || '');
                                option.setAttribute('data-tanggal', petugas.tanggal || '');
                                option.setAttribute('data-status', petugas.status || '');
                                selectEl.appendChild(option);

                                console.log('Added option:', displayText);
                            } else {
                                console.warn('Invalid petugas data skipped:', petugas);
                            }
                        });

                        console.log('Total options added:', selectEl.options.length - 1); // Exclude first option

                        if (selectEl.options.length === 1) { // Only "Pilih petugas..."
                            selectEl.innerHTML = '<option value="">Data petugas tidak valid</option>';
                            showToast('Data petugas tidak valid', 'error');
                        } else {
                            showToast(`Ditemukan ${selectEl.options.length - 1} petugas tersedia`, 'success', 2000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);

                    loadingEl.classList.add('hidden');
                    selectEl.disabled = false;

                    let userMessage = 'Gagal memuat daftar petugas';

                    if (error.message.includes('Server mengalami masalah')) {
                        userMessage = error.message;
                    } else if (error.message.includes('Network')) {
                        userMessage = 'Koneksi internet bermasalah. Periksa koneksi Anda.';
                    } else if (error.message.includes('Format response')) {
                        userMessage = 'Server mengembalikan data tidak valid.';
                    } else {
                        userMessage = error.message || 'Terjadi kesalahan tidak terduga';
                    }

                    selectEl.innerHTML = `<option value="">${userMessage}</option>`;
                    errorEl.textContent = userMessage;
                    errorEl.classList.remove('hidden');

                    showToast(userMessage, 'error', 5000);
                });

        } catch (error) {
            console.error('Initialization error:', error);
            loadingEl.classList.add('hidden');
            selectEl.innerHTML = `<option value="">${error.message}</option>`;
            errorEl.textContent = error.message;
            errorEl.classList.remove('hidden');
            showToast(error.message, 'error');
        }
    }

    function showPetugasJadwalInfo(option) {
        const jadwalId = option.getAttribute('data-jadwal-id');
        const shift = option.getAttribute('data-shift');
        const tanggal = option.getAttribute('data-tanggal');

        // Format tanggal untuk display
        const dateObj = new Date(tanggal);
        const formattedDate = dateObj.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('swapTheirDate').textContent = formattedDate;
        document.getElementById('swapTheirShift').textContent = shift.charAt(0).toUpperCase() + shift.slice(1);
        document.getElementById('petugasJadwalInfo').classList.remove('hidden');

        // Tampilkan warning jika shift berbeda
        const warningEl = document.getElementById('swapWarning');
        const warningTextEl = document.getElementById('swapWarningText');

        if (currentEventData.shift !== shift) {
            warningTextEl.textContent = `Perhatian: Anda bertukar dari shift ${currentEventData.shift} ke shift ${shift}`;
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }
    }

    function submitSwapRequest() {
        const selectEl = document.getElementById('petugasSelect');
        const selectedOption = selectEl.options[selectEl.selectedIndex];

        if (!selectedOption.value) {
            showToast('Pilih petugas terlebih dahulu', 'error');
            return;
        }

        const targetPetugasId = selectedOption.value;
        const targetPetugasName = selectedOption.textContent.split(' (')[0];
        const status = selectedOption.getAttribute('data-status');

        // Konfirmasi berdasarkan status
        let confirmMessage = '';
        if (status === 'sudah_ada_jadwal') {
            confirmMessage = `Anda akan bertukar jadwal dengan ${targetPetugasName}. Apakah Anda yakin?`;
        } else {
            confirmMessage = `Anda akan mengalihkan jadwal ini ke ${targetPetugasName}. Apakah Anda yakin?`;
        }

        if (!confirm(confirmMessage)) {
            return;
        }

        const submitBtn = document.querySelector('#swapModal button[onclick="submitSwapRequest()"]');
        setButtonLoading(submitBtn, true, 'Memproses...');

        // Hanya kirim jadwal_asal_id dan petugas_tujuan_id
        fetch('{{ route("petugas.jadwal.swap-request") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    jadwal_asal_id: currentJadwalId,
                    petugas_tujuan_id: targetPetugasId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    hideSwapModal();
                    hideEventModal();

                    // Refresh calendar untuk menampilkan perubahan
                    if (window.calendarInstance) {
                        setTimeout(() => {
                            window.calendarInstance.refetchEvents();
                            showSuccessAnimation(data.message);
                        }, 1000);
                    }
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error submitting swap request:', error);
                showToast(error.message || 'Gagal memproses permintaan tukar jadwal', 'error');
            })
            .finally(() => {
                setButtonLoading(submitBtn, false);
            });
    }

    // Event listener untuk menutup modal detail jadwal saat mengklik backdrop
    modalBackdrop.addEventListener('click', hideEventModal);

    // Event listener untuk menutup modal tukar jadwal saat mengklik backdrop
    swapModalBackdrop.addEventListener('click', hideSwapModal);

    // Add event listener untuk select petugas
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'petugasSelect') {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption.value) {
                showPetugasJadwalInfo(selectedOption);
            } else {
                document.getElementById('petugasJadwalInfo').classList.add('hidden');
                document.getElementById('swapWarning').classList.add('hidden');
            }
        }
    });
</script>
@endsection