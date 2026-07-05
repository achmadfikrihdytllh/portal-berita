@extends('admin.layouts.app')

@section('title', 'Komentar')

@section('content')

    <div class="mb-6">
        <form method="GET" class="flex gap-2">
            <select name="status" onchange="this.form.submit()" class="border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">Semua status</option>
                @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'spam' => 'Spam'] as $val => $label)
                    <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($comments as $comment)
            <div class="bg-white border border-slate-200 rounded-lg p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm">
                            <strong>{{ $comment->name }}</strong>
                            <span class="text-slate-400 text-xs font-mono ml-1">{{ $comment->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-sm text-slate-600 mt-1">{{ $comment->content }}</p>

                        @if($comment->news)
                            <a href="{{ route('news.show', $comment->news) }}" target="_blank"
                               class="text-xs text-blue-600 hover:underline mt-1 inline-block">
                                pada: {{ $comment->news->title }}
                            </a>
                        @else
                            <p class="text-xs text-slate-400 mt-1 italic">Berita terkait sudah dihapus</p>
                        @endif
                    </div>

                    <div class="shrink-0 flex flex-col items-end gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ match($comment->status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'spam'     => 'bg-red-100 text-red-700',
                                default    => 'bg-yellow-100 text-yellow-700',
                            } }}">
                            {{ $comment->status }}
                        </span>

                        <div class="flex gap-2 text-xs">
                            @if($comment->status !== 'approved')
                                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:underline">Setujui</button>
                                </form>
                            @endif
                            @if($comment->status !== 'spam')
                                <form action="{{ route('admin.comments.spam', $comment) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-orange-600 hover:underline">Spam</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                  onsubmit="return confirm('Hapus komentar ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Tidak ada komentar.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $comments->links() }}</div>

@endsection