<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Simpan banyak setting sekaligus dari form.
     * $data contoh: ['site_name' => '...', 'color_primary' => '#2563eb', 'logo' => UploadedFile, ...]
     */
    public function saveAppearance(array $data): void
    {
        $colorKeys = [
            'color_primary', 'color_secondary',
            'color_header_bg', 'color_header_text',
            'color_footer_bg', 'color_footer_text',
        ];

        foreach ($colorKeys as $key) {
            if (array_key_exists($key, $data)) {
                Setting::set($key, $data[$key], type: 'color', group: 'appearance');
            }
        }

        if (! empty($data['site_name'])) {
            Setting::set('site_name', $data['site_name'], type: 'text', group: 'general');
        }

        if (array_key_exists('site_tagline', $data)) {
            Setting::set('site_tagline', $data['site_tagline'], type: 'text', group: 'general');
        }

        if (! empty($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $this->replaceImage('logo', $data['logo']);
        }

        if (! empty($data['favicon']) && $data['favicon'] instanceof UploadedFile) {
            $this->replaceImage('favicon', $data['favicon']);
        }
    }

    private function replaceImage(string $key, UploadedFile $file): void
    {
        $oldPath = Setting::get($key);

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $newPath = $file->store('branding', 'public');

        Setting::set($key, $newPath, type: 'image', group: 'appearance');
    }
}
