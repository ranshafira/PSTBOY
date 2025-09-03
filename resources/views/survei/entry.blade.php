{{-- resources/views/survei/entry.blade.php --}}
@extends('layouts.app') {{-- Atau layout publik jika ada --}}
@section('title', 'Survei Kepuasan Pelayanan')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Survei Kepuasan Pelayanan</h1>
        <p class="text-gray-500">Masukkan kode survei yang Anda dapatkan dari petugas.</p>
    </div>

    <div class="bg-white border rounded-xl p-8">
        <form action="{{ route('survei.find') }}" method="POST">
            @csrf
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">Kode Survei</label>
                <input type="text" name="pin" id="pin"
                       class="w-full rounded-lg border-gray-300 text-center text-xl tracking-widest uppercase focus:ring-orange-500 focus:border-orange-500 transition"
                       placeholder="XXX-XXX" value="{{ old('pin') }}" required>
            </div>

            @if ($errors->any())
                <div class="mt-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="w-full px-6 py-3 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition">
                    Mulai Survei
                </button>
            </div>
        </form>
    </div>
</div>
@endsection