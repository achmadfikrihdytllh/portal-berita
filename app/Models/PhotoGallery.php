<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PhotoGallery extends Model
{
    use HasFactory;

    protected $table = 'photo_galleries';

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'cover_image', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (PhotoGallery $gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title) . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(PhotoGalleryImage::class)->orderBy('order');
    }

   public function scopePublished(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}