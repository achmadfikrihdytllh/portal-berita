<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Focus extends Model
{
    use HasFactory;

    protected $table = 'focuses';

    protected $fillable = ['title', 'slug', 'description', 'cover_image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Focus $focus) {
            if (empty($focus->slug)) {
                $focus->slug = Str::slug($focus->title);
            }
        });
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'focus_news')
            ->withPivot('order')
            ->orderBy('focus_news.order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}