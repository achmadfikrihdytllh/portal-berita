@extends('front.layouts.app')

@section('title', 'Cari: ' . $keyword)

@section('content')
    <h1 class="font-display text-3xl font-semibold mb-2">Hasil Pencarian</h1>
    <p class="text-ink/60 mb-8">
        Menampilkan {{ $news->total() }} hasil untuk "<strong>{{ $keyword }}</strong>"
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
        @forelse($news as $item)
            <x-news-card :news="$item" />
        @empty
            <p class="text-ink/50 col-span-full">Tidak ada berita yang cocok dengan pencarianmu.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $news->links() }}
    </div>
@endsection
