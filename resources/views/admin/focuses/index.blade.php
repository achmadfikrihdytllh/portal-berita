@extends('admin.layouts.app')

@section('title', 'Fokus')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">Kumpulan liputan khusus dari beberapa berita terkait satu topik besar.</p>
        <a href="{{ route('admin.focuses.create') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Fokus
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-mono uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Jumlah Berita</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($focuses as $focus)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $focus->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $focus->news_count }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $focus->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $focus->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.focuses.edit', $focus) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.focuses.destroy', $focus) }}" method="POST"
                                  onsubmit="return confirm('Yakin mau hapus fokus ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada fokus.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $focuses->links() }}</div>

@endsection