{{-- resources/views/partials/_stepper.blade.php --}}
@php
// Definisikan 6 langkah utama dalam alur pelayanan
$steps = [
1 => 'Mulai Layanan',
2 => 'Identitas & Dokumen',
3 => 'Hasil Layanan',
4 => 'Selesai & Durasi',
5 => 'Survei Kepuasan',
6 => 'Simpan & Selesai'
];
// Asumsi langkah ke-3 adalah "Hasil Layanan", dan seterusnya. Sesuaikan jika perlu.
@endphp

<nav aria-label="Progress">
    <ol role="list" class="flex items-center">
        @foreach ($steps as $stepNumber => $stepName)
        <li class="relative {{ !$loop->last ? 'pr-8 sm:pr-20' : '' }}">
            @if ($stepNumber < $currentStep)
                {{-- Langkah Selesai --}}
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="h-0.5 w-full bg-bps-blue-600"></div>
                </div>
                <div class="relative flex h-9 w-9 items-center justify-center rounded-full bg-bps-blue-600 hover:bg-bps-blue-700">
                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">{{ $stepName }}</span>
                </div>
                @elseif ($stepNumber == $currentStep)
                {{-- Langkah Saat Ini --}}
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="h-0.5 w-full bg-slate-200"></div>
                </div>
                <div class="relative flex h-9 w-9 items-center justify-center rounded-full border-2 border-bps-blue-600 bg-white" aria-current="step">
                    <span class="h-2.5 w-2.5 rounded-full bg-bps-blue-600" aria-hidden="true"></span>
                    <span class="sr-only">{{ $stepName }}</span>
                </div>
                @else
                {{-- Langkah Berikutnya --}}
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="h-0.5 w-full bg-slate-200"></div>
                </div>
                <div class="group relative flex h-9 w-9 items-center justify-center rounded-full border-2 border-slate-300 bg-white hover:border-slate-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-slate-300" aria-hidden="true"></span>
                    <span class="sr-only">{{ $stepName }}</span>
                </div>
                @endif
        </li>
        @endforeach
    </ol>
</nav>