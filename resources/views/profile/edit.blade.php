@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ubah Biodata</h1>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">NIP</label>
            <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div class="text-right">
            <button type="submit" class="px-5 py-3 mt-4 text-sm font-medium rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
