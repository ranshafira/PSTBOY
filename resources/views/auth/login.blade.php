<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pelayanan PST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Kita tambahkan beberapa style kustom di sini agar lebih mirip */
        body {
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary {
            background-color: #F97316; /* Orange */
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #EA580C;
        }
    </style>
</head>
<body class="bg-gray-100">

    <main class="flex flex-col items-center justify-center py-12 px-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 text-orange-500 mb-4">
                 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0c-.81 0-1.42.06-2.007.175A4.3 4.3 0 0 0 4.31 1.007C3.584 1.505 2.857 2.28 2.24 3.253a13.2 13.2 0 0 0-1.22 3.992C.876 8.324.81 9.19.808 10A7.5 7.5 0 0 0 8 15a7.5 7.5 0 0 0 7.192-5.008c-.002-.81-.068-1.668-.228-2.522a13.2 13.2 0 0 0-1.22-3.992C13.143 2.28 12.416 1.505 11.69 1.007A4.3 4.3 0 0 0 10.007.175C9.42 0.06 8.81 0 8 0m0 5a1.5 1.5 0 0 1 .494 2.955a.5.5 0 0 0 .5.5h.01a.5.5 0 0 0 .5-.5A1.5 1.5 0 0 1 8 5m2.23 4.23a.5.5 0 0 0-.707-.707l-1.5 1.5a.5.5 0 0 0 0 .707l1.5 1.5a.5.5 0 0 0 .707-.707L9.207 10z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Admin PST</h1>
            <p class="text-gray-500 mt-2">Masuk ke panel administrasi</p>
        </div>

        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800">Masuk Admin</h2>
            <p class="text-center text-gray-500 mb-6">Gunakan kredensial Anda untuk mengakses sistem</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full btn-primary">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>