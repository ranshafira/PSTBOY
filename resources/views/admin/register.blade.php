<!-- resources/views/admin/register.blade.php -->
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 px-4">
        <form method="POST" action="{{ route('admin.register.store') }}" class="bg-white bg-opacity-90 rounded-xl shadow-xl p-8 max-w-md w-full border border-gray-200">
            @csrf

            <div class="mb-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-14 w-14 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7m-6-7v4m12-4v4" />
                </svg>
                <h2 class="mt-3 text-3xl font-extrabold text-gray-900 tracking-wide">Register Admin</h2>
                <p class="mt-1 text-gray-600 font-medium">Create your admin account</p>
            </div>

            <!-- Input Fields -->
            <div class="space-y-5">
                <div>
                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" class="font-semibold text-gray-700" />
                    <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" required autofocus autocomplete="name" :value="old('nama_lengkap')" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="nip" :value="__('NIP')" class="font-semibold text-gray-700" />
                    <x-text-input id="nip" name="nip" type="text" required :value="old('nip')" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('nip')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="username" :value="__('Username')" class="font-semibold text-gray-700" />
                    <x-text-input id="username" name="username" type="text" required :value="old('username')" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
                    <x-text-input id="email" name="email" type="email" required :value="old('email')" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="no_hp" :value="__('No HP')" class="font-semibold text-gray-700" />
                    <x-text-input id="no_hp" name="no_hp" type="text" required :value="old('no_hp')" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
                    <x-text-input id="password" name="password" type="password" required autocomplete="new-password" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-semibold text-gray-700" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring focus:ring-pink-300 focus:ring-opacity-50" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600" />
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-pink-600 hover:text-pink-800 font-semibold text-sm">Already registered?</a>
                <x-primary-button class="px-8 py-2 bg-pink-600 hover:bg-pink-700 rounded-lg text-white font-semibold tracking-wide">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <style>
        /* Simple fadeIn animation */
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-10px);}
            to {opacity: 1; transform: translateY(0);}
        }
        form {
            animation: fadeIn 0.5s ease-in-out forwards;
        }
    </style>
</x-guest-layout>
