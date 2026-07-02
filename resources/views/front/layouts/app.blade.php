<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        // Catatan: untuk performa lebih baik, pindahkan query ini ke View Composer
        // (app/Providers/AppServiceProvider.php) supaya tidak dieksekusi di setiap request layout.
        $appearance = \App\Models\Setting::group('appearance');
        $general = \App\Models\Setting::group('general');
        $siteName = $general['site_name'] ?? 'Portal Berita';
        $navCategories = \App\Models\Category::active()->parents()->orderBy('order')->take(6)->get();
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
    <header class="border-b border-rule">
        {{-- baris tanggal & edisi --}}
        <div class="max-w-6xl mx-auto px-4 py-1.5 flex justify-between text-[11px] font-mono uppercase tracking-wider text-ink/60">
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            <span>Edisi No. {{ now()->format('z') + 1 }}</span>
        </div>

        {{-- baris logo & pencarian --}}
        <div class="bg-brand-headerBg text-brand-headerText">
            <div class="max-w-6xl mx-auto px-4 py-5 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if(!empty($appearance['logo']))
                        <img src="{{ Storage::url($appearance['logo']) }}" alt="{{ $siteName }}" class="h-10 w-auto">
                    @else
                        <span class="font-display text-3xl font-semibold tracking-tight">{{ $siteName }}</span>
                    @endif
                </a>

                <form action="{{ route('search') }}" method="GET" class="hidden sm:flex items-center border-b border-current/30 focus-within:border-current">
                    <input
                        type="text"
                        name="q"
                        placeholder="Cari berita..."
                        value="{{ request('q') }}"
                        class="bg-transparent text-sm py-1 px-2 w-52 focus:outline-none placeholder:text-current/40"
                    >
                    <button type="submit" aria-label="Cari" class="px-2 text-current/70 hover:text-current">
                        &#128269;
                    </button>
                </form>
            </div>

            {{-- navigasi kategori --}}
            <nav class="max-w-6xl mx-auto px-4 pb-3">
                <ul class="flex flex-wrap gap-x-6 gap-y-1 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors">Beranda</a></li>
                    @foreach($navCategories as $cat)
                        <li>
                            <a href="{{ route('categories.show', $cat) }}" class="hover:text-brand-primary transition-colors">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        {{-- ticker breaking news --}}
        @isset($breakingTicker)
            @if($breakingTicker->isNotEmpty())
                <div class="bg-brand-primary text-white overflow-hidden">
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
