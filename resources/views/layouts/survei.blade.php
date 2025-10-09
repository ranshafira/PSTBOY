<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Survei Kepuasan Pelayanan')</title>

    {{-- ✅ Import Tailwind dari CDN (biar langsung nyala desainnya kayak di app) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Optional: Alpine.js untuk interaktifitas --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Optional custom font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 min-h-screen">
    {{-- Ini isi konten tiap halaman survei --}}
    @yield('content')
</body>

</html>