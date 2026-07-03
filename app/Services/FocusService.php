<?php

namespace App\Services;

use App\Models\Focus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FocusService
{
    public function create(array $data): Focus
    {
        $data['slug'] = $this->uniqueSlug($data['title']);
        $newsIds = $data['news_ids'] ?? [];
        unset($data['news_ids']);

        if (! empty($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $data['cover_image'] = $data['cover_image']->store('focuses', 'public');
        }

        $focus = Focus::create($data);

        if (! empty($newsIds)) {
            $sync = [];
            foreach ($newsIds as $order => $newsId) {
                $sync[$newsId] = ['order' => $order];
            }
            $focus->news()->sync($sync);
        }

        return $focus;
    }

    public function update(Focus $focus, array $data): Focus
    {
        if (! empty($data['title']) && $data['title'] !== $focus->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $focus->id);
        }

        $newsIds = $data['news_ids'] ?? null;
        unset($data['news_ids']);

        if (! empty($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            if ($focus->cover_image && Storage::disk('public')->exists($focus->cover_image)) {
                Storage::disk('public')->delete($focus->cover_image);
            }
            $data['cover_image'] = $data['cover_image']->store('focuses', 'public');
        } else {
            unset($data['cover_image']);
        }

        $focus->update($data);

        if ($newsIds !== null) {
            $sync = [];
            foreach ($newsIds as $order => $newsId) {
                $sync[$newsId] = ['order' => $order];
            }
            $focus->news()->sync($sync);
        }

        return $focus;
    }

    public function delete(Focus $focus): void
    {
        if ($focus->cover_image && Storage::disk('public')->exists($focus->cover_image)) {
            Storage::disk('public')->delete($focus->cover_image);
        }
        $focus->delete();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while (
            Focus::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}