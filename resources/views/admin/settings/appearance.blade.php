@extends('admin.layouts.app')

@section('title', 'Pengaturan Tampilan')

@section('content')

    <form action="{{ route('admin.settings.appearance.update') }}" method="POST" enctype="multipart/form-data" id="appearance-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ============ KOLOM FORM ============ --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <h2 class="font-semibold mb-4">Identitas Situs</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Situs</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $general['site_name'] ?? '') }}"
                                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Tagline</label>
                            <input type="text" name="site_tagline" value="{{ old('site_tagline', $general['site_tagline'] ?? '') }}"
                                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Logo</label>
                                <input type="file" name="logo" accept="image/*" onchange="previewImage(this, 'logo-preview')"
                                    class="w-full text-sm border border-slate-300 rounded px-3 py-2">
                                @if(!empty($appearance['logo']))
                                    <div class="flex items-center gap-3 mt-2">
                                        <img id="logo-preview" src="{{ Storage::url($appearance['logo']) }}" class="h-10 object-contain">
                                        <button type="submit" form="remove-logo-form" onclick="return confirm('Hapus logo?')"
                                                class="text-xs text-red-600 underline">Hapus</button>
                                    </div>
                                @else
                                    <img id="logo-preview" class="mt-2 h-10 object-contain hidden">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Favicon</label>
                                <input type="file" name="favicon" accept="image/*" onchange="previewImage(this, 'favicon-preview')"
                                    class="w-full text-sm border border-slate-300 rounded px-3 py-2">
                                @if(!empty($appearance['favicon']))
                                    <div class="flex items-center gap-3 mt-2">
                                        <img id="favicon-preview" src="{{ Storage::url($appearance['favicon']) }}" class="h-8 w-8 object-contain">
                                        <button type="submit" form="remove-favicon-form" onclick="return confirm('Hapus favicon?')"
                                                class="text-xs text-red-600 underline">Hapus</button>
                                    </div>
                                @else
                                    <img id="favicon-preview" class="mt-2 h-8 w-8 object-contain hidden">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg p-5">
                    <h2 class="font-semibold mb-1">Warna Situs</h2>
                    <p class="text-sm text-slate-500 mb-4">Perubahan langsung terlihat di panel pratinjau sebelah kanan.</p>

                    @php
                        $colorFields = [
                            'color_primary'      => 'Warna Utama (tombol, link, aksen)',
                            'color_secondary'    => 'Warna Sekunder',
                            'color_header_bg'    => 'Latar Header',
                            'color_header_text'  => 'Teks Header',
                            'color_footer_bg'    => 'Latar Footer',
                            'color_footer_text'  => 'Teks Footer',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($colorFields as $key => $label)
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ $label }}</label>
                                <div class="flex items-center gap-2 border border-slate-300 rounded px-2 py-1.5">
                                    <input
                                        type="color"
                                        name="{{ $key }}"
                                        id="{{ $key }}"
                                        value="{{ old($key, $appearance[$key] ?? '#2563eb') }}"
                                        oninput="syncPreview('{{ $key }}')"
                                        class="h-8 w-8 shrink-0 border-0 p-0 cursor-pointer bg-transparent"
                                    >
                                    <input
                                        type="text"
                                        value="{{ old($key, $appearance[$key] ?? '#2563eb') }}"
                                        oninput="document.getElementById('{{ $key }}').value = this.value; syncPreview('{{ $key }}')"
                                        class="flex-1 text-sm font-mono border-0 focus:outline-none"
                                    >
                                </div>
                                @error($key)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded hover:bg-blue-700">
                    Simpan Pengaturan
                </button>
            </div>

            {{-- ============ LIVE PREVIEW ============ --}}
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <p class="text-xs font-mono uppercase tracking-wider text-slate-500 mb-2">Pratinjau Langsung</p>

                    <div id="preview-box" class="border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                        {{-- header --}}
                        <div id="preview-header" class="px-4 py-3 flex items-center justify-between">
                            <img id="preview-logo" src="{{ !empty($appearance['logo']) ? Storage::url($appearance['logo']) : '' }}"
                                 class="h-6 {{ empty($appearance['logo']) ? 'hidden' : '' }}">
                            <span id="preview-sitename" class="font-display font-semibold {{ !empty($appearance['logo']) ? 'hidden' : '' }}">
                                {{ $general['site_name'] ?? 'Portal Berita' }}
                            </span>
                            <div id="preview-btn" class="text-xs px-3 py-1 rounded text-white">Berlangganan</div>
                        </div>

                        {{-- body dummy --}}
                        <div class="p-4 bg-white space-y-2">
                            <div id="preview-eyebrow" class="text-[10px] font-mono uppercase font-semibold">Teknologi</div>
                            <div class="h-3 bg-slate-200 rounded w-5/6"></div>
                            <div class="h-3 bg-slate-200 rounded w-3/4"></div>
                            <div id="preview-link" class="text-xs font-medium mt-2">Baca selengkapnya &rarr;</div>
                        </div>

                        {{-- footer --}}
                        <div id="preview-footer" class="px-4 py-3 text-xs">
                            &copy; {{ now()->year }} <span id="preview-footer-name">{{ $general['site_name'] ?? 'Portal Berita' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Form terpisah untuk hapus logo/favicon, HARUS di luar #appearance-form (HTML tidak boleh nested form) --}}
    <form id="remove-logo-form" action="{{ route('admin.settings.appearance.remove-image', 'logo') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    <form id="remove-favicon-form" action="{{ route('admin.settings.appearance.remove-image', 'favicon') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function previewImage(input, targetId) {
            const img = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
                img.classList.remove('hidden');
                if (targetId === 'logo-preview') {
                    document.getElementById('preview-logo').src = img.src;
                    document.getElementById('preview-logo').classList.remove('hidden');
                    document.getElementById('preview-sitename').classList.add('hidden');
                }
            }
        }

        function syncPreview(key) {
            const value = document.getElementById(key).value;
            const map = {
                color_header_bg:   el => document.getElementById('preview-header').style.backgroundColor = el,
                color_header_text: el => document.getElementById('preview-header').style.color = el,
                color_footer_bg:   el => document.getElementById('preview-footer').style.backgroundColor = el,
                color_footer_text: el => document.getElementById('preview-footer').style.color = el,
                color_primary:     el => {
                    document.getElementById('preview-btn').style.backgroundColor = el;
                    document.getElementById('preview-eyebrow').style.color = el;
                    document.getElementById('preview-link').style.color = el;
                },
            };
            if (map[key]) map[key](value);
        }

        // Inisialisasi preview saat halaman dimuat
        window.addEventListener('DOMContentLoaded', () => {
            ['color_header_bg', 'color_header_text', 'color_footer_bg', 'color_footer_text', 'color_primary']
                .forEach(syncPreview);
        });
    </script>

@endsection