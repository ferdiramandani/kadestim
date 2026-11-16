<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $counts = [
            'total' => ContentItem::count(),
            'published' => ContentItem::where('status', 'published')->count(),
            'draft' => ContentItem::where('status', 'draft')->count(),
            'review' => ContentItem::where('status', 'review')->count(),
        ];

        $byType = ContentItem::query()
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $recentItems = ContentItem::query()->latest()->limit(6)->get();

        return view('admin.dashboard', compact('counts', 'byType', 'recentItems'));
    }
}
