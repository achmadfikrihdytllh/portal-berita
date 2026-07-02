@extends('admin.layouts.app')

@section('title', 'Berita')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..."
                   class="border border-slate-300 rounded px-3 py-2 text-sm w-56">
            <select name="status" class="border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">Semua status</option>
                @foreach(['draft', 'published', 'archived'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="text-sm px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Filter</button>
        </form>

        <a href="{{ route('admin.news.create') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Berita
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-mono uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Penulis</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Views</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($news as $item)
                <tr>
                    <td class="px-4 py-3 font-medium max-w-xs truncate">{{ $item->title }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $item->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $item->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ number_format($item->views) }}</td>
                    <td class="px-4 py-3 text-right space-x-3">
                        <a href="{{ route('admin.news.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST"
                            onsubmit="return confirm('Yakin mau hapus berita ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $news->links() }}
    </div>

@endsection
