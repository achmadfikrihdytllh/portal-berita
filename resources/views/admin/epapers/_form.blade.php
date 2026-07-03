<div class="max-w-lg space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Judul Edisi</label>
        <input type="text" name="title" value="{{ old('title', $epaper->title ?? '') }}"
               placeholder="Contoh: Edisi Kamis, 3 Juli 2026"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm" required>
        @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Tanggal Edisi</label>
        <input type="date" name="edition_date"
               value="{{ old('edition_date', isset($epaper) ? $epaper->edition_date->format('Y-m-d') : '') }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm" required>
        @error('edition_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Cover (gambar halaman depan)</label>
        @isset($epaper)
            @if($epaper->cover_image)
                <img src="{{ asset('storage/'.$epaper->cover_image) }}" class="w-24 rounded mb-2">
            @endif
        @endisset
        <input type="file" name="cover_image" accept="image/*" class="w-full text-sm">
        @error('cover_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">File PDF Edisi</label>
        @isset($epaper)
            <p class="text-xs text-slate-500 mb-1">
                File saat ini: <a href="{{ asset('storage/'.$epaper->file_path) }}" target="_blank" class="text-blue-600 hover:underline">lihat PDF</a>
            </p>
        @endisset
        <input type="file" name="file_path" accept="application/pdf" class="w-full text-sm">
        @error('file_path') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $epaper->is_published ?? true))>
        Terbitkan sekarang
    </label>

    <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
        Simpan
    </button>
</div>