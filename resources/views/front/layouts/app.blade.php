<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $appearance = \App\Models\Setting::group('appearance');
        $general = \App\Models\Setting::group('general');
        $social = \App\Models\Setting::group('social');
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
        #catScroll::-webkit-scrollbar { display: none; }
        #catScroll { scrollbar-width: none; -ms-overflow-style: none; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }

        .thin-scrollbar::-webkit-scrollbar { width: 6px; }
        .thin-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .thin-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 999px;
        }
        .thin-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.3); }
        .thin-scrollbar { scrollbar-width: thin; scrollbar-color: rgba(0, 0, 0, 0.15) transparent; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-body antialiased">

    {{-- ============ MASTHEAD ============ --}}
    <header>
        {{-- baris tanggal & edisi: desktop saja, biar mobile lebih ringkas --}}
        <div class="hidden md:flex max-w-6xl mx-auto px-4 py-1.5 justify-between text-[11px] font-mono uppercase tracking-wider text-ink/50">
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            <span>Edisi No. {{ now()->format('z') + 1 }}</span>
        </div>

        {{-- baris logo, menu utama berikon, & pencarian (gradasi warna) --}}
        <div style="background: linear-gradient(115deg, var(--color-header-bg), var(--color-secondary));"
             class="text-brand-headerText">
            <div class="max-w-6xl mx-auto px-3 md:px-4 py-3 md:py-4 flex items-center gap-3 md:gap-6">

                {{-- hamburger: mobile only, di kiri --}}
                <button type="button" aria-label="Buka menu" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        class="md:hidden shrink-0 p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>

                {{-- logo: center di mobile, kiri di desktop --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 flex-1 justify-center md:flex-none md:justify-start">
                    @if(!empty($appearance['logo']))
                        <img src="{{ Storage::url($appearance['logo']) }}" alt="{{ $siteName }}" class="h-8 md:h-9 w-auto">
                    @else
                        <span class="font-display text-xl md:text-2xl font-semibold tracking-tight">{{ $siteName }}</span>
                    @endif
                </a>

                {{-- menu utama: desktop saja --}}
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

                {{-- pencarian: desktop (form penuh), didorong ke pojok kanan --}}
                <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center shrink-0 md:ml-auto">
                    <input
                        type="text"
                        name="q"
                        placeholder="Cari berita..."
                        value="{{ request('q') }}"
                        class="bg-white/15 placeholder:text-current/60 rounded-l px-3 py-1.5 text-sm w-40 lg:w-56 focus:outline-none focus:bg-white/25 transition"
                    >
                    <button type="submit" aria-label="Cari"
                            class="bg-white/15 rounded-r p-2 hover:bg-white/25 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>

                {{-- pencarian: mobile (ikon saja, di kanan) --}}
                <button type="button" aria-label="Cari" onclick="document.getElementById('mobileSearchBar').classList.toggle('hidden')"
                        class="md:hidden shrink-0 p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </div>

            {{-- search bar mobile: nyembul di bawah header saat ikon cari ditekan --}}
            <div id="mobileSearchBar" class="md:hidden hidden px-3 pb-3">
                <form action="{{ route('search') }}" method="GET" class="flex items-center">
                    <input type="text" name="q" placeholder="Cari berita..." value="{{ request('q') }}" autofocus
                           class="bg-white/15 placeholder:text-current/60 rounded-l px-3 py-2 text-sm flex-1 focus:outline-none focus:bg-white/25 transition">
                    <button type="submit" aria-label="Cari" class="bg-white/15 rounded-r p-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>
            </div>

            {{-- panel menu hamburger: mobile only, isi link situs (bukan kategori, kategori sudah tampil sebagai bar di bawah) --}}
            <div id="mobileMenu" class="md:hidden hidden border-t border-white/15 px-4 py-3">
                <nav class="flex flex-col gap-1 text-sm font-medium">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('kanal.index') }}" class="flex items-center gap-2.5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        Kanal
                    </a>
                    <a href="{{ route('focuses.index') }}" class="flex items-center gap-2.5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="3" x2="12" y2="7"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                        Fokus
                    </a>
                    <a href="{{ route('epapers.index') }}" class="flex items-center gap-2.5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        E-koran
                    </a>
                    <a href="{{ route('galleries.index') }}" class="flex items-center gap-2.5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        Foto
                    </a>
                </nav>
            </div>
        </div>

        {{-- baris kategori (sub-nav): SELALU tampil, mobile & desktop, scroll horizontal --}}
        @if($navCategories->isNotEmpty())
            <div class="bg-brand-primary text-white">
                <div class="max-w-6xl mx-auto px-2 flex items-center">
                    {{-- tombol panah: desktop saja, di mobile cukup swipe --}}
                    <button type="button" aria-label="Scroll kiri"
                            onclick="document.getElementById('catScroll').scrollBy({left:-220,behavior:'smooth'})"
                            class="hidden md:block shrink-0 p-2 hover:opacity-70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>

                    <div id="catScroll" class="overflow-x-auto">
                        <ul class="flex gap-5 md:gap-6 py-2.5 px-3 md:px-2 text-sm font-medium whitespace-nowrap">
                            @foreach($navCategories as $cat)
                                <li>
                                    <a href="{{ route('categories.show', $cat) }}" class="hover:opacity-75 transition">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <button type="button" aria-label="Scroll kanan"
                            onclick="document.getElementById('catScroll').scrollBy({left:220,behavior:'smooth'})"
                            class="hidden md:block shrink-0 p-2 hover:opacity-70 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
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
        <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                @if(!empty($appearance['logo']))
                    <img src="{{ Storage::url($appearance['logo']) }}" alt="{{ $siteName }}" class="h-9 w-auto mb-3 brightness-0 invert">
                @else
                    <span class="font-display text-2xl font-semibold">{{ $siteName }}</span>
                @endif
                <p class="mt-3 text-sm opacity-70 leading-relaxed">
                    {{ $general['site_tagline'] ?? '' }}
                </p>

                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mt-6 mb-3">Kontak Kami</h3>
                <ul class="space-y-2 text-sm opacity-80">
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Jl. Merdeka No. 45, Kota Contoh, Jawa Timur 60000</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.902.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.908.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>(031) 555-0123</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>redaksi@portalberita.test</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Kanal Utama</h3>
                <ul class="space-y-1.5 text-sm">
                    @foreach($navCategories->take(8) as $cat)
                        <li><a href="{{ route('categories.show', $cat) }}" class="opacity-80 hover:opacity-100">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('kanal.index') }}" class="opacity-80 hover:opacity-100 font-medium">Lihat semua kanal &rarr;</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Jelajahi</h3>
                <ul class="space-y-1.5 text-sm mb-6">
                    <li><a href="{{ route('focuses.index') }}" class="opacity-80 hover:opacity-100">Fokus</a></li>
                    <li><a href="{{ route('epapers.index') }}" class="opacity-80 hover:opacity-100">E-koran</a></li>
                    <li><a href="{{ route('galleries.index') }}" class="opacity-80 hover:opacity-100">Foto</a></li>
                </ul>

                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Ikuti Kami</h3>
                <div class="flex gap-3">
                    <a href="{{ $social['facebook'] ?? '#' }}" target="_blank" rel="noopener" aria-label="Facebook"
                       class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg>
                    </a>
                    <a href="{{ $social['twitter'] ?? '#' }}" target="_blank" rel="noopener" aria-label="Twitter/X"
                       class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.6 8.7L23 22h-7l-5.5-6.8L4.2 22H1l8.2-9.4L1 2h7.2l5 6.2L18.9 2zm-1.2 18h1.7L7.4 4h-1.8l12.1 16z"/></svg>
                    </a>
                    <a href="{{ $social['instagram'] ?? '#' }}" target="_blank" rel="noopener" aria-label="Instagram"
                       class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
                    </a>
                    <a href="{{ $social['youtube'] ?? '#' }}" target="_blank" rel="noopener" aria-label="YouTube"
                       class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23 12s0-3.6-.5-5.3c-.3-1-1.1-1.8-2-2C18.9 4.2 12 4.2 12 4.2s-6.9 0-8.5.5c-1 .2-1.8 1-2 2C1 8.4 1 12 1 12s0 3.6.5 5.3c.3 1 1.1 1.8 2 2 1.6.5 8.5.5 8.5.5s6.9 0 8.5-.5c1-.2 1.8-1 2-2 .5-1.7.5-5.3.5-5.3zM9.8 15.5v-7l6 3.5-6 3.5z"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-60 mb-3">Newsletter</h3>
                <p class="text-sm opacity-70 mb-3">Dapatkan ringkasan berita pilihan setiap pagi.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col gap-2">
                    @csrf
                    <input
                        type="email" name="email" required placeholder="Alamat email"
                        class="rounded px-3 py-2 text-sm text-ink"
                    >
                    <button type="submit" class="bg-brand-primary text-white text-sm px-4 py-2 rounded font-medium hover:opacity-90">
                        Langganan
                    </button>
                </form>
            </div>
        </div>

        {{-- ============ MEMBER OF (dummy) ============ --}}
        <div class="border-t border-white/10 py-10">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <h3 class="font-mono text-xs uppercase tracking-wider opacity-50 mb-6">Member Of</h3>
                <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-4 opacity-60">
                    <span class="font-display font-bold text-lg tracking-wide">ASOSIASI MEDIA SIBER</span>
                    <span class="font-display font-bold text-lg tracking-wide">DEWAN PERS TERVERIFIKASI</span>
                    <span class="font-display font-bold text-lg tracking-wide">IJTI</span>
                    <span class="font-display font-bold text-lg tracking-wide">WAN-IFRA</span>
                </div>
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