<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Model;

class PhotoGalleryImage extends Model
{
    use ResolvesMediaUrl;

    protected $fillable = ['photo_gallery_id', 'image_path', 'caption', 'order'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image_path);
    }

    public function gallery()
    {
        return $this->belongsTo(PhotoGallery::class, 'photo_gallery_id');
    }
}