@extends('layouts.app')
@section('title', 'Login Admin')
@section('content')
<section class="admin-login-wrap"><div class="admin-login-card"><span class="eyebrow">AREA PENGELOLA</span><h1>Login <em>Admin</em></h1><p>Halaman masuk pengelola KADESTIM. Akun admin dibuat oleh pengelola yang berwenang.</p><form class="login-form" method="post" action="#"><label for="email">Email</label><input id="email" type="email" placeholder="admin@kadestim.id" autocomplete="username"><label for="password">Kata sandi</label><input id="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password"><button class="button button-primary" type="button" disabled>Autentikasi belum diaktifkan</button></form><a class="text-link" href="{{ route('home') }}">← Kembali ke situs</a></div></section>
@endsection
