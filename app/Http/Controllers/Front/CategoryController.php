<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $news = News::published()
            ->where('category_id', $category->id)
            ->with(['user'])
            ->latest('published_at')
            ->paginate(12);

        $subCategories = $category->children()->active()->get();

        return view('front.categories.show', compact('category', 'news', 'subCategories'));
    }
}
