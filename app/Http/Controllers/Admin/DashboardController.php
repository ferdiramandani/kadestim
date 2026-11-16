<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $roleType = [
            'admin_event' => 'event',
            'admin_education' => 'article',
            'admin_gallery' => 'gallery',
            'admin_learning' => 'learning',
        ];

        $user = Auth::user();
        $allowedTypes = isset($roleType[$user->role])
            ? [$roleType[$user->role]]
            : ['article', 'event', 'gallery', 'learning'];

        $content = ContentItem::query()->whereIn('type', $allowedTypes);

        $counts = [
            'total' => (clone $content)->count(),
            'published' => (clone $content)->where('status', 'published')->count(),
            'draft' => (clone $content)->where('status', 'draft')->count(),
            'review' => (clone $content)->where('status', 'review')->count(),
        ];

        $byType = (clone $content)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $recentItems = (clone $content)->latest()->limit(6)->get();

        return view('admin.dashboard', compact('counts', 'byType', 'recentItems', 'allowedTypes'));
    }
}
