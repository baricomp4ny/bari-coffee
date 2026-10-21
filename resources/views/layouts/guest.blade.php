<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>Admin · Bari Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bari.css') }}?v={{ filemtime(public_path('css/bari.css')) }}">
</head>
<body>
<div class="wrap">
    <nav>
        <x-logo />
    </nav>
    <div class="center-card">
        {{ $slot }}
    </div>
</div>
</body>
</html>
