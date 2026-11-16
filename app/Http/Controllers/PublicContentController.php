<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function index(string $type): View
    {
        abort_unless(in_array($type, ['article', 'event', 'gallery', 'learning'], true), 404);

        $items = ContentItem::published()->where('type', $type)->latest('published_at')->paginate(9);

        return view('pages.content-index', compact('items', 'type'));
    }

    public function show(string $type, string $slug): View
    {
        abort_unless(in_array($type, ['article', 'event', 'gallery', 'learning'], true), 404);

        $item = ContentItem::published()->where('type', $type)->where('slug', $slug)->firstOrFail();

        return view('pages.content-show', compact('item', 'type'));
    }
}
