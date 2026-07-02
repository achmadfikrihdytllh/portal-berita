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

    {{-- ============ SIDEBAR ============ --}}
    <aside class="w-64 shrink-0 bg-slate-900 text-slate-200 flex flex-col">
        <div class="px-6 py-5 border-b border-white/10">
            <span class="font-display text-xl font-semibold text-white">Admin Panel</span>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
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
        <header class="bg-white border-b border-slate-200 px-8 py-4">
            <h1 class="font-display text-xl font-semibold">@yield('title', 'Dashboard')</h1>
        </header>

        <main class="flex-1 px-8 py-6">
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
</body>
</html>
