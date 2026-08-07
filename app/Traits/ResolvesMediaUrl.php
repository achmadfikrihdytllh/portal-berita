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
            : $this->resolveStoredMediaUrl($value);
    }

    protected function resolveStoredMediaUrl(string $value): string
    {
        if (Storage::disk('public')->exists($value)) {
            return Storage::url($value);
        }

        $cloudName = config('filesystems.disks.cloudinary.cloud');

        if (! $cloudName) {
            return Storage::url($value);
        }

        $resourceType = str_ends_with(strtolower($value), '.pdf') ? 'raw' : 'image';

        return sprintf(
            'https://res.cloudinary.com/%s/%s/upload/%s',
            $cloudName,
            $resourceType,
            ltrim($value, '/')
        );
    }
}