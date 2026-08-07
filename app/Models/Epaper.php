<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epaper extends Model
{
    use HasFactory;
    use ResolvesMediaUrl;

    protected $fillable = ['title', 'edition_date', 'cover_image', 'file_path', 'is_published'];

    protected $casts = [
        'edition_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->cover_image);
    }

    public function getFilePathUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->file_path);
    }

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_published', true);
    }
}