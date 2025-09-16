@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Petugas PST</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data seluruh petugas yang terdaftar pada sistem.</p>
        </div>
        <a href="{{ route('admin.register.store') }}" 
           class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-orange-600 text-sm">
            <!-- Icon Tambah Petugas -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 
                         3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 
                         12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            Tambah Petugas
        </a>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <!-- Card Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <!-- Icon Judul -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 
                             4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07
                             M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766
                             l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 
                             0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 
                             0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <h2 class="font-semibold text-gray-700">Daftar Petugas</h2>
            </div>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('admin.petugas.index') }}" class="relative w-full md:w-auto flex items-center gap-2">
                <div class="relative w-full md:w-64">
                    <input 
                        type="text" 
                        name="search" 
                        id="searchInput"
                        placeholder="Cari berdasarkan nama atau NIP..." 
                        value="{{ request('search') }}" 
                        class="border border-gray-300 rounded-lg px-4 py-2 pr-10 w-full focus:outline-none focus:ring-2 focus:ring-orange-400"
                    />
                    @if(request('search'))
                    <button type="button" id="clearSearch"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-red-500 focus:outline-none">
                        &times;
                    </button>
                    @endif
                </div>
                <button type="submit" 
                        class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
                    Cari
                </button>
            </form>
        </div>

        <!-- Tabel -->
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">NIP</th>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3">Username</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">No. HP</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $user->nip }}</td>
                                <td class="px-4 py-3">{{ $user->nama_lengkap }}</td>
                                <td class="px-4 py-3">{{ $user->username }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->no_hp }}</td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus petugas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 text-red-700 px-3 py-1 rounded-lg hover:bg-red-200 text-xs font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Data petugas tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    const searchInput = document.getElementById('searchInput');

    // Auto submit jika dikosongkan
    searchInput?.addEventListener('input', function () {
        if (this.value.trim() === '') {
            this.form.submit();
        }
    });

    // Tombol "X" untuk clear input
    const clearButton = document.getElementById('clearSearch');
    clearButton?.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.form.submit();
    });
</script>
@endsection
