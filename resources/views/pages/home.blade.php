@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow"><span class="eyebrow-dot"></span> PLATFORM DIGITAL KESEHATAN</span>
        <h1>Pengetahuan sehat,<br><em>aksi nyata</em> untuk semua.</h1>
        <p>KADESTIM menjadi ruang digital untuk mengenal kegiatan Duta Kesehatan Jawa Timur, menemukan informasi kesehatan, dan belajar melalui pengalaman interaktif.</p>
        <div class="hero-actions"><a class="button button-primary" href="{{ route('education') }}">Jelajahi Edukasi <span>↗</span></a><a class="button button-outline" href="{{ route('about') }}">Kenali KADESTIM</a></div>
        <div class="hero-meta"><span>01 — INFORMASI</span><span>02 — EDUKASI</span><span>03 — PEMBELAJARAN</span></div>
    </div>
    <div class="hero-art">
        <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
        <div class="art-card art-card-main"><span class="art-label">RUANG BELAJAR</span><div class="art-symbol">✳</div><strong>Mulai dari<br>hal sederhana.</strong><small>Kenali • Pahami • Terapkan</small></div>
        <div class="art-chip chip-top">✦ Informasi terpercaya</div><div class="art-chip chip-bottom">♡ Sehat bersama</div>
        <div class="art-side-note">JAWA TIMUR · INDONESIA</div>
    </div>
</section>

<section class="public-stats" aria-label="Ringkasan konten KADESTIM">
    <div><strong>{{ $counts['article'] }}</strong><span>Materi edukasi</span></div>
    <div><strong>{{ $counts['event'] }}</strong><span>Informasi event</span></div>
    <div><strong>{{ $counts['gallery'] }}</strong><span>Dokumentasi galeri</span></div>
    <div><strong>{{ $counts['learning'] }}</strong><span>Modul pembelajaran</span></div>
</section>

<section class="section section-soft">
    <div class="section-heading"><div><span class="eyebrow">JELAJAHI KADESTIM</span><h2>Satu ruang, banyak hal<br>untuk dipelajari.</h2></div><p>Temukan informasi dan pengalaman belajar kesehatan dalam satu platform yang dapat diakses secara terbuka.</p></div>
    <div class="feature-grid">
        <a class="feature-card" href="{{ route('education') }}"><span class="feature-number">01</span><span class="feature-icon">✳</span><h3>Edukasi Kesehatan</h3><p>Artikel, informasi, dan materi kesehatan yang disajikan secara ringkas dan mudah dipahami.</p><span class="feature-link">Jelajahi edukasi ↗</span></a>
        <a class="feature-card" href="{{ route('events') }}"><span class="feature-number">02</span><span class="feature-icon">⌁</span><h3>Event Duta</h3><p>Ikuti agenda, kegiatan, dan kontribusi Duta Kesehatan Jawa Timur.</p><span class="feature-link">Lihat kegiatan ↗</span></a>
        <a class="feature-card" href="{{ route('gallery') }}"><span class="feature-number">03</span><span class="feature-icon">▧</span><h3>Galeri</h3><p>Dokumentasi kegiatan, kampanye, dan cerita kolaborasi dalam satu galeri.</p><span class="feature-link">Buka galeri ↗</span></a>
        <a class="feature-card feature-card-accent" href="{{ route('learning') }}"><span class="feature-number">04</span><span class="feature-icon">◎</span><h3>Media Pembelajaran</h3><p>Jelajahi kasus kesehatan sehari-hari dan pahami pilihan tindakan yang tepat.</p><span class="feature-link">Mulai belajar ↗</span></a>
    </div>
</section>

@if($latestArticles->isNotEmpty())
<section class="section home-latest">
    <div class="section-heading"><div><span class="eyebrow">BACAAN TERBARU</span><h2>Informasi kesehatan<br><em>untuk keseharian.</em></h2></div><a class="text-link" href="{{ route('education') }}">Lihat semua edukasi ↗</a></div>
    <div class="public-content-grid">
        @foreach($latestArticles as $item)
        <a class="public-content-card" href="{{ route('content.show', ['type' => 'article', 'slug' => $item->slug]) }}">
            @if($item->cover_image)<img src="{{ str_starts_with($item->cover_image, 'http') ? $item->cover_image : asset('storage/'.$item->cover_image) }}" alt="" loading="lazy">@else<div class="content-placeholder">KADESTIM</div>@endif
            <div class="public-content-copy"><span class="eyebrow">{{ $item->published_at?->format('d M Y') }}</span><h2>{{ $item->title }}</h2><p>{{ $item->excerpt }}</p><span class="feature-link">Baca selengkapnya ↗</span></div>
        </a>
        @endforeach
    </div>
</section>
@endif

@if($latestEvents->isNotEmpty() || $latestLearning->isNotEmpty())
<section class="section section-soft home-discover">
    <div class="section-heading"><div><span class="eyebrow">AKTIVITAS & PENGALAMAN</span><h2>Terhubung, berpartisipasi,<br>dan terus <em>belajar.</em></h2></div></div>
    <div class="home-discover-grid">
        <div class="home-discover-column"><div class="home-mini-heading"><h3>Event Duta</h3><a class="text-link" href="{{ route('events') }}">Semua event ↗</a></div>
            @forelse($latestEvents as $item)
            <a class="home-list-item" href="{{ route('content.show', ['type' => 'event', 'slug' => $item->slug]) }}"><span class="home-list-date">{{ $item->published_at?->format('d M') }}</span><span><strong>{{ $item->title }}</strong><small>{{ $item->event_location ?: 'Informasi kegiatan Duta Kesehatan' }}</small></span><b>↗</b></a>
            @empty<p class="home-muted">Informasi event akan ditampilkan di sini setelah dipublikasikan.</p>@endforelse
        </div>
        <div class="home-discover-column"><div class="home-mini-heading"><h3>Media Pembelajaran</h3><a class="text-link" href="{{ route('learning') }}">Semua modul ↗</a></div>
            @forelse($latestLearning as $item)
            <a class="home-list-item" href="{{ route('content.show', ['type' => 'learning', 'slug' => $item->slug]) }}"><span class="home-list-icon">◎</span><span><strong>{{ $item->title }}</strong><small>{{ $item->excerpt }}</small></span><b>↗</b></a>
            @empty<p class="home-muted">Modul jelajah kasus akan tersedia setelah diterbitkan.</p>@endforelse
        </div>
    </div>
</section>
@endif

<section class="closing-banner"><div><span class="eyebrow eyebrow-light">BELAJAR DARI KEHIDUPAN SEHARI-HARI</span><h2>Karena memahami kesehatan<br>adalah langkah awal perubahan.</h2></div><a class="button button-white" href="{{ route('learning') }}">Coba media pembelajaran ↗</a></section>
@endsection
