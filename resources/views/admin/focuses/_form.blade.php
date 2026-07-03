<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Judul Fokus</label>
            <input type="text" name="title" value="{{ old('title', $focus->title ?? '') }}"
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm" required>
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('description', $focus->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Pilih Berita untuk Fokus Ini</label>
            <p class="text-xs text-slate-500 mb-2">Tahan Ctrl (Windows) / Cmd (Mac) untuk pilih lebih dari satu. Urutan pilihan = urutan tampil.</p>
            <select name="news_ids[]" multiple size="10" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                @php $selected = old('news_ids', isset($focus) ? $focus->news->pluck('id')->toArray() : []); @endphp
                @foreach($newsList as $item)
                    <option value="{{ $item->id }}" @selected(in_array($item->id, $selected))>{{ $item->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Cover</label>
            @isset($focus)
                @if($focus->cover_image)
                    <img src="{{ asset('storage/'.$focus->cover_image) }}" class="w-full rounded mb-2 aspect-video object-cover">
                @endif
            @endisset
            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $focus->is_active ?? true))>
            Tampilkan di situs
        </label>

        <button type="submit" class="w-full bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
    </div>
</div>