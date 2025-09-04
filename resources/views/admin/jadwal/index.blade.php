@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-10 p-6">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Generate Jadwal Bulanan</h2>

        <form method="POST" action="{{ route('admin.jadwal.generate') }}" class="mb-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ request('tahun', date('Y')) }}" required>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 ease-in-out transform hover:scale-105">
                Generate Jadwal
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Jadwal Bulan Ini</h3>

        <div id="calendar" class="min-h-screen bg-white border border-gray-200 rounded-lg"></div>
    </div>
</div>

<!-- Updated Modal with Edit and Delete buttons -->
<div id="modalBackdrop" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>
<div id="eventModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div id="modalDialog" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                <!-- Delete Button -->
                <button type="button" id="deleteJadwalBtn" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
                
                <!-- Edit Button -->
                <a id="editJadwalBtn" href="#" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                
                <!-- Close Button -->
                <button type="button" onclick="hideEventModal()" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto sm:text-sm transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Message Toast -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full bg-white border border-gray-200 rounded-lg shadow-lg">
    <div class="flex p-4">
        <div id="toastIcon" class="flex-shrink-0">
            <!-- Icon will be inserted here -->
        </div>
        <div class="ml-3 w-0 flex-1">
            <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button onclick="hideToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,dayGridMonth'
        },
        height: 'auto',
        
        // Konfigurasi waktu
        slotMinTime: '07:00:00',
        slotMaxTime: '17:00:00',
        slotDuration: '01:00:00',
        slotLabelInterval: '01:00:00',
        
        // Format waktu
        slotLabelFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: false
        },
        
        // Event time format
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: false
        },
        
        // Konfigurasi hari kerja
        weekends: false,
        hiddenDays: [0, 6],
        
        events: function(info, successCallback, failureCallback) {
            fetch("{{ route('admin.jadwal.events') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Events loaded:', data);
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    failureCallback(error);
                });
        },
        
        eventDisplay: 'block',
        displayEventTime: true,
        
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            showEventModal(info.event);
        },
        
        eventDidMount: function(info) {
            const shift = info.event.extendedProps.shift;
            
            if (shift === 'pagi') {
                info.el.style.backgroundColor = '#3B82F6';
                info.el.style.borderColor = '#2563EB';
            } else {
                info.el.style.backgroundColor = '#F59E0B';
                info.el.style.borderColor = '#D97706';
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
    
    // Refresh calendar setelah generate jadwal
    @if(session('success'))
        setTimeout(function() {
            calendar.refetchEvents();
        }, 500);
    @endif

    // Store calendar reference globally for refresh
    window.calendarInstance = calendar;
});

// Modal elements
const modalBackdrop = document.getElementById('modalBackdrop');
const eventModal = document.getElementById('eventModal');
const modalDialog = document.getElementById('modalDialog');

// Store current jadwal ID
let currentJadwalId = null;

function showEventModal(event) {
    // Store jadwal ID for delete functionality
    currentJadwalId = event.extendedProps.jadwal_id;
    
    // Isi data modal
    document.getElementById('modalPetugas').textContent = event.title;
    document.getElementById('modalDate').textContent = event.start.toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    const shiftText = event.extendedProps.shift ? event.extendedProps.shift.charAt(0).toUpperCase() + event.extendedProps.shift.slice(1) : '-';
    document.getElementById('modalShift').textContent = shiftText;

    const startTime = event.start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    const endTime = event.end.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    document.getElementById('modalTime').textContent = `${startTime} - ${endTime}`;

    // Set link untuk tombol edit
    const editBtn = document.getElementById('editJadwalBtn');
    if (currentJadwalId) {
        let editUrl = `{{ url('admin/jadwal') }}/${currentJadwalId}/edit`;
        editBtn.setAttribute('href', editUrl);
        editBtn.style.display = 'inline-flex';
    } else {
        console.error('Jadwal ID not found in event extendedProps');
        editBtn.style.display = 'none';
    }

    // Enable delete button
    const deleteBtn = document.getElementById('deleteJadwalBtn');
    deleteBtn.style.display = currentJadwalId ? 'inline-flex' : 'none';

    // Tampilkan modal dengan animasi
    modalBackdrop.classList.remove('hidden');
    eventModal.classList.remove('hidden');
    setTimeout(() => {
        modalBackdrop.classList.remove('opacity-0');
        modalDialog.classList.remove('opacity-0', 'scale-95');
        modalDialog.classList.add('opacity-100', 'scale-100');
    }, 10);
}

function hideEventModal() {
    // Sembunyikan modal dengan animasi
    modalBackdrop.classList.add('opacity-0');
    modalDialog.classList.remove('opacity-100', 'scale-100');
    modalDialog.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
        modalBackdrop.classList.add('hidden');
        eventModal.classList.add('hidden');
        currentJadwalId = null; // Reset jadwal ID
    }, 300);
}

