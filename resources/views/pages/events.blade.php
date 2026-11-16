@extends('layouts.app')
@section('title', 'Event Duta')
@section('content')
<section class="page-hero"><span class="eyebrow">GERAK DAN KOLABORASI</span><h1>Event <em>Duta</em></h1><p>Kenali agenda, kegiatan, dan ruang kolaborasi Duta Kesehatan Jawa Timur.</p></section>
<section class="section"><div class="empty-state"><span class="empty-icon">⌁</span><h2>Belum ada agenda ditampilkan</h2><p>Informasi event akan diperbarui oleh pengelola KADESTIM.</p><a class="text-link" href="{{ route('home') }}">Kembali ke beranda ↗</a></div></section>
@endsection
