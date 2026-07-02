@csrf

<div class="bg-white border border-slate-200 rounded-lg p-5 max-w-xl space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Slug (opsional, dibuat otomatis jika kosong)</label>
        <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm font-mono">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Kategori Induk (opsional)</label>
        <select name="parent_id" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
            <option value="">— Tidak ada (kategori utama) —</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Deskripsi</label>
        <textarea name="description" rows="2"
                  class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
        Aktifkan kategori ini
    </label>

    <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded hover:bg-blue-700">
        {{ isset($category) ? 'Simpan Perubahan' : 'Simpan Kategori' }}
    </button>
</div>
