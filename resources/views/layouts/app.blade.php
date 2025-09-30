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

<body class="font-figtree antialiased bg-gray-50 min-h-screen text-gray-800 flex flex-col">

    {{-- Navbar --}}
    <div> {{-- Dibungkus div agar tidak ikut "stretch" --}}
        @if(Auth::user()->role_id == 1)
        @include('layouts.navbar-admin')
        @elseif(Auth::user()->role_id == 2)
        @include('layouts.navigation')
        @else
        @include('layouts.navbar-kepala')
        @endif
    </div>
    <div class="container mx-auto px-4 py-6 flex-grow">
        @yield('content')
    </div>
    @include('layouts.footer')

    @stack('scripts')
</body>

</html>