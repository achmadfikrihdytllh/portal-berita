<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &mdash; Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 font-body antialiased min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <span class="font-display text-2xl font-semibold text-white">
                {{ \App\Models\Setting::get('site_name', 'Portal Berita') }}
            </span>
            <p class="text-slate-400 text-sm mt-1">Masuk ke panel redaksi</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-xl">
            @if ($errors->any())
                <div class="mb-4 border border-red-300 bg-red-50 text-red-700 text-sm px-3 py-2 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 border border-green-300 bg-green-50 text-green-700 text-sm px-3 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input
                        id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input
                        id="password" type="password" name="password" required
                        class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-brand-primary text-white text-sm font-medium px-4 py-2.5 rounded hover:opacity-90">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; {{ now()->year }} {{ \App\Models\Setting::get('site_name', 'Portal Berita') }}
        </p>
    </div>

</body>
</html>