<div class="bg-white p-6 rounded-xl shadow">
  <div class="flex items-center mb-4 space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <h2 class="font-semibold text-lg text-gray-700">Daftar Buku Tamu</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full border-t border-gray-200 text-sm">
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-4 py-2 text-left">ID</th>
          <th class="px-4 py-2 text-left">Nama Tamu</th>
          <th class="px-4 py-2 text-left">Instansi</th>
          <th class="px-4 py-2 text-left">Kontak</th>
          <th class="px-4 py-2 text-left">Tujuan</th>
          <th class="px-4 py-2 text-left">Keperluan</th>
          <th class="px-4 py-2 text-left">Waktu Kunjungan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($riwayat as $tamu)
        <tr>
          <td class="px-4 py-2">{{ $tamu->id }}</td>
          <td class="px-4 py-2">{{ $tamu->nama_tamu }}</td>
          <td class="px-4 py-2">{{ $tamu->instansi_tamu }}</td>
          <td class="px-4 py-2">{{ $tamu->kontak_tamu }}</td>
          <td class="px-4 py-2">{{ $tamu->tujuan }}</td>
          <td class="px-4 py-2">{{ $tamu->keperluan }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tamu->waktu_kunjungan)->format('d-m-Y H:i') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $riwayat->withQueryString()->links() }}
  </div>
</div>
