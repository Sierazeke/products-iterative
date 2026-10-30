@props(['title' => 'Products'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name') }}</title>
</head>
<body>
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    {{ $slot }}
</body>
</html>