@extends('layouts.app')
@section('title', 'Kelola Konten')
@section('content')
<section class="page-hero"><span class="eyebrow">PENGELOLAAN KONTEN</span><h1>Kelola <em>Konten</em></h1><p>Atur artikel, event, dokumentasi galeri, dan modul pembelajaran KADESTIM.</p></section>
<section class="section">
@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="admin-toolbar"><div><span class="eyebrow">DAFTAR KONTEN</span><h2>Semua materi</h2></div><a class="button button-primary" href="{{ route('admin.content.create') }}">＋ Tambah Konten</a></div>
<form class="filter-row" method="get">
<select name="type"><option value="">Semua kategori</option>@foreach(['article'=>'Edukasi','event'=>'Event','gallery'=>'Galeri','learning'=>'Pembelajaran'] as $key=>$label)<option value="{{ $key }}" @selected(request('type')===$key)>{{ $label }}</option>@endforeach</select>
<select name="status"><option value="">Semua status</option>@foreach(['draft'=>'Draft','review'=>'Review','published'=>'Published','archived'=>'Archived'] as $key=>$label)<option value="{{ $key }}" @selected(request('status')===$key)>{{ $label }}</option>@endforeach</select><button class="button button-outline" type="submit">Filter</button>
</form>
<div class="content-table-wrap"><table class="content-table"><thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Diperbarui</th><th>Aksi</th></tr></thead><tbody>
@forelse ($items as $item)
<tr><td><strong>{{ $item->title }}</strong><small>{{ $item->slug }}</small></td><td>{{ ['article'=>'Edukasi','event'=>'Event','gallery'=>'Galeri','learning'=>'Pembelajaran'][$item->type] }}</td><td><span class="status status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td><td>{{ $item->updated_at->format('d M Y') }}</td><td><div class="table-actions"><a href="{{ route('admin.content.edit',$item) }}">Edit</a><form method="post" action="{{ route('admin.content.destroy',$item) }}" onsubmit="return confirm('Hapus konten ini?')">@csrf @method('DELETE')<button type="submit">Hapus</button></form></div></td></tr>
@empty<tr><td colspan="5" class="table-empty">Belum ada konten. Mulai dengan membuat konten pertama.</td></tr>@endforelse
</tbody></table></div>{{ $items->links() }}<p class="admin-bottom-link"><a class="text-link" href="{{ route('admin.dashboard') }}">← Kembali ke dashboard</a></p>
</section>
@endsection
