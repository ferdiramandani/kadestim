<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function index(\\Illuminate\\Http\\Request $request, string $type): View
    {
        abort_unless(in_array($type, ['article', 'event', 'gallery', 'learning'], true), 404);

        $query = ContentItem::published()->where('type', $type);
        if ($type === 'learning') {
            $query->when($request->filled('audience'), fn ($q) => $q->where('scenario_data->audience', $request->input('audience')))
                ->when($request->filled('topic'), fn ($q) => $q->where('scenario_data->topic', $request->input('topic')));
        }
        $items = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('pages.content-index', compact('items', 'type'));
    }

    public function show(string $type, string $slug): View
    {
        abort_unless(in_array($type, ['article', 'event', 'gallery', 'learning'], true), 404);

        $item = ContentItem::published()->where('type', $type)->where('slug', $slug)->firstOrFail();

        return view('pages.content-show', compact('item', 'type'));
    }
}
