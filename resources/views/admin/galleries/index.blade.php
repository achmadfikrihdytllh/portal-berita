@extends('admin.layouts.app')

@section('title', 'Galeri Foto')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">Kumpulan foto jurnalistik/foto esai.</p>
        <a href="{{ route('admin.galleries.create') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Galeri
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-mono uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">Cover</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Penulis</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($galleries as $gallery)
                    <tr>
                        <td class="px-4 py-3">
                            @if($gallery->cover_image)
                                <img src="{{ asset('storage/'.$gallery->cover_image) }}" class="w-14 h-10 object-cover rounded">
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium max-w-xs truncate">{{ $gallery->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $gallery->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $gallery->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $gallery->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                                  onsubmit="return confirm('Yakin mau hapus galeri ini beserta semua fotonya?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada galeri foto.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $galleries->links() }}</div>

@endsection