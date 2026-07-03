<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $appearance = \App\Models\Setting::group('appearance');
        $general = \App\Models\Setting::group('general');
        $siteName = $general['site_name'] ?? 'Portal Berita';
        $navCategories = \App\Models\Category::active()->parents()->orderBy('order')->get();
    @endphp

    <title>@hasSection('title')@yield('title') &mdash; {{ $siteName }}@else{{ $siteName }}@endif</title>
    <meta name="description" content="@yield('meta_description', $general['site_tagline'] ?? '')">

    @if(!empty($appearance['favicon']))
        <link rel="icon" href="{{ Storage::url($appearance['favicon']) }}">
    @endif

    <style>
        :root {
            --color-primary: {{ $appearance['color_primary'] ?? '#2563eb' }};
            --color-secondary: {{ $appearance['color_secondary'] ?? '#1e40af' }};
            --color-header-bg: {{ $appearance['color_header_bg'] ?? '#ffffff' }};
            --color-header-text: {{ $appearance['color_header_text'] ?? '#111827' }};
            --color-footer-bg: {{ $appearance['color_footer_bg'] ?? '#111827' }};
            --color-footer-text: {{ $appearance['color_footer_text'] ?? '#f9fafb' }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-body antialiased">

    {{-- ============ MASTHEAD ============ --}}
    <header>
        {{-- baris tanggal & edisi --}}
        <div class="max-w-6xl mx-auto px-4 py-1.5 flex justify-between text-[11px] font-mono uppercase tracking-wider text-ink/50">
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            <span>Edisi No. {{ now()->format('z') + 1 }}</span>
        </div>

        {{-- baris logo, menu utama berikon, & pencarian (gradasi warna) --}}
        <div style="background: linear-gradient(115deg, var(--color-header-bg), var(--color-secondary));"
             class="text-brand-headerText">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    @if(!empty($appearance['logo']))
                        <img src="{{ Storage::url($appearance['logo']) }}" alt="{{ $siteName }}" class="h-9 w-auto">
                    @else
                        <span class="font-display text-2xl font-semibold tracking-tight">{{ $siteName }}</span>
                    @endif
                </a>

                <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="flex items-center gap-1.5 hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('kanal.index') }}" class="flex items-center gap-1.5 hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        Kanal
                    </a>
                    <a href="{{ route('focuses.index') }}" class="flex items-center gap-1.5 hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="3" x2="12" y2="7"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Fokus
                    </a>
                    <a href="{{ route('epapers.index') }}" class="flex items-center gap-1.5 hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        E-koran
                    </a>
                    <a href="{{ route('galleries.index') }}" class="flex items-center gap-1.5 hover:opacity-75 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        Foto
                    </a>
                </nav>

                <form action="{{ route('search') }}" method="GET" class="flex items-center shrink-0">
                    <input
                        type="text"
                        name="q"
                        placeholder="Cari berita..."
                        value="{{ request('q') }}"
                        class="hidden sm:block bg-white/15 placeholder:text-current/60 rounded-l px-3 py-1.5 text-sm w-40 lg:w-56 focus:outline-none focus:bg-white/25 transition"
                    >
                    <button type="submit" aria-label="Cari"
                            class="bg-white/15 sm:rounded-r rounded p-2 hover:bg-white/25 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- baris kategori (sub-nav, bisa di-scroll ke samping) --}}
        @if($navCategories->isNotEmpty())
            <div class="bg-brand-primary text-white overflow-x-auto">
                <div class="max-w-6xl mx-auto px-4">
                    <ul class="flex gap-6 py-2.5 text-sm font-medium whitespace-nowrap">
                        @foreach($navCategories as $cat)
                            <li>
                                <a href="{{ route('categories.show', $cat) }}" class="hover:opacity-75 transition">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ticker breaking news --}}
        @isset($breakingTicker)
            @if($breakingTicker->isNotEmpty())
                <div class="bg-ink text-white overflow-hidden">
                    <div class="max-w-6xl mx-auto px-4 py-2 flex items-center gap-3">
                        <span class="shrink-0 text-[11px] font-mono font-semibold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded">
                            Breaking
                        </span>
                        <div class="overflow-hidden flex-1 whitespace-nowrap">
                            <div class="inline-flex ticker-track">
                                @foreach($breakingTicker->concat($breakingTicker) as $item)
                                    <a href="{{ route('news.show', $item) }}" class="mx-6 text-sm hover:underline">
                                        {{ $item->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endisset
    </header>

    {{-- ============ FLASH MESSAGES ============ --}}
    <div class="max-w-6xl mx-auto px-4">
        @if(session('success'))
            <div class="mt-4 border border-green-300 bg-green-50 text-green-800 text-sm px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-2 rounded">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- ============ KONTEN ============ --}}
    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-brand-footerBg text-brand-footerText mt-16">
        <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <span class="font-display text-2xl font-semibold">{{ $siteName }}</span>
                <p class="mt-3 text-sm opacity-70 leading-relaxed">
                    {{ $general['site_tagline'] ?? '' }}
                </p>
            </div>

            <div>
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Kategori</h3>
                <ul class="space-y-1.5 text-sm">
                    @foreach($navCategories as $cat)
                        <li><a href="{{ route('categories.show', $cat) }}" class="opacity-80 hover:opacity-100">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Newsletter</h3>
                <p class="text-sm opacity-70 mb-3">Dapatkan ringkasan berita pilihan setiap pagi.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input
                        type="email" name="email" required placeholder="Alamat email"
                        class="flex-1 min-w-0 rounded px-3 py-2 text-sm text-ink"
                    >
                    <button type="submit" class="shrink-0 bg-brand-primary text-white text-sm px-4 py-2 rounded font-medium hover:opacity-90">
                        Langganan
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="max-w-6xl mx-auto px-4 py-4 text-xs opacity-60 font-mono">
                &copy; {{ now()->year }} {{ $siteName }}. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>