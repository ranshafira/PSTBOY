<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Pelayanan PST</title>

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

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <main class="w-full max-w-md">
        <!-- Card Reset Password -->
        <div class="login-card rounded-2xl p-8">
            <!-- Icon -->
            <div class="icon-container flex items-center justify-center w-20 h-20 mx-auto mb-6 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                    class="bi bi-shield-lock-fill text-orange-600" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.5 1.5a.5.5 0 0 1-.5.585h-1a.5.5 0 0 1-.5-.585l.5-1.5A1.5 1.5 0 0 1 8 5z" />
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Reset Password</h2>
            <p class="text-center text-gray-600 mb-8">
                Buat password baru untuk akun Anda
            </p>

            <!-- Alert Messages -->
            @if($errors->any())
            <div class="alert-error">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input id="email"
                        class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan email Anda">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input id="password"
                        class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password baru">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input id="password_confirmation"
                        class="input-field w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan ulang password baru">
                </div>

                <!-- Button -->
                <div>
                    <button type="submit" class="w-full btn-primary py-3 text-lg font-semibold">
                        Reset Password
                    </button>
                </div>
            </form>

            <!-- Additional Info
            <div class="mt-6 text-center">
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