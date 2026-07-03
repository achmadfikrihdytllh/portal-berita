@extends('admin.layouts.app')

@section('title', 'E-koran')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">Edisi cetak digital harian dalam format PDF.</p>
        <a href="{{ route('admin.epapers.create') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Edisi
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-mono uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">Cover</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Tanggal Edisi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($epapers as $epaper)
                    <tr>
                        <td class="px-4 py-3">
                            @if($epaper->cover_image)
                                <img src="{{ asset('storage/'.$epaper->cover_image) }}" class="w-10 h-14 object-cover rounded">
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $epaper->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $epaper->edition_date->translatedFormat('d F Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $epaper->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $epaper->is_published ? 'Terbit' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.epapers.edit', $epaper) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.epapers.destroy', $epaper) }}" method="POST"
                                  onsubmit="return confirm('Yakin mau hapus edisi ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada edisi e-koran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $epapers->links() }}</div>

@endsection