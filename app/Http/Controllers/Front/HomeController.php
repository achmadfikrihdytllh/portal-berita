<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\PhotoGallery;
use App\Models\Tag;
use Illuminate\View\View;
use App\Models\Epaper;

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

        // Feed panjang untuk section "Jelajah Berita" (scroll independen)
        $jelajah = News::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(20)
            ->get();

        // Galeri foto terbaru untuk carousel "Galeri Foto"
        $galleries = PhotoGallery::published()
            ->with('category')
            ->latest('published_at')
            ->take(8)
            ->get();

        // Edisi E-koran terbaru untuk carousel "E-koran"
        $epapers = Epaper::published()
            ->latest('edition_date')
            ->take(8)
            ->get();

        // Tag terpopuler (berdasarkan jumlah berita published yang memakainya)
        $popularTags = Tag::query()
            ->withCount(['news' => fn ($q) => $q->published()])
            ->having('news_count', '>', 0)
            ->orderByDesc('news_count')
            ->take(10)
            ->get();

        // Kategori untuk section "Sorotan Kategori" (3 kolom) + section lama di bawahnya
        $categorySections = Category::active()
            ->parents()
            ->orderBy('order')
            ->take(6)
            ->get()
            ->map(function (Category $category) {
                $category->setRelation(
                    'previewNews',
                    News::published()
                        ->with(['category', 'user'])
                        ->where('category_id', $category->id)
                        ->latest('published_at')
                        ->take(5)
                        ->get()
                );

                return $category;
            })
            ->filter(fn ($category) => $category->previewNews->isNotEmpty());

        $spotlightCategories = $categorySections->take(3);
        $remainingCategories = $categorySections->slice(3);

        return view('front.home', compact(
            'headline', 'latest', 'trending', 'jelajah',
            'galleries', 'epapers', 'popularTags', 'spotlightCategories', 'remainingCategories'
        ));
    }
}