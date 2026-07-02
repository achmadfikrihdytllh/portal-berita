@extends('front.layouts.app')

@section('title', $news->title)
@section('meta_description', $news->meta_description ?? $news->excerpt)

@section('content')
    <article class="max-w-3xl mx-auto">

        <a href="{{ route('categories.show', $news->category) }}"
           class="font-mono text-xs uppercase tracking-wider text-brand-primary font-semibold">
            {{ $news->category->name }}
        </a>

        <h1 class="mt-2 font-display text-3xl md:text-4xl font-semibold leading-tight">
            {{ $news->title }}
        </h1>

        <div class="mt-4 flex items-center gap-2 text-sm text-ink/60 font-mono pb-6 border-b border-rule">
            <span>Oleh {{ $news->user->name ?? 'Redaksi' }}</span>
            <span>&middot;</span>
            <span>{{ $news->published_at?->translatedFormat('d F Y, H:i') }}</span>
            <span>&middot;</span>
            <span>{{ number_format($news->views) }} dibaca</span>
        </div>

        @if($news->thumbnail)
            <img src="{{ Storage::url($news->thumbnail) }}" alt="{{ $news->title }}"
                 class="mt-6 w-full rounded-lg object-cover aspect-[16/9]">
        @endif

        <div class="prose prose-lg max-w-none mt-8 leading-relaxed">
            {!! nl2br(e($news->content)) !!}
        </div>

        @if($news->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach($news->tags as $tag)
                    <a href="{{ route('news.index', ['tag' => $tag->slug]) }}"
                       class="text-xs font-mono px-3 py-1 rounded-full border border-rule hover:border-brand-primary hover:text-brand-primary">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ============ KOMENTAR ============ --}}
        <section class="mt-14 pt-8 border-t border-rule">
            <h2 class="font-display text-2xl font-semibold mb-6">
                Komentar ({{ $news->approvedComments->count() }})
            </h2>

            <form action="{{ route('news.comment', $news) }}" method="POST" class="mb-10 space-y-3">
                @csrf
                @auth
                    <p class="text-sm text-ink/60">Berkomentar sebagai <strong>{{ auth()->user()->name }}</strong></p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" name="name" placeholder="Nama" required
                               class="border border-rule rounded px-3 py-2 text-sm">
                        <input type="email" name="email" placeholder="Email" required
                               class="border border-rule rounded px-3 py-2 text-sm">
                    </div>
                @endauth
                <textarea name="content" rows="3" required placeholder="Tulis komentar..."
                          class="w-full border border-rule rounded px-3 py-2 text-sm"></textarea>
                <button type="submit" class="bg-brand-primary text-white text-sm font-medium px-5 py-2 rounded hover:opacity-90">
                    Kirim Komentar
                </button>
                @error('content')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </form>

            <div class="space-y-6">
                @forelse($news->approvedComments as $comment)
                    <div>
                        <div class="flex items-center gap-2 text-sm">
                            <strong>{{ $comment->name }}</strong>
                            <span class="text-ink/40 text-xs font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-ink/80 leading-relaxed">{{ $comment->content }}</p>

                        @if($comment->replies->isNotEmpty())
                            <div class="mt-4 ml-6 space-y-4 border-l border-rule pl-4">
                                @foreach($comment->replies as $reply)
                                    <div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <strong>{{ $reply->name }}</strong>
                                            <span class="text-ink/40 text-xs font-mono">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-1 text-ink/80 leading-relaxed">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Belum ada komentar. Jadilah yang pertama berkomentar.</p>
                @endforelse
            </div>
        </section>
    </article>

    {{-- ============ RELATED NEWS ============ --}}
    @if($related->isNotEmpty())
        <section class="max-w-5xl mx-auto mt-16 pt-8 border-t border-rule">
            <h2 class="font-display text-2xl font-semibold mb-6">Berita Terkait</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
                @foreach($related as $item)
                    <x-news-card :news="$item" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
