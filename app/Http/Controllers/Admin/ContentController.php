<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $items = ContentItem::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.content.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.content.form', ['item' => new ContentItem()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['author_id'] = $request->user()->id;
        $data['scenario_data'] = ! empty($data['scenario_data']) ? json_decode($data['scenario_data'], true) : null;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        ContentItem::create($data);

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil dibuat.');
    }

    public function edit(ContentItem $content): View
    {
        return view('admin.content.form', ['item' => $content]);
    }

    public function update(Request $request, ContentItem $content): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title'], $content->id);
        $data['scenario_data'] = ! empty($data['scenario_data']) ? json_decode($data['scenario_data'], true) : null;

        if ($data['status'] === 'published' && ! $content->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        $content->update($data);

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(ContentItem $content): RedirectResponse
    {
        $content->delete();

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', 'in:article,event,gallery,learning'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'url', 'max:2048'],
            'status' => ['required', 'in:draft,review,published,archived'],
            'event_start_at' => ['nullable', 'date'],
            'event_location' => ['nullable', 'string', 'max:180'],
            'external_url' => ['nullable', 'url', 'max:2048'],
            'scenario_data' => ['nullable', 'json'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'konten';
        $slug = $base;
        $counter = 2;

        while (ContentItem::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
