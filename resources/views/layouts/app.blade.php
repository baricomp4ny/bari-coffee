<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Bari Coffee'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bari.css') }}?v={{ filemtime(public_path('css/bari.css')) }}">
    @stack('styles')
</head>
<body class="@yield('body-class')">
<div class="wrap" id="top">
    <nav>
        <x-logo :href="route('home').'#top'" />
        <div class="navlinks">
            <a href="{{ route('home') }}#top" @class(['on' => request()->routeIs('home')])>Beranda</a>
            <a href="{{ route('home') }}#menu">Menu</a>
            <a href="{{ route('home') }}#tentang">Tentang</a>
            <a href="{{ route('home') }}#kontak">Kontak</a>
        </div>
        @yield('nav-action')
    </nav>

    @yield('content')

    <footer>
        <div>
            <x-logo />
            <x-beans :count="26" :inner="40" :outer="150" :seed="3" />
        </div>
        <div>
            <h4>Menu</h4>
            <ul>
                <li><a href="{{ route('home') }}#menu">Semua menu</a></li>
                <li><a href="{{ route('home') }}#andalan">Racikan andalan</a></li>
                <li><a href="{{ route('home') }}#kontak">Pesan untuk diambil</a></li>
            </ul>
        </div>
        <div>
            <h4>Bari Coffee</h4>
            <ul>
                <li><a href="{{ route('home') }}#tentang">Tentang kami</a></li>
                <li><a href="{{ route('home') }}#lokasi">Jam buka</a></li>
                <li><a href="https://wa.me/{{ $cafeWhatsapp }}" target="_blank" rel="noopener">WhatsApp</a></li>
            </ul>
        </div>
        <div>
            <h4>Alamat</h4>
            <ul>
                <li>Jl. Pajajaran No. 12</li>
                <li>Bogor, Jawa Barat</li>
                <li class="num">{{ $cafeWhatsappDisplay }}</li>
            </ul>
        </div>
        <div class="copy">
            <span>© {{ date('Y') }} Bari Coffee · Proyek Digital Kreatif (data contoh)</span>
            <span>Diseduh pelan di Bogor.</span>
        </div>
    </footer>
</div>

@yield('after')
<script src="{{ asset('js/bari.js') }}?v={{ filemtime(public_path('js/bari.js')) }}"></script>
@stack('scripts')
</body>
</html>
