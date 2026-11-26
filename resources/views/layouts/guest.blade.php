<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pelaporan Sales')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-1">Pelaporan Sales</h1>
        <h2 class="text-base text-center text-gray-500 mb-6">@yield('title')</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>