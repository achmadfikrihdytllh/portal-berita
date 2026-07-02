<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\News;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_news'       => News::count(),
            'published_news'   => News::where('status', 'published')->count(),
            'draft_news'       => News::where('status', 'draft')->count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
        ];

        $latestNews = News::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $mostViewed = News::published()
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestNews', 'mostViewed'));
    }
}
