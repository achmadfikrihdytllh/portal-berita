@extends('front.layouts.app')

@section('title', 'Semua Berita')

@section('content')
    <h1 class="font-display text-3xl font-semibold mb-8">Semua Berita</h1>

    @if(request('tag'))
        <p class="text-sm text-ink/60 mb-6 font-mono">Tag: #{{ request('tag') }}</p>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
        @forelse($news as $item)
            <x-news-card :news="$item" />
        @empty
            <p class="text-ink/50 col-span-full">Belum ada berita.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $news->links() }}
    </div>
@endsection
