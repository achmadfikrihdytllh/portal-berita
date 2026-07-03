<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleries = PhotoGallery::published()
            ->with(['user', 'category'])
            ->latest('published_at')
            ->paginate(12);

        return view('front.galleries.index', compact('galleries'));
    }

    public function show(PhotoGallery $gallery): View
    {
        abort_unless(
            $gallery->status === 'published' && $gallery->published_at?->isPast(),
            404
        );

        $gallery->load(['images', 'user', 'category']);

        return view('front.galleries.show', compact('gallery'));
    }
}