<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pelaporan Sales')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ open: window.innerWidth >= 1024 }">

    @include('layouts.partials.sidebar')

    {{-- Isi halaman --}}
    <div :class="open ? 'lg:ml-64' : ''" class="transition-all duration-200">
        <header class="h-14 bg-white shadow-sm flex items-center px-4 gap-3 sticky top-0 z-20">
            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100" aria-label="Buka atau tutup sidebar">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="font-semibold">@yield('title')</h1>
        </header>

        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    <x-lightbox />
</body>
</html>