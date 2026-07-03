<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Judul Galeri</label>
            <input type="text" name="title" value="{{ old('title', $gallery->title ?? '') }}"
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm" required>
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('description', $gallery->description ?? '') }}</textarea>
        </div>

        @isset($gallery)
            @if($gallery->images->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium mb-2">Foto yang Sudah Ada ({{ $gallery->images->count() }})</label>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($gallery->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/'.$image->image_path) }}" class="w-full aspect-square object-cover rounded border">
                                @if($image->caption)
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $image->caption }}</p>
                                @endif
                                <form action="{{ route('admin.galleries.images.destroy', $image) }}" method="POST"
                                      onsubmit="return confirm('Hapus foto ini?')"
                                      class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white text-xs w-6 h-6 rounded-full">&times;</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endisset

        <div>
            <label class="block text-sm font-medium mb-1">
                {{ isset($gallery) ? 'Tambah Foto Baru' : 'Upload Foto' }}
            </label>
            <input type="file" name="images[]" accept="image/*" multiple class="w-full text-sm">
            <p class="text-xs text-slate-500 mt-1">Bisa pilih beberapa foto sekaligus. Maks 3MB per foto.</p>
            @error('images.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Kategori (opsional)</label>
            <select name="category_id" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">- Tanpa kategori -</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $gallery->category_id ?? null) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Cover Galeri</label>
            @isset($gallery)
                @if($gallery->cover_image)
                    <img src="{{ asset('storage/'.$gallery->cover_image) }}" class="w-full rounded mb-2 aspect-video object-cover">
                @endif
            @endisset
            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="draft" @selected(old('status', $gallery->status ?? 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $gallery->status ?? '') === 'published')>Publish</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
    </div>
</div>