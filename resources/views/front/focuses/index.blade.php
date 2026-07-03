@extends('front.layouts.app')

@section('title', 'Fokus')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display text-3xl font-bold mb-2">Fokus</h1>
    <p class="text-slate-500 mb-8">Liputan khusus dari berbagai topik pilihan redaksi.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($focuses as $focus)
            <a href="{{ route('focuses.show', $focus) }}" class="group block">
                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden mb-3">
                    @if($focus->cover_image)
                        <img src="{{ asset('storage/'.$focus->cover_image) }}" alt="{{ $focus->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @endif
                </div>
                <h2 class="font-display text-lg font-semibold group-hover:opacity-70 transition">{{ $focus->title }}</h2>
                @if($focus->description)
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $focus->description }}</p>
                @endif
            </a>
        @empty
            <p class="text-slate-400 col-span-3 text-center py-12">Belum ada fokus liputan.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $focuses->links() }}</div>
</div>
@endsection