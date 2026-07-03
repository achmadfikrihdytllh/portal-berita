@extends('front.layouts.app')

@section('title', 'Kanal Berita')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-slate-700">Kanal</span>
    </nav>

    <div class="text-center max-w-2xl mx-auto mb-12">
        <h1 class="font-display text-3xl md:text-4xl font-bold mb-3">Kanal Berita</h1>
        <p class="text-slate-500">
            Jelajahi berbagai kanal berita kami untuk mendapatkan informasi terkini dari berbagai kategori.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">
        <div class="border border-slate-200 rounded-lg p-8 text-center">
            <p class="font-display text-4xl font-bold text-brand-primary">{{ $totalChannels }}+</p>
            <p class="text-slate-500 mt-1">Kanal Berita</p>
        </div>
        <div class="border border-slate-200 rounded-lg p-8 text-center">
            <p class="font-display text-4xl font-bold text-brand-primary">{{ number_format($totalArticles) }}+</p>
            <p class="text-slate-500 mt-1">Total Artikel</p>
        </div>
        <div class="border border-slate-200 rounded-lg p-8 text-center">
            <p class="font-display text-4xl font-bold text-brand-primary">24/7</p>
            <p class="text-slate-500 mt-1">Update Terkini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category) }}"
               class="border border-slate-200 rounded-lg p-5 hover:border-brand-primary hover:shadow-sm transition">
                <h2 class="font-display text-lg font-semibold">{{ $category->name }}</h2>
                @if($category->description)
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                @endif
                <p class="text-xs text-brand-primary mt-3">{{ $category->news_count }} artikel &rarr;</p>
            </a>
        @endforeach
    </div>
</div>
@endsection