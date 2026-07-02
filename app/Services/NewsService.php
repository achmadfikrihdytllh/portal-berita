<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsService
{
    /**
     * Buat berita baru dari data yang sudah divalidasi.
     */
    public function create(array $data, int $userId): News
    {
        $data['user_id'] = $userId;
        $data['slug'] = $this->uniqueSlug($data['title']);

        if (! empty($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        }

        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $news = News::create($data);

        if (! empty($tags)) {
            $news->tags()->sync($tags);
        }

        return $news;
    }

    /**
     * Update berita yang sudah ada.
     */
    public function update(News $news, array $data): News
    {
        if (! empty($data['title']) && $data['title'] !== $news->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $news->id);
        }

        if (! empty($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
            $this->deleteThumbnail($news->thumbnail);
            $data['thumbnail'] = $this->storeThumbnail($data['thumbnail']);
        } else {
            unset($data['thumbnail']); // jangan timpa kalau tidak upload baru
        }

        if (($data['status'] ?? $news->status) === 'published' && empty($news->published_at)) {
            $data['published_at'] = now();
        }

        $tags = $data['tags'] ?? null;
        unset($data['tags']);

        $news->update($data);

        if ($tags !== null) {
            $news->tags()->sync($tags);
        }

        return $news;
    }

    public function delete(News $news): void
    {
        $this->deleteThumbnail($news->thumbnail);
        $news->delete();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while (
            News::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function storeThumbnail(UploadedFile $file): string
    {
        return $file->store('news/thumbnails', 'public');
    }

    private function deleteThumbnail(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
