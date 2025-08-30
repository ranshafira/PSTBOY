@extends('layouts.app')

@section('title', 'Edit Password')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ubah Password</h1>

    <form method="POST" action="{{ route('profile.updatePassword') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Password Sekarang</label>
            <input type="password" name="current_password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Password Baru</label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400 transition">
        </div>

        <div class="text-right">
            <button type="submit" class="px-5 py-3 mt-4 text-sm font-medium rounded-full bg-orange-100 text-orange-600 hover:bg-orange-200 transition">
                Ganti Password
            </button>
        </div>

    </form>
</div>
@endsection
