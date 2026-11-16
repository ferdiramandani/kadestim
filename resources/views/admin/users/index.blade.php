@extends('layouts.app')
@section('title', 'Kelola Admin')
@section('content')
<section class="page-hero"><span class="eyebrow">MANAJEMEN AKUN</span><h1>Kelola <em>Admin</em></h1><p>Tambahkan pengelola KADESTIM dan atur peran serta status akun.</p></section>
<section class="section">
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<div class="editor-card"><h2>Tambah admin</h2>
<form class="editor-form" method="post" action="{{ route('admin.users.store') }}">@csrf
<label>Nama<input name="name" required maxlength="120"></label><label>Email<input name="email" type="email" required></label>
<div class="form-two"><label>Peran<select name="role" required>@foreach(['admin'=>'Admin umum','super_admin'=>'Super Admin','admin_event'=>'Admin Event','admin_education'=>'Admin Edukasi','admin_gallery'=>'Admin Galeri','admin_learning'=>'Admin Pembelajaran'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label><label>Kata sandi sementara<input name="password" type="password" minlength="12" required></label></div>
<label>Konfirmasi kata sandi<input name="password_confirmation" type="password" minlength="12" required></label><button class="button button-primary" type="submit">＋ Tambah admin</button>
</form></div>
<div class="admin-toolbar"><div><span class="eyebrow">TIM PENGELOLA</span><h2>Daftar akun admin</h2></div></div>
<div class="content-table-wrap"><table class="content-table"><thead><tr><th>Nama / Email</th><th>Peran</th><th>Status</th><th>Perbarui akses</th></tr></thead><tbody>
@foreach($users as $user)<tr><td><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></td><td>{{ ['admin'=>'Admin umum','super_admin'=>'Super Admin','admin_event'=>'Admin Event','admin_education'=>'Admin Edukasi','admin_gallery'=>'Admin Galeri','admin_learning'=>'Admin Pembelajaran'][$user->role] ?? $user->role }}</td><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td><td><form method="post" action="{{ route('admin.users.update',$user) }}" class="table-actions">@csrf @method('PATCH')<select name="role">@foreach(['admin'=>'Admin umum','super_admin'=>'Super Admin','admin_event'=>'Admin Event','admin_education'=>'Admin Edukasi','admin_gallery'=>'Admin Galeri','admin_learning'=>'Admin Pembelajaran'] as $value=>$label)<option value="{{ $value }}" @selected($user->role===$value)>{{ $label }}</option>@endforeach</select><select name="is_active"><option value="1" @selected($user->is_active)>Aktif</option><option value="0" @selected(!$user->is_active)>Nonaktif</option></select><button type="submit">Simpan</button></form></td></tr>@endforeach
</tbody></table></div>{{ $users->links() }}
<p class="admin-bottom-link"><a class="text-link" href="{{ route('admin.dashboard') }}">← Kembali ke dashboard</a></p>
</section>
@endsection
