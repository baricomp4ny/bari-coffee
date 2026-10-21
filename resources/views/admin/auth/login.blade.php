<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>Login Admin · Bari Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bari.css') }}?v={{ filemtime(public_path('css/bari.css')) }}">
</head>
<body class="admin">
<main class="login-shell">
    <section class="login-art" aria-hidden="true">
        <x-beans :count="40" :inner="120" :outer="196" :seed="11" />
        <x-cup art="heart" :handle="true" coffee="#B98654" crema="#EAD2AE" foam="#6A3014" />
        <div class="login-quote">
            <div class="eyebrow">Panel admin</div>
            <p>Kelola menu, pantau pesanan, dan lihat penjualan kedai dari satu tempat.</p>
        </div>
    </section>

    <section class="login-form">
        <x-logo :href="route('login')" tagline="Panel admin" />

        <div>
            <div class="eyebrow">Khusus pengelola kedai</div>
            <h1>Masuk admin</h1>
            <p class="lead">Gunakan akun admin Bari Coffee untuk melanjutkan.</p>
        </div>

        @if (session('status'))
            <div class="alert ok" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-fields">
            @csrf

            <label class="field" for="email">Email
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')<span class="field-error" id="email-error">{{ $message }}</span>@enderror
            </label>

            <label class="field" for="password">Kata sandi
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </label>

            <div class="auth-row">
                <label class="check" for="remember_me"><input id="remember_me" type="checkbox" name="remember"> Ingat saya</label>
                @if (Route::has('password.request'))
                    <a class="link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn primary">Masuk ke panel</button>
        </form>

        <a class="link back" href="{{ route('home') }}">← Kembali ke situs Bari Coffee</a>
    </section>
</main>
</body>
</html>
