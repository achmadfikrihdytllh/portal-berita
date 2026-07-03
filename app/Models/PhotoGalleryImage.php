<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoGalleryImage extends Model
{
    protected $fillable = ['photo_gallery_id', 'image_path', 'caption', 'order'];

    public function gallery()
    {
        return $this->belongsTo(PhotoGallery::class, 'photo_gallery_id');
    }
}