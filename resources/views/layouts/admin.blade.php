<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Admin') · Bari Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bari.css') }}?v={{ filemtime(public_path('css/bari.css')) }}">
</head>
<body class="admin">
<div class="admin-shell">
    <aside class="side">
        <x-logo :href="route('admin.dashboard')" tagline="Panel admin" />

        <nav class="side-nav" aria-label="Menu admin">
            <a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])>
                <x-icon name="grid" /> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" @class(['on' => request()->routeIs('admin.orders.*')])>
                <x-icon name="receipt" /> Pesanan
                @if ($newOrderCount)<b class="count" title="{{ $newOrderCount }} pesanan baru">{{ $newOrderCount }}</b>@endif
            </a>
            <a href="{{ route('admin.menus.index') }}" @class(['on' => request()->routeIs('admin.menus.*')])>
                <x-icon name="cup" /> Menu
            </a>
            <a href="{{ route('admin.categories.index') }}" @class(['on' => request()->routeIs('admin.categories.*')])>
                <x-icon name="tag" /> Kategori
            </a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">
                <x-icon name="external" /> Lihat situs
            </a>
        </nav>

        <div class="side-user">
            <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="who"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="icon-btn" title="Keluar" aria-label="Keluar"><x-icon name="logout" /></button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if (session('status'))
            <div class="alert ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert" role="alert">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>
<script src="{{ asset('js/bari.js') }}?v={{ filemtime(public_path('js/bari.js')) }}"></script>
@stack('scripts')
</body>
</html>
