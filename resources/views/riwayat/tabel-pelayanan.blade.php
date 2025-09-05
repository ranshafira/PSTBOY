<div class="bg-white p-6 rounded-xl shadow">
  <div class="flex items-center mb-4 space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <h2 class="font-semibold text-lg text-gray-700">Daftar Pelayanan</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full border-t border-gray-200 text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-4 py-2 text-left">No. Antrian</th>
          <th class="px-4 py-2 text-left">Klien</th>
          <th class="px-4 py-2 text-left">Kontak</th>
          <th class="px-4 py-2 text-left">Jenis Layanan</th>
          <th class="px-4 py-2 text-left">Tanggal</th>
          <th class="px-4 py-2 text-left">Durasi</th>
          <th class="px-4 py-2 text-left">Status</th>
          <th class="px-4 py-2 text-left">Token Survei</th>
          <th class="px-4 py-2 text-left">Kepuasan</th>
          <th class="px-4 py-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($riwayat as $p)
        <tr>
          <td class="px-4 py-2">{{ $p->antrian->nomor_antrian ?? '-' }}</td>
          <td class="px-4 py-2">{{ $p->nama_pelanggan }}</td>
          <td class="px-4 py-2">{{ $p->kontak_pelanggan }}</td>
          <td class="px-4 py-2">{{ $p->jenisLayanan->nama_layanan ?? '-' }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->format('d-m-Y') }}</td>
          <td class="px-4 py-2">
            @if($p->waktu_selesai_sesi)
              {{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInHours($p->waktu_selesai_sesi) }} jam
              {{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->diffInMinutes($p->waktu_selesai_sesi) % 60 }} menit
            @else
              -
            @endif
          </td>
          <td class="px-4 py-2">
            @php
                $status = $p->status_penyelesaian ?? 'Proses';
                $bgColor = 'bg-gray-100 text-gray-500';
                if(in_array($status, ['Selesai','Selesai dengan tindak lanjut'])) $bgColor='bg-green-100 text-green-700';
                elseif($status=='Tidak dapat dipenuhi') $bgColor='bg-red-100 text-red-700';
                elseif($status=='Dibatalkan klien') $bgColor='bg-purple-100 text-purple-700';
            @endphp
            <span class="px-2 py-1 text-xs rounded-full {{ $bgColor }}">{{ $status }}</span>
          </td>
          <td class="px-4 py-2">{{ $p->survey_token }}</td>
          @php
              $skor = $p->surveyKepuasan->skor_kepuasan ?? null;
              $rataRata = $skor ? round(array_sum($skor)/count($skor),1) : null;
          @endphp
          <td class="px-4 py-2">{{ $rataRata ? $rataRata.'/5' : 'Belum Mengisi' }}</td>
          <td class="px-4 py-2">
            <a href="{{ route('pelayanan.detail', $p->id) }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-300 text-black-800 text-xs font-medium mr-2">Lihat Detail</a>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $riwayat->withQueryString()->links() }}
  </div>
</div>