// Delete functionality
document.getElementById('deleteJadwalBtn').addEventListener('click', function() {
    if (!currentJadwalId) {
        showToast('Error: Jadwal ID tidak ditemukan', 'error');
        return;
    }

    const petugasName = document.getElementById('modalPetugas').textContent;
    const tanggal = document.getElementById('modalDate').textContent;
    
    if (confirm(`Apakah Anda yakin ingin menghapus jadwal ${petugasName} pada ${tanggal}?`)) {
        // Show loading state
        const deleteBtn = this;
        const originalText = deleteBtn.innerHTML;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menghapus...
        `;

        // Send delete request
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
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
            
            if (data.success) {
                showToast(data.message, 'success');
                hideEventModal();
                // Refresh calendar
                if (window.calendarInstance) {
                    window.calendarInstance.refetchEvents();
                }
            } else {
                showToast(data.message || 'Gagal menghapus jadwal', 'error');
            }
        })
        .catch(error => {
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus jadwal', 'error');
        });
    }
});

// Toast notification functions
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    // Set icon based on type
    if (type === 'success') {
        toastIcon.innerHTML = `
            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
        toast.classList.add('border-green-200');
        toast.classList.remove('border-red-200');
    } else {
        toastIcon.innerHTML = `
            <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
        toast.classList.add('border-red-200');
        toast.classList.remove('border-green-200');
    }
    
    toast.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    document.getElementById('toast').classList.add('hidden');
}

// Event listener untuk menutup modal saat mengklik backdrop
modalBackdrop.addEventListener('click', hideEventModal);
</script>

<style>
/* Custom CSS menggunakan konsep Tailwind */
.fc-theme-standard td, .fc-theme-standard th {
    border: 1px solid #E5E7EB; /* gray-200 */
}

.fc-theme-standard .fc-scrollgrid {
    border: 1px solid #E5E7EB; /* gray-200 */
    border-radius: 0.5rem; /* rounded-lg */
}

/* Header styling */
.fc-toolbar {
    padding: 1rem;
    background-color: #F9FAFB; /* gray-50 */
    border-bottom: 1px solid #E5E7EB; /* gray-200 */
    border-radius: 0.5rem 0.5rem 0 0;
}

.fc-toolbar-title {
    font-size: 1.5rem !important; /* text-2xl */
    font-weight: 600 !important; /* font-semibold */
    color: #111827 !important; /* gray-900 */
}

.fc-button {
    background: white !important;
    border: 1px solid #D1D5DB !important; /* gray-300 */
    color: #374151 !important; /* gray-700 */
    border-radius: 0.375rem !important; /* rounded-md */
    font-size: 0.875rem !important; /* text-sm */
    font-weight: 500 !important; /* font-medium */
    padding: 0.5rem 1rem !important; /* px-4 py-2 */
    transition: all 0.2s ease-in-out !important;
}

.fc-button:hover {
    background-color: #F3F4F6 !important; /* gray-100 */
    border-color: #9CA3AF !important; /* gray-400 */
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
}

.fc-button-active {
    background-color: #3B82F6 !important; /* blue-500 */
    border-color: #2563EB !important; /* blue-600 */
    color: white !important;
}

.fc-button-active:hover {
    background-color: #2563EB !important; /* blue-600 */
}

/* Day header styling */
.fc-col-header {
    background-color: #F9FAFB; /* gray-50 */
}

.fc-col-header-cell {
    font-weight: 600; /* font-semibold */
    color: #374151; /* gray-700 */
    padding: 0.75rem 0.5rem; /* py-3 px-2 */
}

/* Time slots styling */
.fc-timegrid-slot {
    height: 3rem !important; /* h-12 */
    border-bottom: 1px solid #F3F4F6 !important; /* gray-100 */
}

.fc-timegrid-slot:nth-child(even) {
    background-color: #FAFAFA; /* Very light gray for alternating rows */
}

.fc-timegrid-slot-label {
    font-size: 0.75rem; /* text-xs */
    color: #6B7280; /* gray-500 */
    font-weight: 500; /* font-medium */
    padding: 0.25rem 0.5rem; /* py-1 px-2 */
}

/* Today column highlight */
.fc-day-today {
    background-color: #EFF6FF !important; /* blue-50 */
}

.fc-day-today .fc-col-header-cell {
    background-color: #DBEAFE !important; /* blue-100 */
    color: #1D4ED8 !important; /* blue-700 */
}

/* Event styling enhancements */
.fc-event {
    margin: 1px !important;
    border-radius: 0.375rem !important; /* rounded-md */
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    transition: all 0.2s ease-in-out !important;
}

.fc-event:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    cursor: pointer;
}

.fc-event-title {
    font-weight: 500 !important; /* font-medium */
    padding: 2px 4px !important;
}

/* Scrollbar styling */
.fc-scroller::-webkit-scrollbar {
    width: 8px;
}

.fc-scroller::-webkit-scrollbar-track {
    background: #F3F4F6; /* gray-100 */
    border-radius: 4px;
}

.fc-scroller::-webkit-scrollbar-thumb {
    background: #D1D5DB; /* gray-300 */
    border-radius: 4px;
}

.fc-scroller::-webkit-scrollbar-thumb:hover {
    background: #9CA3AF; /* gray-400 */
}

/* Loading state */
.fc-event.fc-event-loading {
    opacity: 0.6;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
    
    .fc-button {
        padding: 0.375rem 0.75rem !important; /* px-3 py-1.5 */
        font-size: 0.75rem !important; /* text-xs */
    }
}
</style>
@endsection