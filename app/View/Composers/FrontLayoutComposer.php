<?php

namespace App\View\Composers;

use App\Models\News;
use Illuminate\View\View;

class FrontLayoutComposer
{
    public function compose(View $view): void
    {
        $view->with('breakingTicker', News::published()
            ->breaking()
            ->latest('published_at')
            ->take(6)
            ->get(['id', 'title', 'slug'])
        );
    }
}
