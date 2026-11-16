@extends('layouts.app')
@section('title', $item->exists ? 'Edit Konten' : 'Konten Baru')
@section('content')
<section class="page-hero"><span class="eyebrow">EDITOR KONTEN</span><h1>{{ $item->exists ? 'Edit' : 'Buat' }} <em>Konten</em></h1><p>Kelola informasi KADESTIM. Simpan sebagai draft, kirim untuk review, atau terbitkan setelah materi siap.</p></section>
<section class="section"><div class="editor-card">
@if ($errors->any())<div class="alert alert-error"><strong>Periksa kembali isian:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@php
    $scenario = old('scenario', $item->scenario_data ?? []);
    $supporting = $scenario['supporting_info'] ?? [''];
    $choices = $scenario['choices'] ?? [['label'=>'','feedback'=>'','is_recommended'=>false],['label'=>'','feedback'=>'','is_recommended'=>false]];
@endphp
<form class="editor-form" id="content-editor" method="post" action="{{ $item->exists ? route('admin.content.update',$item) : route('admin.content.store') }}">
@csrf @if($item->exists) @method('PUT') @endif
<label>Judul konten<input name="title" value="{{ old('title',$item->title) }}" maxlength="180" required></label>
<div class="form-two"><label>Kategori<select name="type" id="content-type" required>@foreach(['article'=>'Edukasi Kesehatan','event'=>'Event Duta','gallery'=>'Galeri','learning'=>'Media Pembelajaran'] as $key=>$label)<option value="{{ $key }}" @selected(old('type',$item->type)===$key)>{{ $label }}</option>@endforeach</select></label>
<label>Status<select name="status" required>@foreach(['draft'=>'Draft','review'=>'Review','published'=>'Published','archived'=>'Archived'] as $key=>$label)<option value="{{ $key }}" @selected(old('status',$item->status ?: 'draft')===$key)>{{ $label }}</option>@endforeach</select></label></div>
<label>Ringkasan<textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt',$item->excerpt) }}</textarea></label>
<label>Isi konten<textarea name="body" rows="8">{{ old('body',$item->body) }}</textarea></label>
<label>URL gambar sampul (opsional)<input type="url" name="cover_image" value="{{ old('cover_image',$item->cover_image) }}" placeholder="https://..."></label>
<div class="form-two"><label>Tanggal dan waktu event (opsional)<input type="datetime-local" name="event_start_at" value="{{ old('event_start_at',$item->event_start_at?->format('Y-m-d\TH:i')) }}"></label><label>Lokasi event (opsional)<input name="event_location" value="{{ old('event_location',$item->event_location) }}"></label></div>
<label>Link pendaftaran / informasi (opsional)<input type="url" name="external_url" value="{{ old('external_url',$item->external_url) }}"></label>

