<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $published = ContentItem::published();

        $counts = [
            'article' => (clone $published)->where('type', 'article')->count(),
            'event' => (clone $published)->where('type', 'event')->count(),
            'gallery' => (clone $published)->where('type', 'gallery')->count(),
            'learning' => (clone $published)->where('type', 'learning')->count(),
        ];

        $latestArticles = ContentItem::published()->where('type', 'article')->latest('published_at')->take(3)->get();
        $latestEvents = ContentItem::published()->where('type', 'event')->latest('published_at')->take(2)->get();
        $latestLearning = ContentItem::published()->where('type', 'learning')->latest('published_at')->take(2)->get();

        return view('pages.home', compact('counts', 'latestArticles', 'latestEvents', 'latestLearning'));
    }
}
