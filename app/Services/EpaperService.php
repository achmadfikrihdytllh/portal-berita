<?php

namespace App\Services;

use App\Models\Epaper;
use Illuminate\Support\Facades\Storage;

class EpaperService
{
    public function create(array $data): Epaper
    {
        if (! empty($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('epapers/covers', 'public');
        }

        if (! empty($data['file_path'])) {
            $data['file_path'] = $data['file_path']->store('epapers/files', 'public');
        }

        return Epaper::create($data);
    }

    public function update(Epaper $epaper, array $data): Epaper
    {
        if (! empty($data['cover_image'])) {
            $this->deleteFile($epaper->cover_image);
            $data['cover_image'] = $data['cover_image']->store('epapers/covers', 'public');
        } else {
            unset($data['cover_image']);
        }

        if (! empty($data['file_path'])) {
            $this->deleteFile($epaper->file_path);
            $data['file_path'] = $data['file_path']->store('epapers/files', 'public');
        } else {
            unset($data['file_path']);
        }

        $epaper->update($data);

        return $epaper;
    }

    public function delete(Epaper $epaper): void
    {
        $this->deleteFile($epaper->cover_image);
        $this->deleteFile($epaper->file_path);
        $epaper->delete();
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}