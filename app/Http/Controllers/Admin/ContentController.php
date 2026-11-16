<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $items = ContentItem::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
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
        $this->storeUploadedCover($request, $data);
        $scenario = $data['scenario'] ?? [];
        unset($data['scenario']);

        $data['content_format'] = $data['type'] === 'article' ? ($data['content_format'] ?? 'text') : null;
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['author_id'] = $request->user()->id;
        $data['scenario_data'] = $data['type'] === 'learning' ? $this->normalizeScenario($scenario) : null;
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
        $oldCover = $content->cover_image;
        if (! $request->filled('cover_image') && ! $request->hasFile('cover_image_file')) {
            $data['cover_image'] = $oldCover;
        }
        $this->storeUploadedCover($request, $data);
        if ($request->hasFile('cover_image_file') && $oldCover && ! str_starts_with($oldCover, 'http')) {
            Storage::disk('public')->delete($oldCover);
        }
        $scenario = $data['scenario'] ?? [];
        unset($data['scenario']);

        $data['content_format'] = $data['type'] === 'article' ? ($data['content_format'] ?? 'text') : null;
        $data['slug'] = $this->uniqueSlug($data['title'], $content->id);
        $data['scenario_data'] = $data['type'] === 'learning' ? $this->normalizeScenario($scenario) : null;

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
        if ($content->cover_image && ! str_starts_with($content->cover_image, 'http')) {
            Storage::disk('public')->delete($content->cover_image);
        }

        $content->delete();

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil dihapus.');
    }


    private function storeUploadedCover(Request $request, array &$data): void
    {
        if ($request->hasFile('cover_image_file')) {
            $data['cover_image'] = $request->file('cover_image_file')->storePublicly('content-covers', 'public');
        }

        unset($data['cover_image_file']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', 'in:article,event,gallery,learning'],
            'content_format' => ['nullable', 'in:text,video'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'url', 'max:2048'],
            'cover_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,review,published,archived'],
            'event_start_at' => ['nullable', 'date'],
            'event_location' => ['nullable', 'string', 'max:180'],
            'external_url' => ['nullable', 'url', 'max:2048'],
            'scenario.audience' => ['required_if:type,learning', 'in:all,children,teens,adults'],
            'scenario.topic' => ['required_if:type,learning', 'string', 'max:100'],
            'scenario.intro' => ['required_if:type,learning', 'nullable', 'string', 'max:1000'],
            'scenario.situation' => ['required_if:type,learning', 'nullable', 'string', 'max:2000'],
            'scenario.supporting_info' => ['nullable', 'array', 'max:8'],
            'scenario.supporting_info.*' => ['nullable', 'string', 'max:500'],
            'scenario.question' => ['required_if:type,learning', 'nullable', 'string', 'max:500'],
            'scenario.choices' => ['required_if:type,learning', 'array', 'min:2', 'max:6'],
            'scenario.choices.*.label' => ['required', 'string', 'max:300'],
            'scenario.choices.*.feedback' => ['required', 'string', 'max:1000'],
            'scenario.choices.*.is_recommended' => ['nullable', 'boolean'],
            'scenario.summary' => ['required_if:type,learning', 'nullable', 'string', 'max:1500'],
        ]);

        if ($data['type'] === 'learning') {
            $recommendedCount = collect($data['scenario']['choices'] ?? [])
                ->filter(fn ($choice) => ! empty($choice['is_recommended']))
                ->count();

            if ($recommendedCount !== 1) {
                throw ValidationException::withMessages([
                    'scenario.choices' => 'Tandai tepat satu pilihan sebagai tindakan yang direkomendasikan.',
                ]);
            }
        }

        return $data;
    }

    private function normalizeScenario(array $scenario): array
    {
        $choices = collect($scenario['choices'] ?? [])
            ->map(fn ($choice) => [
                'label' => trim($choice['label']),
                'feedback' => trim($choice['feedback']),
                'is_recommended' => filter_var($choice['is_recommended'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ])->values()->all();

        return [
            'audience' => $scenario['audience'] ?? 'all',
            'topic' => trim($scenario['topic'] ?? ''),
            'intro' => trim($scenario['intro'] ?? ''),
            'situation' => trim($scenario['situation'] ?? ''),
            'supporting_info' => collect($scenario['supporting_info'] ?? [])
                ->filter(fn ($item) => filled($item))
                ->map(fn ($item) => trim($item))->values()->all(),
            'question' => trim($scenario['question'] ?? ''),
            'choices' => $choices,
            'summary' => trim($scenario['summary'] ?? ''),
        ];
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
