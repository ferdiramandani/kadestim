@extends('layouts.app')
@section('title', $item->title)
@section('content')
<section class="page-hero"><span class="eyebrow">KADESTIM · {{ strtoupper($type) }}</span><h1>{{ $item->title }}</h1><p>{{ $item->excerpt }}</p></section>
<article class="article-body">
@if($item->cover_image)<img class="article-cover" src="{{ str_starts_with($item->cover_image, 'http') ? $item->cover_image : asset('storage/'.$item->cover_image) }}" alt="{{ $item->title }}">@endif
<div class="article-meta">Dipublikasikan {{ $item->published_at?->format('d F Y') }}</div>
@if($type === 'learning' && $item->scenario_data)
<div class="learning-tags"><span>{{ ['children'=>'Anak-anak','teens'=>'Remaja','adults'=>'Dewasa / keluarga','all'=>'Semua usia'][$item->scenario_data['audience'] ?? 'all'] }}</span><span>{{ $item->scenario_data['topic'] ?? 'Pembelajaran kesehatan' }}</span></div>
@endif
@if($item->event_start_at)<p><strong>Waktu:</strong> {{ $item->event_start_at->format('d F Y, H:i') }} WIB</p>@endif
@if($item->event_location)<p><strong>Lokasi:</strong> {{ $item->event_location }}</p>@endif
@if($type === 'learning' && $item->scenario_data)
<section class="scenario-panel" id="scenario-module">
    <span class="eyebrow">JELAJAH KASUS KESEHATAN</span>
    <h2 id="scenario-heading">Memahami situasi</h2>
    <p id="scenario-intro"></p>
    <div class="scenario-situation"><strong>Situasi</strong><p id="scenario-situation-text"></p></div>
    <div class="scenario-support"><strong>Informasi pendukung</strong><ul id="scenario-info"></ul></div>
    <div class="scenario-question"><h3 id="scenario-question"></h3><div id="scenario-choices" class="scenario-choices"></div><div id="scenario-feedback" class="scenario-feedback" hidden></div></div>
    <div class="scenario-summary" id="scenario-summary" hidden><strong>Kesimpulan</strong><p id="scenario-summary-text"></p></div>
    <p class="scenario-disclaimer">Materi ini untuk edukasi umum, bukan diagnosis atau pengganti konsultasi tenaga kesehatan.</p>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = @json($item->scenario_data);
    const root = document.getElementById('scenario-module');
    if (!root || !data) return;
    const put = (id, value) => { const node = document.getElementById(id); if (node) node.textContent = value || ''; };
    put('scenario-intro', data.intro);
    put('scenario-situation-text', data.situation);
    put('scenario-question', data.question || 'Apa tindakan yang paling tepat dalam situasi ini?');
    const info = document.getElementById('scenario-info');
    (Array.isArray(data.supporting_info) ? data.supporting_info : []).forEach(item => {
        const li = document.createElement('li'); li.textContent = item; info.appendChild(li);
    });
    const choices = document.getElementById('scenario-choices');
    const feedback = document.getElementById('scenario-feedback');
    (Array.isArray(data.choices) ? data.choices : []).forEach((choice, index) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'scenario-choice';
        button.textContent = choice.label || ('Pilihan ' + (index + 1));
        button.addEventListener('click', () => {
            [...choices.querySelectorAll('button')].forEach(item => item.disabled = true);
            feedback.hidden = false;
            feedback.textContent = choice.feedback || 'Terima kasih sudah mempertimbangkan pilihan tindakan ini.';
            if (choice.is_recommended) feedback.dataset.recommended = 'true';
            else feedback.dataset.recommended = 'false';
            const summary = document.getElementById('scenario-summary');
            if (data.summary) {
                summary.hidden = false;
                put('scenario-summary-text', data.summary);
            }
        });
        choices.appendChild(button);
    });
});
</script>
@endif
<div class="article-text">{!! nl2br(e($item->body)) !!}</div>
@if($item->external_url)<p><a class="button button-primary" href="{{ $item->external_url }}" target="_blank" rel="noopener noreferrer">Informasi selengkapnya ↗</a></p>@endif
<a class="text-link" href="{{ route('home') }}">← Kembali ke beranda</a>
</article>
@endsection
