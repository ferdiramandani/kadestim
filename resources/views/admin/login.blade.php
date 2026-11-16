@extends('layouts.app')
@section('title', 'Login Admin')
@section('content')
<section class="admin-login-wrap"><div class="admin-login-card">
<span class="eyebrow">AREA PENGELOLA</span><h1>Login <em>Admin</em></h1>
<p>Masuk untuk mengelola informasi dan materi KADESTIM. Akun admin hanya dibuat oleh pengelola berwenang.</p>
@if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
<form class="login-form" method="post" action="{{ route('admin.login.submit') }}">
@csrf
<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="admin@kadestim.id" autocomplete="username" required>
<label for="password">Kata sandi</label><input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
<label class="remember-row"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
<button class="button button-primary" type="submit">Masuk ke Dashboard <span>→</span></button>
</form><a class="text-link" href="{{ route('home') }}">← Kembali ke situs</a>
</div></section>
@endsection
