<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Products') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white shadow">
        <div class="max-w-3xl mx-auto p-4">
            <a href="{{ route('products.index') }}" class="font-bold">Products</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto p-6">
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>