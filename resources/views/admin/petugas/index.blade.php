@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Petugas PST</h1>

    <!-- Tombol Tambah Petugas -->
    <div class="mb-4">
        <a href="{{ route('admin.register.store') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Petugas
        </a>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Nama Lengkap</th>
                <th class="border border-gray-300 px-4 py-2">Username</th>
                <th class="border border-gray-300 px-4 py-2">Email</th>
                <th class="border border-gray-300 px-4 py-2">No. HP</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->nama_lengkap }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->username }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->no_hp }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus petugas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
