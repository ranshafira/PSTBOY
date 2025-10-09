@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ubah Biodata</h1>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
    <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Notifikasi error umum --}}
    @if ($errors->any())
    <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        {{-- Nama Lengkap --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap"
                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                class="w-full border @error('nama_lengkap') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
            @error('nama_lengkap')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Username --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Username</label>
            <input type="text" name="username"
                value="{{ old('username', $user->username) }}"
                class="w-full border @error('username') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
            @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- NIP --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">NIP</label>
            <input type="text" name="nip"
                value="{{ old('nip', $user->nip) }}"
                class="w-full border @error('nip') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
            @error('nip')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- No HP --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No HP</label>
            <input type="text" name="no_hp"
                value="{{ old('no_hp', $user->no_hp) }}"
                class="w-full border @error('no_hp') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
            @error('no_hp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
            <input type="email" name="email"
                value="{{ old('email', $user->email) }}"
                class="w-full border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-right">
            <button type="submit"
                class="px-5 py-3 mt-4 text-sm font-medium rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection