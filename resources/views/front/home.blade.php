@extends('front.layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- ============ HERO: headline besar + daftar breaking ============ --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10 border-b border-rule">
        @if($headline)
            <div class="lg:col-span-2">
                <x-news-card :news="$headline" size="large" />
            </div>
        @endif

        <div>
            <h2 class="font-mono text-xs uppercase tracking-wider text-ink/50 pb-3 border-b border-rule mb-4">
                Terpopuler
            </h2>
            <ol class="space-y-4">
                @forelse($trending as $i => $item)
                    <li class="flex gap-3">
                        <span class="font-display text-2xl text-ink/20 leading-none">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('news.show', $item) }}" class="text-sm font-medium leading-snug hover:text-brand-primary">
                            {{ $item->title }}
                        </a>
                    </li>
                @empty
                    <li class="text-sm text-ink/50">Belum ada berita.</li>
                @endforelse
            </ol>
        </div>
    </section>

    {{-- ============ BERITA TERBARU ============ --}}
    <section class="py-10 border-b border-rule">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-display text-2xl font-semibold">Berita Terbaru</h2>
            <a href="{{ route('news.index') }}" class="text-sm font-mono text-brand-primary hover:underline">Lihat semua &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
            @foreach($latest as $item)
                <x-news-card :news="$item" />
            @endforeach
        </div>
    </section>

    {{-- ============ SECTION PER KATEGORI ============ --}}
    @foreach($categorySections as $category)
        <section class="py-10 {{ !$loop->last ? 'border-b border-rule' : '' }}">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-2xl font-semibold">{{ $category->name }}</h2>
                <a href="{{ route('categories.show', $category) }}" class="text-sm font-mono text-brand-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
                @foreach($category->previewNews as $item)
                    <x-news-card :news="$item" />
                @endforeach
            </div>
        </section>
    @endforeach

@endsection
