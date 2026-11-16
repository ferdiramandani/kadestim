@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<section class="page-hero"><span class="eyebrow">AREA PENGELOLA</span><h1>Halo, <em>{{ auth()->user()->name }}</em></h1><p>Kelola informasi dan materi KADESTIM dari satu tempat.</p></section>
<section class="section"><div class="admin-toolbar"><div><span class="eyebrow">PUSAT KONTROL</span><h2>Ringkasan konten</h2></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="button button-outline" type="submit">Keluar</button></form></div>
<div class="admin-grid">
@foreach ([['article','Edukasi Kesehatan'],['event','Event Duta'],['gallery','Galeri'],['learning','Media Pembelajaran']] as [$type,$label])
<a class="admin-tile" href="{{ route('admin.content.index',['type'=>$type]) }}"><b>{{ strtoupper($type) }}</b><h3>{{ $label }}</h3><p>Kelola konten, perbarui informasi, dan atur status publikasi.</p><span class="feature-link">Kelola konten ↗</span></a>
@endforeach
</div><div class="admin-bottom-link"><a class="button button-primary" href="{{ route('admin.content.create') }}">＋ Buat Konten Baru</a></div></section>
@endsection
