@extends('layouts.app')
@section('title', ['article'=>'Edukasi Kesehatan','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'][$type])
@section('content')
<section class="page-hero"><span class="eyebrow">KADESTIM · {{ strtoupper($type) }}</span><h1>{{ ['article'=>'Edukasi','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'][$type] }} <em>KADESTIM</em></h1><p>Informasi yang telah dipublikasikan oleh pengelola KADESTIM.</p></section>
@if($type === 'learning')
<section class="learning-filter-wrap"><form class="learning-filters" method="get" action="{{ route('learning') }}"><label>Kelompok usia<select name="audience"><option value="">Semua kelompok</option><option value="children" @selected(request('audience') === 'children')>Anak-anak</option><option value="teens" @selected(request('audience') === 'teens')>Remaja</option><option value="adults" @selected(request('audience') === 'adults')>Dewasa / keluarga</option><option value="all" @selected(request('audience') === 'all')>Semua usia</option></select></label><label>Topik<select name="topic"><option value="">Semua topik</option>@foreach(['Kebersihan diri','Gizi dan nutrisi','Kesehatan remaja','Pencegahan penyakit','Kesehatan keluarga','Kesehatan lingkungan','Pertolongan pertama dasar'] as $topic)<option value="{{ $topic }}" @selected(request('topic') === $topic)>{{ $topic }}</option>@endforeach</select></label><button class="button button-primary" type="submit">Terapkan filter</button><a class="text-link" href="{{ route('learning') }}">Reset</a></form></section>
@endif
@if($type === 'article')
<section class="learning-filter-wrap"><form class="learning-filters" method="get" action="{{ route('education') }}"><label>Jenis edukasi<select name="format"><option value="">Semua edukasi</option><option value="text" @selected(request('format') === 'text')>Edukasi teks</option><option value="video" @selected(request('format') === 'video')>Edukasi video</option></select></label><button class="button button-primary" type="submit">Terapkan</button><a class="text-link" href="{{ route('education') }}">Reset</a></form></section>
@endif
<section class="section"><div class="public-content-grid">
@forelse($items as $item)
<a class="public-content-card" href="{{ route('content.show',[$type,$item->slug]) }}">
@if($item->cover_image)<img src="{{ str_starts_with($item->cover_image, 'http') ? $item->cover_image : asset('storage/'.$item->cover_image) }}" alt="" loading="lazy">@else<div class="content-placeholder">KADESTIM</div>@endif
<div class="public-content-copy"><span class="eyebrow">{{ $item->published_at?->format('d M Y') }} @if($type === 'article') · {{ ($item->content_format ?? 'text') === 'video' ? 'VIDEO' : 'ARTIKEL' }} @endif</span>@if($type === 'learning' && $item->scenario_data)<div class="learning-tags"><span>{{ ['children'=>'Anak-anak','teens'=>'Remaja','adults'=>'Dewasa / keluarga','all'=>'Semua usia'][$item->scenario_data['audience'] ?? 'all'] }}</span><span>{{ $item->scenario_data['topic'] ?? 'Pembelajaran kesehatan' }}</span></div>@endif<h2>{{ $item->title }}</h2><p>{{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->body), 150) }}</p><span class="feature-link">Selengkapnya ↗</span></div></a>
@empty<div class="empty-state"><span class="empty-icon">✳</span><h2>Konten belum tersedia</h2><p>Materi untuk kategori ini akan ditampilkan setelah diterbitkan oleh pengelola.</p><a class="text-link" href="{{ route('home') }}">Kembali ke beranda ↗</a></div>@endforelse
</div>{{ $items->links() }}</section>
@endsection
