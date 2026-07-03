<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epaper extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'edition_date', 'cover_image', 'file_path', 'is_published'];

    protected $casts = [
        'edition_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_published', true);
    }
}