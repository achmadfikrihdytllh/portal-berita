<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\View\View;

class KanalController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->parents()
            ->withCount(['news' => fn ($q) => $q->published()])
            ->orderBy('order')
            ->get();

        $totalArticles = News::published()->count();
        $totalChannels = $categories->count();

        return view('front.kanal.index', compact('categories', 'totalArticles', 'totalChannels'));
    }
}