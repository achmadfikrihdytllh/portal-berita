@extends('front.layouts.app')

@section('title', $focus->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <nav class="text-sm text-slate-500 mb-4">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('focuses.index') }}" class="hover:underline">Fokus</a>
        <span class="mx-1">/</span>
        <span class="text-slate-700">{{ $focus->title }}</span>
    </nav>

    @if($focus->cover_image)
        <img src="{{ asset('storage/'.$focus->cover_image) }}" alt="{{ $focus->title }}"
             class="w-full aspect-video object-cover rounded-lg mb-6">
    @endif

    <h1 class="font-display text-3xl md:text-4xl font-bold mb-3">{{ $focus->title }}</h1>
    @if($focus->description)
        <p class="text-slate-600 mb-8">{{ $focus->description }}</p>
    @endif

    <div class="space-y-4">
        @forelse($focus->news as $item)
            <a href="{{ route('news.show', $item) }}" class="flex gap-4 group border-b border-slate-100 pb-4">
                @if($item->thumbnail)
                    <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}"
                         class="w-32 h-20 object-cover rounded shrink-0">
                @endif
                <div>
                    <span class="text-xs text-brand-primary font-medium">{{ $item->category->name ?? '' }}</span>
                    <h3 class="font-display text-lg font-semibold group-hover:opacity-70 transition">{{ $item->title }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $item->published_at?->translatedFormat('d F Y') }}</p>
                </div>
            </a>
        @empty
            <p class="text-slate-400 text-center py-8">Belum ada berita di fokus ini.</p>
        @endforelse
    </div>
</div>
@endsection