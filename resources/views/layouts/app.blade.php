<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KADESTIM — Platform Digital Kader Kesehatan Jawa Timur.">
    <title>@yield('title', 'KADESTIM') | Kader Kesehatan Jawa Timur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kadestim.css') }}">
</head>
@php($musicEnabled = request()->routeIs('home', 'events', 'gallery') && file_exists(public_path('audio/lagu-duta-kesehatan-jatim.mp3')))
<body data-music-enabled="{{ $musicEnabled ? 'true' : 'false' }}">
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}"><img class="brand-logo" src="{{ asset('images/kadestim-logo.png') }}" alt="Logo resmi KADESTIM"><span class="brand-name">KADESTIM<small>Kader Kesehatan Jawa Timur</small></span></a>
        <button class="menu-toggle" aria-label="Buka navigasi" aria-expanded="false">☰</button>
        <nav class="main-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('education') }}">Edukasi</a>
            <a href="{{ route('events') }}">Event Duta</a>
            <a href="{{ route('gallery') }}">Galeri</a>
            <a href="{{ route('learning') }}">Pembelajaran</a>
            <a href="{{ route('about') }}">Tentang</a>
        </nav>
        <a class="nav-cta" href="{{ route('admin.login') }}">Admin</a>
    </header>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div><a class="brand brand-light" href="{{ route('home') }}"><img class="brand-logo" src="{{ asset('images/kadestim-logo.png') }}" alt="Logo resmi KADESTIM"><span class="brand-name">KADESTIM<small>Kader Kesehatan Jawa Timur</small></span></a><p>Ruang informasi, edukasi, dan pembelajaran kesehatan yang terbuka untuk masyarakat.</p></div>
        <div class="footer-note">© {{ date('Y') }} KADESTIM. Dikembangkan untuk edukasi kesehatan.</div>
    </footer>
    @if($musicEnabled)
    <div class="site-audio" aria-label="Pemutar Lagu Duta Kesehatan Jawa Timur">
        <audio id="kadestim-theme" src="{{ asset('audio/lagu-duta-kesehatan-jatim.mp3') }}" autoplay loop preload="auto"></audio>
        <button type="button" class="audio-toggle" data-audio-toggle aria-label="Putar atau jeda lagu">♫ <span>Memuat lagu...</span></button>
        <button type="button" class="audio-mute" data-audio-mute aria-label="Bisukan atau aktifkan suara">Suara</button>
    </div>
    @endif
    <script src="{{ asset('js/kadestim.js') }}" defer></script>
</body>
</html>
