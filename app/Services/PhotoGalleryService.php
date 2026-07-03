<?php

namespace App\Services;

use App\Models\PhotoGallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoGalleryService
{
    public function create(array $data, int $userId): PhotoGallery
    {
        $data['user_id'] = $userId;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);

        $images = $data['images'] ?? [];
        $captions = $data['captions'] ?? [];
        unset($data['images'], $data['captions']);

        if (! empty($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('galleries/covers', 'public');
        }

        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $gallery = PhotoGallery::create($data);

        $this->attachImages($gallery, $images, $captions);

        return $gallery;
    }

    public function update(PhotoGallery $gallery, array $data): PhotoGallery
    {
        $images = $data['images'] ?? [];
        $captions = $data['captions'] ?? [];
        unset($data['images'], $data['captions']);

        if (! empty($data['cover_image'])) {
            if ($gallery->cover_image && Storage::disk('public')->exists($gallery->cover_image)) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $data['cover_image']->store('galleries/covers', 'public');
        } else {
            unset($data['cover_image']);
        }

        if (($data['status'] ?? $gallery->status) === 'published' && empty($gallery->published_at)) {
            $data['published_at'] = now();
        }

        $gallery->update($data);

        if (! empty($images)) {
            $this->attachImages($gallery, $images, $captions);
        }

        return $gallery;
    }

    public function delete(PhotoGallery $gallery): void
    {
        if ($gallery->cover_image && Storage::disk('public')->exists($gallery->cover_image)) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        foreach ($gallery->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $gallery->delete();
    }

    public function deleteImage(\App\Models\PhotoGalleryImage $image): void
    {
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
    }

    private function attachImages(PhotoGallery $gallery, array $images, array $captions): void
    {
        $maxOrder = $gallery->images()->max('order') ?? -1;

        foreach ($images as $i => $file) {
            $maxOrder++;
            $gallery->images()->create([
                'image_path' => $file->store('galleries/photos', 'public'),
                'caption'    => $captions[$i] ?? null,
                'order'      => $maxOrder,
            ]);
        }
    }
}