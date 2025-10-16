<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Petugas PST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background-color: #F97316;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.2s;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #EA580C;
        }

        .input-field {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            margin-top: 0.25rem;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .input-field:focus {
            outline: none;
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .error-message {
            color: #EF4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        label {
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="bg-gray-100">

    <main class="flex flex-col items-center justify-center min-h-screen py-8 px-4">


        <!-- Form Card -->
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-md p-6">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-500 mb-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v7m-6-7v4m12-4v4" />
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-bold text-center text-gray-800">Register Petugas PST</h2>
            <p class="text-center text-gray-500 text-sm mb-5">Isi data berikut untuk membuat akun baru</p>

            <form method="POST" action="{{ route('admin.register.store') }}">
                @csrf

                <!-- Nama Lengkap (Full Width) -->
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" required autofocus value="{{ old('nama_lengkap') }}"
                        class="input-field" placeholder="Masukkan nama lengkap" />
                    @error('nama_lengkap')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIP & No HP -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                        <input id="nip" name="nip" type="text" required value="{{ old('nip') }}"
                            class="input-field" placeholder="Masukkan NIP" />
                        @error('nip')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP</label>
                        <input id="no_hp" name="no_hp" type="text" required
                            pattern="^62[8][0-9]{8,11}$"
                            title="Nomor HP harus diawali dengan 62, contoh: 6281234567890"
                            inputmode="numeric"
                            value="{{ old('no_hp') }}"
                            class="input-field" placeholder="628xxxxxxxxxx" />

                        @error('no_hp')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Username & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}"
                            class="input-field" placeholder="Pilih username" />
                        @error('username')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="input-field" placeholder="nama@email.com" />
                        @error('email')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password & Konfirmasi Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="input-field" placeholder="Min. 8 karakter" />
                        @error('password')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="input-field" placeholder="Ulangi password" />
                        @error('password_confirmation')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Daftar -->
                <div class="mt-5">
                    <button type="submit" class="btn-primary">
                        Daftar
                    </button>
                </div>

            </form>
        </div>
    </main>
    <script>
        // ambil elemen input no_hp
        const noHpInput = document.getElementById('no_hp');

        // saat form disubmit, cek validasinya
        noHpInput.addEventListener('invalid', function(event) {
            event.target.setCustomValidity('Nomor HP harus diawali dengan 62, contoh: 6281234567890');
        });

        // reset pesan kalau user ngetik lagi
        noHpInput.addEventListener('input', function(event) {
            event.target.setCustomValidity('');
        });
    </script>

</body>

</html>