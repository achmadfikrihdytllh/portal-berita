@extends('front.layouts.app')

@section('title', 'Foto')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display text-3xl font-bold mb-2">Foto</h1>
    <p class="text-slate-500 mb-8">Galeri foto jurnalistik pilihan redaksi.</p>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
        @forelse($galleries as $gallery)
            <a href="{{ route('galleries.show', $gallery) }}" class="group block">
                <div class="aspect-square bg-slate-100 rounded-lg overflow-hidden relative">
                    @if($gallery->cover_image)
                        <img src="{{ asset('storage/'.$gallery->cover_image) }}" alt="{{ $gallery->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @endif
                    <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $gallery->images_count ?? $gallery->images()->count() }} foto
                    </span>
                </div>
                <h2 class="font-display text-base font-semibold mt-2 group-hover:opacity-70 transition line-clamp-2">
                    {{ $gallery->title }}
                </h2>
            </a>
        @empty
            <p class="text-slate-400 col-span-3 text-center py-12">Belum ada galeri foto.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $galleries->links() }}</div>
</div>
@endsection