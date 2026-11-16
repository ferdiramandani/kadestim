@extends('layouts.app')
@section('title', $item->title)
@section('content')
<section class="page-hero"><span class="eyebrow">KADESTIM · {{ strtoupper($type) }}</span><h1>{{ $item->title }}</h1><p>{{ $item->excerpt }}</p></section>
<article class="article-body">
@if($item->cover_image)<img class="article-cover" src="{{ $item->cover_image }}" alt="{{ $item->title }}">@endif
<div class="article-meta">Dipublikasikan {{ $item->published_at?->format('d F Y') }}</div>
@if($item->event_start_at)<p><strong>Waktu:</strong> {{ $item->event_start_at->format('d F Y, H:i') }} WIB</p>@endif
@if($item->event_location)<p><strong>Lokasi:</strong> {{ $item->event_location }}</p>@endif
<div class="article-text">{!! nl2br(e($item->body)) !!}</div>
@if($item->external_url)<p><a class="button button-primary" href="{{ $item->external_url }}" target="_blank" rel="noopener noreferrer">Informasi selengkapnya ↗</a></p>@endif
<a class="text-link" href="{{ route('home') }}">← Kembali ke beranda</a>
</article>
@endsection
