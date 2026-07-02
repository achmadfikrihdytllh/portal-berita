@extends('front.layouts.app')

@section('title', $category->name)

@section('content')
    <div class="mb-8 pb-6 border-b border-rule">
        <span class="font-mono text-xs uppercase tracking-wider text-brand-primary font-semibold">Kategori</span>
        <h1 class="font-display text-3xl font-semibold mt-1">{{ $category->name }}</h1>
        @if($category->description)
            <p class="mt-2 text-ink/60">{{ $category->description }}</p>
        @endif

        @if($subCategories->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($subCategories as $sub)
                    <a href="{{ route('categories.show', $sub) }}"
                       class="text-xs font-mono px-3 py-1 rounded-full border border-rule hover:border-brand-primary hover:text-brand-primary">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-8">
        @forelse($news as $item)
            <x-news-card :news="$item" />
        @empty
            <p class="text-ink/50 col-span-full">Belum ada berita di kategori ini.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $news->links() }}
    </div>
@endsection
