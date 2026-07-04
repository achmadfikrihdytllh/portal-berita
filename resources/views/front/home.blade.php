@extends('front.layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- ============ HERO: headline besar + Terpopuler ============ --}}
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
                    <li class="flex gap-3 items-start">
                        <span class="font-display text-2xl text-ink/20 leading-none shrink-0 pt-1">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>

                        <a href="{{ route('news.show', $item) }}" class="shrink-0 block w-16 h-12 rounded overflow-hidden bg-ink/5">
                            @if($item->thumbnail)
                                <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ink/30 text-[9px] font-mono text-center px-1">
                                    {{ $item->category->name ?? 'Berita' }}
                                </div>
                            @endif
                        </a>

                        <div class="min-w-0">
                            <a href="{{ route('news.show', $item) }}" class="text-sm font-medium leading-snug hover:text-brand-primary line-clamp-2">
                                {{ $item->title }}
                            </a>
                            <div class="mt-1 flex items-center gap-1 text-[11px] text-ink/40 font-mono">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                {{ number_format($item->views) }}
                            </div>
                        </div>
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

    {{-- ============ SOROTAN KATEGORI: 3 kolom, tiap kolom 1 featured + list ============ --}}
    @if($spotlightCategories->isNotEmpty())
        <section class="py-10 border-b border-rule">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-10">
                @foreach($spotlightCategories as $category)
                    @php
                        $featured = $category->previewNews->first();
                        $rest = $category->previewNews->slice(1, 4);
                    @endphp

                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2 border-b-2 border-brand-primary">
                            <a href="{{ route('categories.show', $category) }}" class="font-display text-lg font-bold uppercase tracking-wide hover:text-brand-primary">
                                {{ $category->name }}
                            </a>
                            <a href="{{ route('categories.show', $category) }}" class="text-ink/40 hover:text-brand-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>

                        @if($featured)
                            <a href="{{ route('news.show', $featured) }}" class="group block mb-4">
                                <div class="relative overflow-hidden rounded-lg bg-ink/5 aspect-[16/10]">
                                    @if($featured->thumbnail)
                                        <img src="{{ Storage::url($featured->thumbnail) }}" alt="{{ $featured->title }}"
                                             class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-ink/30 font-display text-sm">
                                            {{ $category->name }}
                                        </div>
                                    @endif
                                </div>
                                <h3 class="mt-3 font-display font-semibold leading-snug group-hover:text-brand-primary">
                                    {{ $featured->title }}
                                </h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-ink/50 font-mono">
                                    <span>{{ $featured->user->name ?? 'Redaksi' }}</span>
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ number_format($featured->views) }}
                                    </span>
                                </div>
                            </a>
                        @endif

                        <ul class="space-y-3">
                            @foreach($rest as $item)
                                <li>
                                    <a href="{{ route('news.show', $item) }}" class="text-sm font-medium leading-snug hover:text-brand-primary line-clamp-2">
                                        {{ $item->title }}
                                    </a>
                                    <div class="mt-0.5 text-[11px] text-ink/40 font-mono">
                                        {{ $item->published_at?->diffForHumans() }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ GALERI FOTO: carousel horizontal, scrollbar tersembunyi + tombol panah hologram ============ --}}
    @if($galleries->isNotEmpty())
        <section class="py-10 border-b border-rule">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-2xl font-semibold">Galeri Foto</h2>
                <a href="{{ route('galleries.index') }}" class="text-sm font-mono text-brand-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            <div class="relative group/carousel">
                <button type="button" aria-label="Geser kiri"
                        onclick="document.getElementById('galleryScroll').scrollBy({left:-300,behavior:'smooth'})"
                        class="hidden md:flex absolute left-0 top-0 bottom-0 z-10 w-14 items-center justify-start pl-1
                               bg-gradient-to-r from-paper/90 via-paper/40 to-transparent
                               opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300">
                    <span class="w-9 h-9 rounded-full bg-white/40 backdrop-blur-md border border-white/60 shadow-lg
                                 flex items-center justify-center text-ink/70 hover:bg-white/70 hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </span>
                </button>

                <button type="button" aria-label="Geser kanan"
                        onclick="document.getElementById('galleryScroll').scrollBy({left:300,behavior:'smooth'})"
                        class="hidden md:flex absolute right-0 top-0 bottom-0 z-10 w-14 items-center justify-end pr-1
                               bg-gradient-to-l from-paper/90 via-paper/40 to-transparent
                               opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300">
                    <span class="w-9 h-9 rounded-full bg-white/40 backdrop-blur-md border border-white/60 shadow-lg
                                 flex items-center justify-center text-ink/70 hover:bg-white/70 hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </button>

                <div class="flex gap-5 overflow-x-auto pb-2 snap-x snap-mandatory no-scrollbar" id="galleryScroll">
                    @foreach($galleries as $gallery)
                        <a href="{{ route('galleries.show', $gallery) }}"
                           class="group shrink-0 w-56 snap-start">
                            <div class="relative overflow-hidden rounded-lg bg-ink/5 aspect-[4/3]">
                                @if($gallery->cover_image)
                                    <img src="{{ Storage::url($gallery->cover_image) }}" alt="{{ $gallery->title }}"
                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-ink/30 font-display text-sm">
                                        Galeri
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 bg-black/60 text-white text-[10px] font-mono px-1.5 py-0.5 rounded flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                    {{ $gallery->images->count() }}
                                </span>
                            </div>
                            <h3 class="mt-2 text-sm font-medium leading-snug group-hover:text-brand-primary line-clamp-2">
                                {{ $gallery->title }}
                            </h3>
                            <div class="mt-0.5 text-[11px] text-ink/40 font-mono">
                                {{ $gallery->published_at?->diffForHumans() }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ E-KORAN: carousel horizontal, scrollbar tersembunyi + tombol panah hologram ============ --}}
    @if($epapers->isNotEmpty())
        <section class="py-10 border-b border-rule">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-2xl font-semibold">E-koran</h2>
                <a href="{{ route('epapers.index') }}" class="text-sm font-mono text-brand-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            <div class="relative group/carousel">
                <button type="button" aria-label="Geser kiri"
                        onclick="document.getElementById('epaperScroll').scrollBy({left:-260,behavior:'smooth'})"
                        class="hidden md:flex absolute left-0 top-0 bottom-0 z-10 w-14 items-center justify-start pl-1
                               bg-gradient-to-r from-paper/90 via-paper/40 to-transparent
                               opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300">
                    <span class="w-9 h-9 rounded-full bg-white/40 backdrop-blur-md border border-white/60 shadow-lg
                                 flex items-center justify-center text-ink/70 hover:bg-white/70 hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </span>
                </button>

                <button type="button" aria-label="Geser kanan"
                        onclick="document.getElementById('epaperScroll').scrollBy({left:260,behavior:'smooth'})"
                        class="hidden md:flex absolute right-0 top-0 bottom-0 z-10 w-14 items-center justify-end pr-1
                               bg-gradient-to-l from-paper/90 via-paper/40 to-transparent
                               opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300">
                    <span class="w-9 h-9 rounded-full bg-white/40 backdrop-blur-md border border-white/60 shadow-lg
                                 flex items-center justify-center text-ink/70 hover:bg-white/70 hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </button>

                <div class="flex gap-5 overflow-x-auto pb-2 snap-x snap-mandatory no-scrollbar" id="epaperScroll">
                    @foreach($epapers as $epaper)
                        <a href="{{ Storage::url($epaper->file_path) }}" target="_blank" rel="noopener"
                           class="group shrink-0 w-40 snap-start">
                            <div class="relative overflow-hidden rounded-lg bg-ink/5 aspect-[3/4] shadow-sm">
                                @if($epaper->cover_image)
                                    <img src="{{ Storage::url($epaper->cover_image) }}" alt="{{ $epaper->title }}"
                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-ink/30 font-display text-sm text-center px-2">
                                        {{ $epaper->title }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="mt-2 text-sm font-medium leading-snug group-hover:text-brand-primary line-clamp-2">
                                {{ $epaper->title }}
                            </h3>
                            <div class="mt-0.5 text-[11px] text-ink/40 font-mono">
                                {{ \Carbon\Carbon::parse($epaper->edition_date)->diffForHumans() }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ TOPIK PILIHAN: tag terpopuler ============ --}}
    @if($popularTags->isNotEmpty())
        <section class="py-10 border-b border-rule">
            <h2 class="font-display text-2xl font-semibold mb-6">Topik Pilihan</h2>

            <div class="flex flex-wrap gap-3">
                @foreach($popularTags as $tag)
                    <a href="{{ route('tags.show', $tag) }}"
                       class="flex items-center gap-1.5 bg-ink/5 hover:bg-brand-primary hover:text-white transition rounded-full px-4 py-2 text-sm font-medium">
                        <span class="text-brand-primary text-xs group-hover:text-white">#</span>
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ SECTION SISA KATEGORI (gaya lama, grid biasa) ============ --}}
    @foreach($remainingCategories as $category)
        <section class="py-10 border-b border-rule">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-2xl font-semibold">{{ $category->name }}</h2>
                <a href="{{ route('categories.show', $category) }}" class="text-sm font-mono text-brand-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
                @foreach($category->previewNews->take(4) as $item)
                    <x-news-card :news="$item" />
                @endforeach
            </div>
        </section>
    @endforeach

    {{-- ============ JELAJAH BERITA: list scroll independen (scrollbar tipis) + sidebar Terpopuler ============ --}}
    <section class="py-10">
        <h2 class="font-display text-2xl font-semibold mb-6">Jelajah Berita</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 max-h-[700px] overflow-y-auto pr-2 space-y-6 thin-scrollbar">
                @foreach($jelajah as $item)
                    <div class="flex gap-4 pb-6 border-b border-rule last:border-b-0">
                        <a href="{{ route('news.show', $item) }}" class="shrink-0 block w-28 h-20 sm:w-36 sm:h-24 rounded overflow-hidden bg-ink/5">
                            @if($item->thumbnail)
                                <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ink/30 text-[10px] font-mono text-center px-1">
                                    {{ $item->category->name ?? 'Berita' }}
                                </div>
                            @endif
                        </a>

                        <div class="min-w-0">
                            <a href="{{ route('news.show', $item) }}" class="font-display font-semibold leading-snug hover:text-brand-primary line-clamp-2">
                                {{ $item->title }}
                            </a>
                            @if($item->excerpt)
                                <p class="mt-1 text-sm text-ink/60 leading-relaxed line-clamp-2 hidden sm:block">
                                    {{ $item->excerpt }}
                                </p>
                            @endif
                            <div class="mt-1.5 flex items-center gap-1 text-[11px] text-ink/40 font-mono">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $item->published_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lg:sticky lg:top-24 self-start">
                <h3 class="font-mono text-xs uppercase tracking-wider text-ink/50 pb-3 border-b border-rule mb-4">
                    Terpopuler
                </h3>
                <ol class="space-y-4">
                    @forelse($trending as $i => $item)
                        <li class="flex gap-3 items-start">
                            <span class="font-display text-2xl text-ink/20 leading-none shrink-0 pt-1">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="min-w-0">
                                <a href="{{ route('news.show', $item) }}" class="text-sm font-medium leading-snug hover:text-brand-primary line-clamp-2">
                                    {{ $item->title }}
                                </a>
                                <div class="mt-1 flex items-center gap-2 text-[11px] text-ink/40 font-mono">
                                    <span>{{ $item->published_at?->diffForHumans() }}</span>
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ number_format($item->views) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-ink/50">Belum ada berita.</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </section>

@endsection