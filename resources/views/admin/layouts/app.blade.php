<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') &mdash; Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-body antialiased">
<div class="flex min-h-screen">

    {{-- overlay gelap saat sidebar mobile terbuka --}}
    <div id="sidebarOverlay" onclick="toggleSidebar()"
         class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    {{-- ============ SIDEBAR ============ --}}
    <aside id="sidebar"
           class="w-64 shrink-0 bg-slate-900 text-slate-200 flex flex-col
                  fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-200
                  md:static md:translate-x-0">
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
            <span class="font-display text-xl font-semibold text-white">Admin Panel</span>
            <button type="button" onclick="toggleSidebar()" aria-label="Tutup menu" class="md:hidden text-slate-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
            @php $user = auth()->user(); @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.news.index') }}"
               class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.news.*') ? 'bg-white/10 text-white' : '' }}">
                Berita
            </a>

            @if($user?->isEditor())
                <a href="{{ route('admin.categories.index') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : '' }}">
                    Kategori
                </a>

                <a href="{{ route('admin.comments.index') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.comments.*') ? 'bg-white/10 text-white' : '' }}">
                    Komentar
                </a>

                <div class="pt-4 mt-4 border-t border-white/10">
                    <span class="px-3 text-[11px] font-mono uppercase tracking-wider text-slate-500">Konten Lainnya</span>
                </div>

                <a href="{{ route('admin.focuses.index') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.focuses.*') ? 'bg-white/10 text-white' : '' }}">
                    Fokus
                </a>

                <a href="{{ route('admin.epapers.index') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.epapers.*') ? 'bg-white/10 text-white' : '' }}">
                    E-koran
                </a>

                <a href="{{ route('admin.galleries.index') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.galleries.*') ? 'bg-white/10 text-white' : '' }}">
                    Galeri Foto
                </a>
            @endif

            @if($user?->isAdmin())
                <div class="pt-4 mt-4 border-t border-white/10">
                    <span class="px-3 text-[11px] font-mono uppercase tracking-wider text-slate-500">Pengaturan</span>
                </div>
                <a href="{{ route('admin.settings.appearance') }}"
                   class="block px-3 py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.settings.appearance') ? 'bg-white/10 text-white' : '' }}">
                    Tampilan (Warna & Logo)
                </a>
            @endif
        </nav>

        <div class="px-6 py-4 border-t border-white/10 text-sm">
            <p class="text-white">{{ $user?->name }}</p>
            <p class="text-slate-400 text-xs capitalize">{{ $user?->role }}</p>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="text-xs text-slate-400 hover:text-white">Keluar</button>
            </form>
        </div>
    </aside>

    {{-- ============ KONTEN ============ --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 flex items-center gap-3">
            <button type="button" onclick="toggleSidebar()" aria-label="Buka menu" class="md:hidden text-slate-500 hover:text-slate-900 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="font-display text-lg md:text-xl font-semibold truncate">@yield('title', 'Dashboard')</h1>
        </header>

        <main class="flex-1 px-4 md:px-8 py-6 overflow-x-auto">
            @if(session('success'))
                <div class="mb-6 border border-green-300 bg-green-50 text-green-800 text-sm px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-2 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
</script>
</body>
</html>