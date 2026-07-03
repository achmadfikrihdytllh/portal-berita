<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePhotoGalleryRequest;
use App\Models\Category;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryImage;
use App\Services\PhotoGalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotoGalleryController extends Controller
{
    public function __construct(private PhotoGalleryService $galleryService)
    {
    }

    public function index(Request $request): View
    {
        $query = PhotoGallery::with(['user', 'category'])->latest();

        if ($request->user()->role === 'author') {
            $query->where('user_id', $request->user()->id);
        }

        $galleries = $query->paginate(15);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.galleries.create', compact('categories'));
    }

    public function store(StorePhotoGalleryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['cover_image'] = $request->file('cover_image');
        $data['images'] = $request->file('images', []);

        $this->galleryService->create($data, $request->user()->id);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri foto berhasil dibuat.');
    }

    public function edit(PhotoGallery $gallery): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $gallery->load('images');

        return view('admin.galleries.edit', compact('gallery', 'categories'));
    }

    public function update(StorePhotoGalleryRequest $request, PhotoGallery $gallery): RedirectResponse
    {
        $data = $request->validated();
        $data['cover_image'] = $request->file('cover_image');
        $data['images'] = $request->file('images', []);

        $this->galleryService->update($gallery, $data);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri foto berhasil diperbarui.');
    }

    public function destroy(PhotoGallery $gallery): RedirectResponse
    {
        $this->galleryService->delete($gallery);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri foto berhasil dihapus.');
    }

    public function destroyImage(PhotoGalleryImage $image): RedirectResponse
    {
        $galleryId = $image->photo_gallery_id;
        $this->galleryService->deleteImage($image);

        return redirect()
            ->route('admin.galleries.edit', $galleryId)
            ->with('success', 'Foto berhasil dihapus dari galeri.');
    }
}