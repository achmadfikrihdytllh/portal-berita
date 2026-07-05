@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between gap-4 flex-wrap">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-medium">{{ $paginator->firstItem() }}</span>
            &ndash; <span class="font-medium">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center gap-1">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="w-9 h-9 flex items-center justify-center rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $onEachSide = 1;
                $pages = collect(range(1, $last));

                // sisakan: halaman 1, halaman terakhir, dan sekitar halaman aktif; sisanya jadi "..."
                $shown = $pages->filter(function ($page) use ($current, $last, $onEachSide) {
                    return $page === 1 || $page === $last || ($page >= $current - $onEachSide && $page <= $current + $onEachSide);
                })->values();
            @endphp

            @php $previousPage = null; @endphp
            @foreach ($shown as $page)
                @if ($previousPage && $page - $previousPage > 1)
                    <span class="w-9 h-9 flex items-center justify-center text-slate-400 text-sm">&hellip;</span>
                @endif

                @if ($page == $current)
                    <span aria-current="page"
                          class="w-9 h-9 flex items-center justify-center rounded bg-brand-primary text-white text-sm font-medium">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                       class="w-9 h-9 flex items-center justify-center rounded border border-slate-300 text-slate-600 text-sm hover:bg-slate-50 transition">
                        {{ $page }}
                    </a>
                @endif

                @php $previousPage = $page; @endphp
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="w-9 h-9 flex items-center justify-center rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <span class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif