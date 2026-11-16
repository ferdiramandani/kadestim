@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<section class="page-hero admin-welcome">
    <span class="eyebrow">AREA PENGELOLA · KADESTIM</span>
    <h1>Selamat datang, <em>{{ auth()->user()->name }}</em></h1>
    <p>Pusat kendali untuk mengelola edukasi kesehatan, agenda Duta, galeri, dan media pembelajaran.</p>
</section>
<section class="section admin-dashboard-section">
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="admin-toolbar">
        <div><span class="eyebrow">RINGKASAN WEBSITE</span><h2>Ikhtisar konten</h2></div>
        <div class="admin-toolbar-actions">
            <a class="button button-primary" href="{{ route('admin.content.create') }}">＋ Buat Konten</a>
            <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="button button-outline" type="submit">Keluar</button></form>
        </div>
    </div>
    <div class="dashboard-stats">
        <article class="stat-card stat-card-main"><span>Total konten</span><strong>{{ $counts['total'] }}</strong><small>Seluruh kategori</small></article>
        <article class="stat-card"><span>Published</span><strong>{{ $counts['published'] }}</strong><small>Tampil untuk publik</small></article>
        <article class="stat-card"><span>Draft</span><strong>{{ $counts['draft'] }}</strong><small>Belum diterbitkan</small></article>
        <article class="stat-card"><span>Perlu review</span><strong>{{ $counts['review'] }}</strong><small>Menunggu pemeriksaan</small></article>
    </div>
    <div class="admin-section-heading"><div><span class="eyebrow">KATEGORI KONTEN</span><h2>Kelola berdasarkan bagian</h2></div></div>
    <div class="admin-grid">
        @foreach ([['article','Edukasi Kesehatan','Artikel, informasi, dan materi kesehatan.'],['event','Event Duta','Agenda dan kegiatan Duta Kesehatan.'],['gallery','Galeri','Dokumentasi kegiatan dan kampanye.'],['learning','Media Pembelajaran','Jelajah kasus kesehatan interaktif.']] as [$type,$label,$description])
            <a class="admin-tile" href="{{ route('admin.content.index',['type'=>$type]) }}">
                <span class="admin-tile-count">{{ $byType[$type] ?? 0 }} konten</span>
                <h3>{{ $label }}</h3><p>{{ $description }}</p><span class="feature-link">Buka pengelolaan ↗</span>
            </a>
        @endforeach
    </div>
    <div class="recent-content">
        <div class="admin-section-heading"><div><span class="eyebrow">AKTIVITAS TERBARU</span><h2>Konten terakhir diperbarui</h2></div><a class="text-link" href="{{ route('admin.content.index') }}">Lihat semua ↗</a></div>
        <div class="content-table-wrap"><table class="content-table"><thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Terakhir diperbarui</th><th></th></tr></thead><tbody>
        @forelse($recentItems as $item)
            <tr><td><strong>{{ $item->title }}</strong></td><td>{{ ['article'=>'Edukasi','event'=>'Event','gallery'=>'Galeri','learning'=>'Pembelajaran'][$item->type] }}</td><td><span class="status status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td><td>{{ $item->updated_at->format('d M Y') }}</td><td><a class="table-action-link" href="{{ route('admin.content.edit',$item) }}">Edit</a></td></tr>
        @empty
            <tr><td colspan="5" class="table-empty">Belum ada konten. Buat konten pertama untuk mulai mengisi website KADESTIM.</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
</section>
@endsection