<section class="scenario-editor" id="scenario-editor">
    <div class="scenario-editor-heading"><div><span class="eyebrow">MEDIA PEMBELAJARAN</span><h2>Rancang Jelajah Kasus</h2><p>Susun materi pembelajaran berbasis situasi nyata. Pengunjung akan membaca informasi, memilih tindakan, lalu melihat penjelasan.</p></div><span class="scenario-step-tag">Scenario-Based Learning</span></div>
    <div class="form-two"><label>Kelompok sasaran<select name="scenario[audience]"><option value="all" @selected(($scenario['audience'] ?? 'all') === 'all')>Semua usia</option><option value="children" @selected(($scenario['audience'] ?? '') === 'children')>Anak-anak</option><option value="teens" @selected(($scenario['audience'] ?? '') === 'teens')>Remaja</option><option value="adults" @selected(($scenario['audience'] ?? '') === 'adults')>Dewasa / keluarga</option></select></label><label>Topik pembelajaran<select name="scenario[topic]"><option value="">Pilih topik</option>@foreach(['Kebersihan diri','Gizi dan nutrisi','Kesehatan remaja','Pencegahan penyakit','Kesehatan keluarga','Kesehatan lingkungan','Pertolongan pertama dasar'] as $topic)<option value="{{ $topic }}" @selected(($scenario['topic'] ?? '') === $topic)>{{ $topic }}</option>@endforeach</select></label></div>
    <label>Pembuka / tujuan pembelajaran<textarea name="scenario[intro]" rows="2" placeholder="Contoh: Setelah mempelajari kasus ini, peserta memahami kebiasaan sehat sebelum makan.">{{ $scenario['intro'] ?? '' }}</textarea></label>
    <label>Situasi kasus<textarea name="scenario[situation]" rows="4" placeholder="Ceritakan situasi sehari-hari yang perlu dipahami peserta.">{{ $scenario['situation'] ?? '' }}</textarea></label>
    <div class="scenario-builder-group"><div class="scenario-builder-title"><strong>Informasi pendukung</strong><span>Tambahkan fakta atau petunjuk yang membantu peserta memahami konteks.</span></div>
        <div id="supporting-list">@foreach($supporting as $index=>$info)<div class="dynamic-field"><textarea name="scenario[supporting_info][]" rows="2" placeholder="Informasi pendukung {{ $index+1 }}">{{ $info }}</textarea><button class="remove-field" type="button" aria-label="Hapus informasi">×</button></div>@endforeach</div>
        <button type="button" class="button button-outline button-small" id="add-supporting">＋ Tambah informasi</button>
    </div>
    <label>Pertanyaan refleksi<textarea name="scenario[question]" rows="2" placeholder="Apa tindakan yang paling tepat dalam situasi ini?">{{ $scenario['question'] ?? '' }}</textarea></label>
    <div class="scenario-builder-group"><div class="scenario-builder-title"><strong>Pilihan tindakan dan penjelasan</strong><span>Minimal dua pilihan. Tandai satu tindakan yang direkomendasikan.</span></div>
        <div id="choices-list">@foreach($choices as $index=>$choice)<div class="choice-editor">
            <div class="choice-editor-top"><strong>Pilihan <span class="choice-number">{{ $index+1 }}</span></strong><button class="remove-field" type="button" aria-label="Hapus pilihan">Hapus ×</button></div>
            <label>Teks pilihan<input name="scenario[choices][{{ $index }}][label]" value="{{ $choice['label'] ?? '' }}" placeholder="Tuliskan tindakan yang dipilih"></label>
            <label>Penjelasan setelah dipilih<textarea name="scenario[choices][{{ $index }}][feedback]" rows="2" placeholder="Jelaskan alasan dan dampak pilihan ini.">{{ $choice['feedback'] ?? '' }}</textarea></label>
            <label class="recommended-check"><input type="checkbox" name="scenario[choices][{{ $index }}][is_recommended]" value="1" @checked($choice['is_recommended'] ?? false)> Tandai sebagai tindakan yang direkomendasikan</label>
        </div>@endforeach</div>
        <button type="button" class="button button-outline button-small" id="add-choice">＋ Tambah pilihan tindakan</button>
    </div>
    <label>Rangkuman pembelajaran<textarea name="scenario[summary]" rows="3" placeholder="Tuliskan hal utama yang perlu diingat peserta setelah menjelajahi kasus.">{{ $scenario['summary'] ?? '' }}</textarea></label>
    <div class="scenario-preview-box"><div><strong>Pratinjau skenario</strong><span>Lihat gambaran materi seperti yang akan dibaca pengunjung.</span></div><button type="button" class="button button-outline button-small" id="preview-scenario">Lihat pratinjau ↗</button></div>
