@extends('front.layouts.app')

@section('title', $gallery->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <nav class="text-sm text-slate-500 mb-4">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('galleries.index') }}" class="hover:underline">Foto</a>
        <span class="mx-1">/</span>
        <span class="text-slate-700">{{ $gallery->title }}</span>
    </nav>

    <h1 class="font-display text-3xl md:text-4xl font-bold mb-2">{{ $gallery->title }}</h1>
    <p class="text-sm text-slate-500 mb-1">
        Oleh {{ $gallery->user->name ?? 'Redaksi' }} &middot; {{ $gallery->published_at?->translatedFormat('d F Y') }}
    </p>
    @if($gallery->description)
        <p class="text-slate-600 mt-4 mb-8">{{ $gallery->description }}</p>
    @endif

    <div class="space-y-8">
        @foreach($gallery->images as $image)
            <figure>
                <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->caption }}"
                     class="w-full rounded-lg">
                @if($image->caption)
                    <figcaption class="text-sm text-slate-500 mt-2 text-center">{{ $image->caption }}</figcaption>
                @endif
            </figure>
        @endforeach
    </div>
</div>
@endsection