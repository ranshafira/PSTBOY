@extends('layouts.app')
@section('title', 'Masukkan Token Survei Kebutuhan Dasar (SKD)')

@section('content')
<div class="antialiased bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full mx-auto">

        @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 text-sm rounded-lg p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if(session('info'))
        <div class="bg-blue-100 border border-blue-300 text-blue-800 text-sm rounded-lg p-4 mb-4" role="alert">
            {{ session('info') }}
        </div>
        @endif

        <form action="{{ route('survei.skd.find') }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            @csrf
            <h2 class="text-2xl font-bold text-gray-900 text-center">Survei Kebutuhan Dasar (SKD)</h2>
            <p class="text-gray-600 text-center mt-2">Masukkan token yang Anda dapatkan setelah menyelesaikan pelayanan.</p>

            <div class="mt-8">
                <label for="token" class="block text-sm font-medium text-gray-700">Token Pelayanan</label>
                <input type="text" name="token" id="token" required autofocus
                    class="mt-1 block w-full text-center text-2xl font-mono tracking-widest uppercase rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500"
                    placeholder="XXX-XXX">
                @error('token')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full py-3 px-4 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                    Mulai Survei
                </button>
            </div>
        </form>
    </div>
</div>
@endsection