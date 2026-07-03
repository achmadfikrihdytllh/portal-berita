@extends('front.layouts.app')

@section('title', 'E-koran')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display text-3xl font-bold mb-2">E-koran</h1>
    <p class="text-slate-500 mb-8">Baca edisi cetak digital kami setiap hari.</p>

    @if($latest)
        <div class="flex flex-col md:flex-row gap-8 mb-12 border-b border-slate-100 pb-10">
            <a href="{{ asset('storage/'.$latest->file_path) }}" target="_blank" class="shrink-0 group">
                <img src="{{ asset('storage/'.$latest->cover_image) }}" alt="{{ $latest->title }}"
                     class="w-48 rounded shadow-lg group-hover:shadow-xl transition">
            </a>
            <div>
                <span class="text-xs uppercase tracking-wider text-brand-primary font-medium">Edisi Terbaru</span>
                <h2 class="font-display text-2xl font-bold mt-1">{{ $latest->title }}</h2>
                <p class="text-slate-500 text-sm mt-1">{{ $latest->edition_date->translatedFormat('l, d F Y') }}</p>
                <a href="{{ asset('storage/'.$latest->file_path) }}" target="_blank"
                   class="inline-block mt-4 bg-brand-primary text-white text-sm font-medium px-5 py-2 rounded hover:opacity-90">
                    Baca Sekarang
                </a>
            </div>
        </div>
    @endif

    <h3 class="font-display text-xl font-semibold mb-4">Edisi Sebelumnya</h3>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        @foreach($epapers as $epaper)
            <a href="{{ asset('storage/'.$epaper->file_path) }}" target="_blank" class="group">
                <img src="{{ asset('storage/'.$epaper->cover_image) }}" alt="{{ $epaper->title }}"
                     class="w-full rounded shadow group-hover:shadow-lg transition">
                <p class="text-xs text-slate-500 mt-2 text-center">{{ $epaper->edition_date->translatedFormat('d M Y') }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8">{{ $epapers->links() }}</div>
</div>
@endsection