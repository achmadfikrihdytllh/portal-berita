<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ResolvesMediaUrl
{
    protected function resolveMediaUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return str_starts_with($value, 'http://') || str_starts_with($value, 'https://')
            ? $value
            : Storage::url($value);
    }
}