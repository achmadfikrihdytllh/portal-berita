@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'Total Berita', 'value' => $stats['total_news']],
                ['label' => 'Dipublikasikan', 'value' => $stats['published_news']],
                ['label' => 'Draft', 'value' => $stats['draft_news']],
                ['label' => 'Komentar Menunggu', 'value' => $stats['pending_comments']],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="bg-white border border-slate-200 rounded-lg p-5">
                <p class="text-xs font-mono uppercase tracking-wider text-slate-500">{{ $card['label'] }}</p>
                <p class="mt-2 font-display text-3xl font-semibold">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-lg p-5">
            <h2 class="font-semibold mb-4">Berita Terbaru</h2>
            <div class="space-y-3">
                @forelse($latestNews as $item)
                    <div class="flex items-center justify-between text-sm border-b border-slate-100 pb-2">
                        <div class="min-w-0">
                            <a href="{{ route('admin.news.edit', $item) }}" class="font-medium hover:text-blue-600 truncate block">
                                {{ $item->title }}
                            </a>
                            <p class="text-xs text-slate-400">{{ $item->category->name ?? '-' }} &middot; {{ $item->user->name ?? '-' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full shrink-0 ml-2
                            {{ $item->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $item->status }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada berita.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg p-5">
            <h2 class="font-semibold mb-4">Paling Banyak Dibaca</h2>
            <div class="space-y-3">
                @forelse($mostViewed as $item)
                    <div class="flex items-center justify-between text-sm border-b border-slate-100 pb-2">
                        <span class="font-medium truncate">{{ $item->title }}</span>
                        <span class="text-xs text-slate-400 shrink-0 ml-2">{{ number_format($item->views) }}x</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
