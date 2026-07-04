@props(['news', 'size' => 'default'])

<article class="group">
    <a href="{{ route('news.show', $news) }}" class="block overflow-hidden rounded-lg bg-ink/5 aspect-[16/10]">
        @if($news->thumbnail)
            <img
                src="{{ Storage::url($news->thumbnail) }}"
                alt="{{ $news->title }}"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
        @else
            <div class="h-full w-full flex items-center justify-center text-ink/30 font-display text-sm">
                {{ $news->category->name ?? 'Berita' }}
            </div>
        @endif
    </a>

    <div class="mt-3">
        <a href="{{ route('categories.show', $news->category) }}"
           class="font-mono text-[11px] uppercase tracking-wider text-brand-primary font-semibold">
            {{ $news->category->name }}
        </a>

        <h3 class="mt-1 font-display font-semibold leading-snug {{ $size === 'large' ? 'text-2xl md:text-3xl' : 'text-lg' }}">
            <a href="{{ route('news.show', $news) }}" class="hover:underline decoration-brand-primary">
                {{ $news->title }}
            </a>
        </h3>

        @if($size === 'large' && $news->excerpt)
            <p class="mt-2 text-ink/70 leading-relaxed">{{ $news->excerpt }}</p>
        @endif

        <div class="mt-2 flex items-center gap-2 text-xs text-ink/50 font-mono">
            <span>{{ $news->user->name ?? 'Redaksi' }}</span>
            <span>&middot;</span>
            <span>{{ $news->published_at?->diffForHumans() }}</span>
            <span>&middot;</span>
            <span class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ number_format($news->views) }}
            </span>
        </div>
    </div>
</article>