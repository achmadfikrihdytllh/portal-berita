<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $headline = News::published()
            ->headline()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->first();

        $latest = News::published()
            ->with(['category', 'user'])
            ->when($headline, fn ($q) => $q->where('id', '!=', $headline->id))
            ->latest('published_at')
            ->take(8)
            ->get();

        $trending = News::published()
            ->with('category')
            ->trending()
            ->take(5)
            ->get();

        // Beberapa berita per kategori utama, untuk section "per kategori" di homepage
        $categorySections = Category::active()
            ->parents()
            ->orderBy('order')
            ->take(4)
            ->get()
            ->map(function (Category $category) {
                $category->setRelation(
                    'previewNews',
                    News::published()
                        ->with(['category', 'user'])
                        ->where('category_id', $category->id)
                        ->latest('published_at')
                        ->take(4)
                        ->get()
                );

                return $category;
            })
            ->filter(fn ($category) => $category->previewNews->isNotEmpty());

        return view('front.home', compact('headline', 'latest', 'trending', 'categorySections'));
    }
}