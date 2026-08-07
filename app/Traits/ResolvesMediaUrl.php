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

        // 1. Sudah berupa URL absolut (Cloudinary secure_url dari NewsSeeder, dll)
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // 2. File ada di disk lokal 'public' (dari DummyContentSeeder)
        if (Storage::disk('public')->exists($value)) {
            return Storage::disk('public')->url($value);
        }

        // 3. Fallback: anggap ini public_id Cloudinary mentah, bentuk URL manual
        //    (tanpa API call, jadi tidak akan pernah throw NotFound)
        $cloudName = config('filesystems.disks.cloudinary.cloud');

        if (! $cloudName) {
            return Storage::disk('public')->url($value);
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