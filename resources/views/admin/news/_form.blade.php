@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">

        <div>
            <label class="block text-sm font-medium mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" required
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ringkasan</label>
            <textarea name="excerpt" rows="2"
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Konten</label>
            <textarea name="content" rows="14" required
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm font-mono">{{ old('content', $news->content ?? '') }}</textarea>
            @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <details class="border border-slate-200 rounded p-4">
            <summary class="text-sm font-medium cursor-pointer">SEO (opsional)</summary>
            <div class="mt-3 space-y-3">
                <input type="text" name="meta_title" placeholder="Meta title"
                       value="{{ old('meta_title', $news->meta_title ?? '') }}"
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <textarea name="meta_description" rows="2" placeholder="Meta description"
                          class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('meta_description', $news->meta_description ?? '') }}</textarea>
            </div>
        </details>
    </div>

    <div class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                    @foreach(['draft' => 'Draft', 'published' => 'Publikasikan', 'archived' => 'Arsipkan'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $news->status ?? 'draft') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $news->category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tag</label>
                <select name="tags[]" multiple class="w-full border border-slate-300 rounded px-3 py-2 text-sm h-28">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', isset($news) ? $news->tags->pluck('id')->toArray() : [])))>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_headline" value="1" @checked(old('is_headline', $news->is_headline ?? false))>
                Jadikan headline utama
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_breaking" value="1" @checked(old('is_breaking', $news->is_breaking ?? false))>
                Tandai sebagai breaking news
            </label>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <label class="block text-sm font-medium mb-1">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm border border-slate-300 rounded px-3 py-2">
            @if(!empty($news?->thumbnail))
                <img src="{{ Storage::url($news->thumbnail) }}" class="mt-2 rounded aspect-video object-cover">
            @endif
            @error('thumbnail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded hover:bg-blue-700">
            {{ isset($news) ? 'Simpan Perubahan' : 'Simpan Berita' }}
        </button>
    </div>
</div>
