<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Pelayanan PST</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            /* Putih solid */
        }

        .login-card {
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #4B5563;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #D1D5DB;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #F9FAFB;
        }

        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }

        .input-field:focus {
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .icon-container {
            background: linear-gradient(135deg, #FFEDD5 0%, #FDBA74 100%);
            box-shadow: 0 4px 15px rgba(251, 146, 60, 0.3);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <main class="w-full max-w-md">
        <!-- Card Forgot Password -->
        <div class="login-card rounded-2xl p-8">
            <!-- Icon -->
            <div class="icon-container flex items-center justify-center w-20 h-20 mx-auto mb-6 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                    class="bi bi-key-fill text-orange-600" viewBox="0 0 16 16">
                    <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Lupa Password</h2>
            <p class="text-center text-gray-600 mb-8">
                Masukkan email yang terdaftar untuk mendapatkan link reset password
            </p>

            <!-- Alert Messages -->
            @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Forgot Password -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Terdaftar
                    </label>
                    <input id="email"
                        class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Masukkan email Anda">
                </div>

                <!-- Buttons -->
                <div class="flex flex-col space-y-4">
                    <button type="submit" class="w-full btn-primary py-3 text-lg font-semibold">
                        {{ __('Kirim Link Reset Password') }}
                    </button>

                    <a href="{{ route('login') }}" class="w-full btn-secondary py-3 text-lg font-semibold text-center">
                        Kembali ke Login
                    </a>
                </div>
            </form>

            <!-- Additional Info -->
            <!-- <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Butuh bantuan?
                    <a href="mailto:admin@boyolalikab.bps.go.id" class="text-orange-600 hover:text-orange-500 font-medium">
                        Hubungi Administrator
                    </a>
                </p>
            </div> -->
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            &copy; {{ date('Y') }} BPS Kabupaten Boyolali
        </div>
    </main>
</body>

</html>