@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<section class="page-hero"><span class="eyebrow">AREA PENGELOLA</span><h1>Dashboard <em>Admin</em></h1><p>Ringkasan pengelolaan konten KADESTIM. Autentikasi dan pembatasan akses akan diaktifkan pada tahap backend.</p></section>
<section class="section"><div class="admin-grid"><div class="admin-tile"><b>01</b><h3>Kelola Edukasi</h3><p>Buat dan tinjau artikel kesehatan.</p></div><div class="admin-tile"><b>02</b><h3>Kelola Event</h3><p>Atur agenda dan informasi kegiatan.</p></div><div class="admin-tile"><b>03</b><h3>Kelola Galeri</h3><p>Atur dokumentasi dan media.</p></div><div class="admin-tile"><b>04</b><h3>Kelola Pembelajaran</h3><p>Susun skenario dan materi belajar.</p></div></div></section>
@endsection
