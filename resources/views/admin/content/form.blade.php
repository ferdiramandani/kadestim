@extends('layouts.app')
@section('title', $item->exists ? 'Edit Konten' : 'Konten Baru')
@section('content')
<section class="page-hero"><span class="eyebrow">EDITOR KONTEN</span><h1>{{ $item->exists ? 'Edit' : 'Buat' }} <em>Konten</em></h1><p>Lengkapi informasi berikut. Konten berstatus Published akan tampil di halaman publik.</p></section>
<section class="section"><div class="editor-card">
@if ($errors->any())<div class="alert alert-error"><strong>Periksa kembali isian:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form class="editor-form" method="post" action="{{ $item->exists ? route('admin.content.update',$item) : route('admin.content.store') }}">
@csrf @if($item->exists) @method('PUT') @endif
<label>Judul konten<input name="title" value="{{ old('title',$item->title) }}" maxlength="180" required></label>
<div class="form-two"><label>Kategori<select name="type" required>@foreach(['article'=>'Edukasi Kesehatan','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'] as $key=>$label)<option value="{{ $key }}" @selected(old('type',$item->type)===$key)>{{ $label }}</option>@endforeach</select></label>
<label>Status<select name="status" required>@foreach(['draft'=>'Draft','review'=>'Review','published'=>'Published','archived'=>'Archived'] as $key=>$label)<option value="{{ $key }}" @selected(old('status',$item->status ?: 'draft')===$key)>{{ $label }}</option>@endforeach</select></label></div>
<label>Ringkasan<textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt',$item->excerpt) }}</textarea></label>
<label>Isi konten<textarea name="body" rows="10">{{ old('body',$item->body) }}</textarea></label>
<label>URL gambar sampul (opsional)<input type="url" name="cover_image" value="{{ old('cover_image',$item->cover_image) }}" placeholder="https://..."></label>
<div class="form-two"><label>Tanggal dan waktu event (opsional)<input type="datetime-local" name="event_start_at" value="{{ old('event_start_at',$item->event_start_at?->format('Y-m-d\TH:i')) }}"></label><label>Lokasi event (opsional)<input name="event_location" value="{{ old('event_location',$item->event_location) }}"></label></div>
<label>Link pendaftaran / informasi (opsional)<input type="url" name="external_url" value="{{ old('external_url',$item->external_url) }}"></label>
<label>Data skenario pembelajaran (JSON)
<textarea name="scenario_data" rows="12" placeholder='{"intro":"...","situation":"...","supporting_info":["..."],"question":"...","choices":[{"label":"...","feedback":"...","is_recommended":true}],"summary":"..."}'>{{ old('scenario_data', $item->scenario_data ? json_encode($item->scenario_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
<small>Isi untuk kategori Media Pembelajaran. Gunakan struktur JSON: intro, situation, supporting_info (array), question, choices (array label/feedback/is_recommended), dan summary. Materi perlu ditinjau sebelum dipublikasikan.</small>
</label>
<div class="form-actions"><a class="button button-outline" href="{{ route('admin.content.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan Konten</button></div>
</form></div></section>
@endsection