</section>
<div class="form-actions"><a class="button button-outline" href="{{ route('admin.content.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan Konten</button></div>
</form>
</div></section>
<div class="preview-modal" id="scenario-preview-modal" hidden role="dialog" aria-modal="true" aria-labelledby="preview-title">
<div class="preview-dialog"><div class="preview-dialog-head"><div><span class="eyebrow">PRATINJAU PENGUNJUNG</span><h2 id="preview-title">Jelajah Kasus Kesehatan</h2></div><button type="button" class="preview-close" id="close-preview" aria-label="Tutup pratinjau">×</button></div>
<div class="scenario-panel preview-panel"><span class="eyebrow">JELAJAH KASUS KESEHATAN</span><h2 id="preview-scenario-title">Memahami situasi</h2><p id="preview-intro"></p><div class="scenario-situation"><strong>Situasi</strong><p id="preview-situation"></p></div><div class="scenario-support"><strong>Informasi pendukung</strong><ul id="preview-info"></ul></div><div class="scenario-question"><h3 id="preview-question"></h3><div class="scenario-choices" id="preview-choices"></div><div class="scenario-feedback" id="preview-feedback" hidden></div></div><div class="scenario-summary" id="preview-summary" hidden><strong>Kesimpulan</strong><p id="preview-summary-text"></p></div></div>
</div></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const type = document.getElementById('content-type');
    const scenarioEditor = document.getElementById('scenario-editor');
    const form = document.getElementById('content-editor');
    const modal = document.getElementById('scenario-preview-modal');
    const syncType = () => { scenarioEditor.hidden = type.value !== 'learning'; };
    type.addEventListener('change', syncType); syncType();

    const supportList = document.getElementById('supporting-list');
    const choicesList = document.getElementById('choices-list');
    const reindexChoices = () => [...choicesList.querySelectorAll('.choice-editor')].forEach((card,index) => {
        card.querySelector('.choice-number').textContent = index + 1;
        card.querySelectorAll('input,textarea').forEach(field => field.name = field.name.replace(/scenario\[choices\]\[\d+\]/, 'scenario[choices]['+index+']'));
    });
    document.getElementById('add-supporting').addEventListener('click', () => {
        if (supportList.children.length >= 8) return;
        const row = document.createElement('div'); row.className = 'dynamic-field';
        const textarea = document.createElement('textarea'); textarea.name = 'scenario[supporting_info][]'; textarea.rows = 2; textarea.placeholder = 'Informasi pendukung';
        const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'remove-field'; remove.textContent = '×'; remove.setAttribute('aria-label','Hapus informasi');
        row.append(textarea,remove); supportList.append(row);
    });
    document.getElementById('add-choice').addEventListener('click', () => {
        if (choicesList.children.length >= 6) return;
        const index = choicesList.children.length;
        const card = document.createElement('div'); card.className = 'choice-editor';
        card.innerHTML = '<div class="choice-editor-top"><strong>Pilihan <span class="choice-number">'+(index+1)+'</span></strong><button class="remove-field" type="button">Hapus ×</button></div><label>Teks pilihan<input name="scenario[choices]['+index+'][label]" placeholder="Tuliskan tindakan yang dipilih"></label><label>Penjelasan setelah dipilih<textarea name="scenario[choices]['+index+'][feedback]" rows="2" placeholder="Jelaskan alasan dan dampak pilihan ini."></textarea></label><label class="recommended-check"><input type="checkbox" name="scenario[choices]['+index+'][is_recommended]" value="1"> Tandai sebagai tindakan yang direkomendasikan</label>';
        choicesList.append(card);
    });
    document.addEventListener('click', event => {
        const remove = event.target.closest('.remove-field');
        if (!remove) return;
        const row = remove.closest('.dynamic-field, .choice-editor');
        if (row) row.remove();
        reindexChoices();
    });

    const readScenario = () => {
        const values = new FormData(form);
        const choices = [...choicesList.querySelectorAll('.choice-editor')].map(card => ({
            label: card.querySelector('input[type="text"],input:not([type])')?.value || '',
            feedback: card.querySelector('textarea')?.value || '',
            recommended: !!card.querySelector('input[type="checkbox"]')?.checked
        })).filter(choice => choice.label.trim());
        return {
            intro: values.get('scenario[intro]') || '',
            situation: values.get('scenario[situation]') || '',
            supporting: [...supportList.querySelectorAll('textarea')].map(el=>el.value).filter(Boolean),
            question: values.get('scenario[question]') || '',
            choices,
            summary: values.get('scenario[summary]') || ''
        };
    };
    const setText = (id,value) => document.getElementById(id).textContent = value;
    document.getElementById('preview-scenario').addEventListener('click', () => {
        const data = readScenario();
        setText('preview-intro',data.intro); setText('preview-situation',data.situation);
        setText('preview-question',data.question || 'Apa tindakan yang paling tepat?');
        setText('preview-summary-text',data.summary);
        const info = document.getElementById('preview-info'); info.replaceChildren();
        data.supporting.forEach(text => { const li=document.createElement('li'); li.textContent=text; info.append(li); });
        const choices=document.getElementById('preview-choices'); choices.replaceChildren();
        const feedback=document.getElementById('preview-feedback'); feedback.hidden=true;
        const summary=document.getElementById('preview-summary'); summary.hidden=!data.summary;
        data.choices.forEach(choice => {
            const button=document.createElement('button'); button.type='button'; button.className='scenario-choice'; button.textContent=choice.label;
            button.addEventListener('click', () => {
                choices.querySelectorAll('button').forEach(item=>item.disabled=true);
                feedback.hidden=false; feedback.textContent=choice.feedback;
                feedback.dataset.recommended=String(choice.recommended);
            });
            choices.append(button);
        });
        modal.hidden=false; document.body.classList.add('modal-open');
    });
    const close = () => { modal.hidden=true; document.body.classList.remove('modal-open'); };
    document.getElementById('close-preview').addEventListener('click',close);
    modal.addEventListener('click',event=>{if(event.target===modal)close();});
    document.addEventListener('keydown',event=>{if(event.key==='Escape'&&!modal.hidden)close();});
});
</script>
@endsection
