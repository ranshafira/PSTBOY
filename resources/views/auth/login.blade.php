<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pelayanan PST</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background-color: #F97316;
            /* Orange */
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .btn-primary:hover {
            background-color: #EA580C;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="bg-gray-100">

    <main class="flex flex-col items-center justify-center min-h-screen px-4 py-8">

        <!-- Card Login -->
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-10">

            <!-- Icon -->
            <div class="flex items-center justify-center w-20 h-20 mx-auto mb-8 rounded-full bg-orange-100 text-orange-500 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                    class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 0c-.81 0-1.42.06-2.007.175A4.3 4.3 0 0 0 4.31 1.007C3.584 1.505 2.857 2.28 2.24 3.253a13.2 13.2 0 0 0-1.22 3.992C.876 8.324.81 9.19.808 10A7.5 7.5 0 0 0 8 15a7.5 7.5 0 0 0 7.192-5.008c-.002-.81-.068-1.668-.228-2.522a13.2 13.2 0 0 0-1.22-3.992C13.143 2.28 12.416 1.505 11.69 1.007A4.3 4.3 0 0 0 10.007.175C9.42 0.06 8.81 0 8 0m0 5a1.5 1.5 0 0 1 .494 2.955a.5.5 0 0 0 .5.5h.01a.5.5 0 0 0 .5-.5A1.5 1.5 0 0 1 8 5m2.23 4.23a.5.5 0 0 0-.707-.707l-1.5 1.5a.5.5 0 0 0 0 .707l1.5 1.5a.5.5 0 0 0 .707-.707L9.207 10z" />
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-center text-gray-500 text-sm mb-8">
                Masuk ke panel administrasi Pelayanan Statistik Terpadu
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Username -->
                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full shadow-sm" type="text" name="username"
                        :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full shadow-sm" type="password" name="password" required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" class="w-full btn-primary py-3 text-lg">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; {{ date('Y') }} BPS Kabupaten Boyolali
        </div>
    </main>
</body>

</html>