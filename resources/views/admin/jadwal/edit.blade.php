@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Edit Jadwal</h2>
            <p class="mt-2 text-sm text-gray-600">Ubah petugas untuk jadwal yang dipilih</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-800">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h3 class="text-lg font-medium text-white">Detail Jadwal</h3>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('admin.jadwal.update', $jadwal->id) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Schedule Info -->
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-orange-500">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Jadwal Saat Ini</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
    <p class="text-xs text-gray-500">Shift</p>
    <div class="flex items-center">
        <span class="text-sm font-medium text-gray-900 mr-2">{{ ucfirst($jadwal->shift) }}</span>
        
        @if($jadwal->shift === 'pagi')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                08:00 - 11:30
            </span>
        @elseif($jadwal->shift === 'siang')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                11:30 - 15:30
            </span>
        @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                Jadwal Tidak Dikenali
            </span>
        @endif
    </div>
</div>

                        </div>
                    </div>
                </div>

                <!-- Current Officer Info -->
                <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Petugas Saat Ini</h4>
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-medium text-sm">
                                {{ strtoupper(substr($jadwal->user->nama_lengkap, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $jadwal->user->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $jadwal->user->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Select New Officer -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Petugas Baru
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition duration-200">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $jadwal->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->nama_lengkap }}
                                @if($user->email) - {{ $user->email }} @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Pilih petugas yang akan menggantikan jadwal ini</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>

                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 transform hover:scale-105">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Jadwal
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-yellow-800">Perhatian</h4>
                    <div class="mt-1 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Pastikan petugas yang dipilih tersedia pada tanggal dan waktu yang ditentukan</li>
                            <li>Perubahan jadwal akan langsung berlaku setelah disimpan</li>
                            <li>Notifikasi akan dikirim ke petugas yang terkait</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Add confirmation dialog for form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const currentOfficer = '{{ $jadwal->user->nama_lengkap }}';
    const select = document.getElementById('user_id');
    const selectedOfficer = select.options[select.selectedIndex].text.split(' - ')[0];
    
    if (currentOfficer !== selectedOfficer) {
        const confirmed = confirm(`Apakah Anda yakin ingin mengubah petugas dari "${currentOfficer}" menjadi "${selectedOfficer}"?`);
        if (!confirmed) {
            e.preventDefault();
        }
    }
});

// Add loading state to submit button
document.querySelector('button[type="submit"]').addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    
    // Reset button state if form submission is prevented
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    }, 3000);
});

// Animasi sukses setelah update jadwal
@if(session('success'))
    // Tambahkan elemen animasi sukses
    document.addEventListener('DOMContentLoaded', function() {
        // Buat elemen animasi
        const successAnimation = document.createElement('div');
        successAnimation.className = 'fixed inset-0 flex items-center justify-center z-50';
        successAnimation.innerHTML = `
            <div class="bg-white bg-opacity-80 fixed inset-0"></div>
            <div class="bg-white rounded-lg p-8 shadow-2xl relative z-10 transform transition-all duration-700 scale-110">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                        <svg class="h-10 w-10 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Jadwal Berhasil Diperbarui!</h3>
                    <p class="text-sm text-gray-500 mb-6">Perubahan jadwal telah disimpan</p>
                    <div class="flex justify-center">
                        <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        // Tambahkan ke body
        document.body.appendChild(successAnimation);
        
        // Animasi masuk
        setTimeout(() => {
            const animationBox = successAnimation.querySelector('.scale-110');
            animationBox.classList.add('scale-100');
            animationBox.classList.remove('scale-110');
        }, 100);
        
        // Animasi keluar otomatis setelah beberapa detik
        setTimeout(() => {
            const animationBox = successAnimation.querySelector('.scale-100');
            animationBox.classList.add('scale-110', 'opacity-0');
            successAnimation.querySelector('.bg-opacity-80').classList.add('opacity-0');
            
            setTimeout(() => {
                successAnimation.remove();
            }, 700);
        }, 3000);
    });
@endif
</script>

<style>
/* Animasi transisi */
.transition-all {
    transition-property: all;
}

.duration-700 {
    transition-duration: 700ms;
}

.scale-110 {
    transform: scale(1.1);
}

.scale-100 {
    transform: scale(1);
}

.opacity-0 {
    opacity: 0;
}
</style>
@endsection