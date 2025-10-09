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
          <th class="px-4 py-2 text-center">No. Antrian</th>
          <th class="px-4 py-2 text-center">Pengunjung</th>
          <th class="px-4 py-2 text-center">Kontak</th>
          <th class="px-4 py-2 text-center">Media Layanan</th>
          <th class="px-4 py-2 text-center">Jenis Layanan</th>
          <th class="px-4 py-2 text-center">Petugas</th>
          <th class="px-4 py-2 text-center">Tanggal</th>
          <th class="px-4 py-2 text-center">Status</th>
          @if(auth()->user()->role_id != 3)
              <th class="px-4 py-2 text-center">Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($riwayat as $p)
        <tr>
          <td class="px-4 py-2 text-center">{{ $p->antrian->nomor_antrian ?? '-' }}</td>
          <td class="px-4 py-2 text-center">{{ $p->nama_pengunjung }}</td>
          <td class="px-4 py-2 text-center">
            {{ $p->no_hp ?? '-' }} <br>
            {{ $p->email ?? '-' }}
          </td>
          <td class="px-4 py-2 text-center">@if ($p->media_layanan)
              {{ $p->media_layanan === 'whatsapp' ? 'WhatsApp' : ucfirst($p->media_layanan) }}
            @else
              -
            @endif
          </td>
          <td class="px-4 py-2 text-center">{{ $p->jenisLayanan->nama_layanan ?? '-' }}</td>
          <td class="px-4 py-2 text-center">{{ $p->petugas->nama_lengkap ?? '-' }}</td>
          <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($p->waktu_mulai_sesi)->format('d-m-Y') }}</td>
          <td class="px-4 py-2 text-center">
            @php
                $status = $p->status_penyelesaian ?? 'Proses';
                $bgColor = 'bg-gray-100 text-gray-500';
                if(in_array($status, ['Selesai','Selesai dengan tindak lanjut'])) $bgColor='bg-green-100 text-green-700';
                elseif($status=='Tidak dapat dipenuhi') $bgColor='bg-red-100 text-red-700';
                elseif($status=='Dibatalkan pengunjung') $bgColor='bg-purple-100 text-purple-700';
            @endphp
            <span class="px-2 py-1 text-xs rounded-full {{ $bgColor }}">{{ $status }}</span>
          </td>
          @if (auth()->user()->role_id != 3)
            <td class="px-4 py-2 text-center">
                <a href="{{ route('pelayanan.detail', $p->id) }}"
                  class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-300 text-black text-xs font-medium mr-2">
                    Lihat Detail
                </a>
            </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $riwayat->withQueryString()->links() }}
  </div>
</div>
