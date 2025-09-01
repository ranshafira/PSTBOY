<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>@yield('title', 'Pelayanan PST')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body class="bg-gray-50 min-h-screen font-sans text-gray-800">
        {{-- Navbar --}}
        @include('layouts.navigation')

        {{-- Konten Halaman --}}
        <div class="container mx-auto px-4 py-6">
            @yield('content')
        </div>
        @include('layouts.footer')
        @stack('scripts')
    </body>
</html>
