@extends('layouts.app')
@section('title', ['article'=>'Edukasi Kesehatan','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'][$type])
@section('content')
<section class="page-hero"><span class="eyebrow">KADESTIM · {{ strtoupper($type) }}</span><h1>{{ ['article'=>'Edukasi','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'][$type] }} <em>KADESTIM</em></h1><p>Informasi yang telah dipublikasikan oleh pengelola KADESTIM.</p></section>
<section class="section"><div class="public-content-grid">
@forelse($items as $item)
<a class="public-content-card" href="{{ route('content.show',[$type,$item->slug]) }}">
@if($item->cover_image)<img src="{{ $item->cover_image }}" alt="" loading="lazy">@else<div class="content-placeholder">KADESTIM</div>@endif
<div class="public-content-copy"><span class="eyebrow">{{ $item->published_at?->format('d M Y') }}</span><h2>{{ $item->title }}</h2><p>{{ $item->excerpt }}</p><span class="feature-link">Baca selengkapnya ↗</span></div></a>
@empty<div class="empty-state"><span class="empty-icon">✳</span><h2>Konten belum tersedia</h2><p>Materi untuk kategori ini akan ditampilkan setelah diterbitkan oleh pengelola.</p><a class="text-link" href="{{ route('home') }}">Kembali ke beranda ↗</a></div>@endforelse
</div>{{ $items->links() }}</section>
@endsection
