@php
    $storeName = \App\Models\Setting::get('store_name');
    $waLink = \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau tanya soal biji kopi.');
    $cartCount = \App\Cart::count();
    $openHours = \App\Models\Setting::get('open_hours');
    $address = \App\Models\Setting::get('address');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kopi Ayam Jago — Kopi Serius, Harga Jujur')</title>
    <meta name="description" content="@yield('description', 'Biji kopi Robusta, Arabika, dan Luwak pilihan dari Sidikalang sampai Toraja. Warung kopi kampung Jakarta, harga jujur, sangrai mingguan.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#B3201F">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $storeName }}">
    <meta property="og:title" content="@yield('title', 'Kopi Ayam Jago — Kopi Serius, Harga Jujur')">
    <meta property="og:description" content="@yield('description', 'Biji kopi Robusta, Arabika, dan Luwak pilihan dari Sidikalang sampai Toraja.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='12' fill='%23B3201F'/%3E%3Ctext x='32' y='43' text-anchor='middle' font-family='Georgia,serif' font-size='28' font-weight='bold' fill='%23F6B820'%3EAJ%3C/text%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Caveat+Brush&family=Nunito+Sans:ital,opsz,wght@0,6..12,400;0,6..12,600;0,6..12,700;0,6..12,800;1,6..12,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="paper min-h-screen antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded focus:bg-sangrai focus:px-4 focus:py-2 focus:text-karung">
        Lompat ke konten
    </a>

    {{-- Bar promo --}}
    <div class="bg-sangrai text-karung">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-1.5 text-[11px] font-semibold tracking-wide sm:text-xs">
            <p class="truncate">{{ \App\Models\Setting::get('free_shipping_note') }}</p>
            <p class="hidden shrink-0 items-center gap-1.5 text-karung/70 sm:flex">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-daun"></span>
                Buka {{ $openHours }}
            </p>
        </div>
    </div>

    {{-- Navigasi --}}
    <header class="sticky top-0 z-50 border-b border-sangrai/10 bg-karung/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5" aria-label="{{ $storeName }} — beranda">
                <span class="relative grid h-11 w-11 place-items-center rounded-lg border-2 border-jago bg-sangrai shadow-[3px_3px_0_0_#B3201F]">
                    <svg viewBox="0 0 32 32" class="h-7 w-7" aria-hidden="true">
                        <path d="M16 4c1.7 0 3 1.2 3.3 2.8l2.2-.9-.8 2.3 2.4.7-1.8 1.6c1.2 1.4 1.9 3.2 1.9 5.2 0 4.7-3.8 8.5-8.4 8.5S6.4 20.4 6.4 15.7c0-.7.1-1.4.3-2L4 14.8l1.9-2.4-2.3-1.6 2.8-.4L5.6 7l2.9 1L9.9 5l2 1.9L13.2 4l1.1 2.4L16 4z" fill="#F6B820"/>
                        <circle cx="18.4" cy="12.6" r="1.15" fill="#1E1410"/>
                        <path d="M8 22.5c-.9 1.7-1.2 3.5-1 5.5l2.2-1.6.6 2.8 1.7-2.2 1.1 2.2 1.2-2.2 1.7 2.2.6-2.8 2.2 1.6c.2-2-.1-3.8-1-5.5H8z" fill="#B3201F"/>
                    </svg>
                </span>
                <span class="leading-none">
                    <span class="block font-marker text-[11px] tracking-[0.25em] text-jago">KOPI</span>
                    <span class="block font-display text-[19px] tracking-tight text-sangrai transition-colors group-hover:text-jago">AYAM JAGO</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                @foreach ([
                    ['route' => 'home', 'label' => 'Beranda'],
                    ['route' => 'catalog.index', 'label' => 'Katalog'],
                    ['route' => 'story', 'label' => 'Cerita Kami'],
                    ['route' => 'contact', 'label' => 'Kontak & Lokasi'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'rounded-md px-3 py-2 text-sm font-bold transition-colors',
                           'bg-sangrai text-karung' => request()->routeIs($item['route']),
                           'text-espresso/80 hover:bg-sangrai/5 hover:text-jago' => ! request()->routeIs($item['route']),
                       ])>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('cart.index') }}" class="relative grid h-10 w-10 place-items-center rounded-lg border-2 border-sangrai/15 bg-white/50 text-espresso transition hover:border-jago hover:text-jago" aria-label="Keranjang ({{ $cartCount }} item)">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.4a2 2 0 0 0 2-1.5L20 7H6" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9.5" cy="19.5" r="1.4" fill="currentColor" stroke="none"/>
                        <circle cx="16.5" cy="19.5" r="1.4" fill="currentColor" stroke="none"/>
                    </svg>
                    @if ($cartCount > 0)
                        <span class="absolute -right-1.5 -top-1.5 grid h-5 min-w-5 place-items-center rounded-full bg-jago px-1 text-[10px] font-extrabold text-white">{{ $cartCount }}</span>
                    @endif
                </a>

                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-wa hidden sm:inline-flex">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.7c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.1 0 1.2.9 2.4 1 2.5.1.2 1.7 2.7 4.2 3.7 2 .8 2.4.7 2.9.6.4 0 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1 0-.1-.2-.2-.4-.3Z"/></svg>
                    Pesan via WA
                </a>

                <button type="button" data-nav-toggle aria-expanded="false" aria-controls="nav-mobile"
                        class="grid h-10 w-10 place-items-center rounded-lg border-2 border-sangrai/15 text-espresso md:hidden">
                    <span class="sr-only">Buka menu</span>
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>

        <div id="nav-mobile" data-nav-panel class="hidden border-t border-sangrai/10 bg-karung md:hidden">
            <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3" aria-label="Navigasi mobile">
                @foreach ([
                    ['route' => 'home', 'label' => 'Beranda'],
                    ['route' => 'catalog.index', 'label' => 'Katalog'],
                    ['route' => 'cart.index', 'label' => 'Keranjang'],
                    ['route' => 'story', 'label' => 'Cerita Kami'],
                    ['route' => 'contact', 'label' => 'Kontak & Lokasi'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" class="rounded-md px-3 py-2.5 text-sm font-bold text-espresso hover:bg-sangrai/5">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-wa mt-2">Pesan via WhatsApp</a>
            </nav>
        </div>
    </header>

    @if (session('success') || session('error'))
        <div class="fixed inset-x-0 top-24 z-[55] mx-auto flex w-full max-w-md flex-col gap-2 px-4">
            @foreach (['success' => 'bg-daun', 'error' => 'bg-jago'] as $flashType => $flashColor)
                @if (session($flashType))
                    <div data-flash class="flex items-start gap-3 rounded-xl border-2 border-sangrai {{ $flashColor }} px-4 py-3 text-sm font-semibold text-white shadow-[4px_4px_0_0_#1E1410] transition-all duration-200">
                        <span class="mt-0.5">{{ $flashType === 'success' ? '✓' : '!' }}</span>
                        <p class="flex-1">{{ session($flashType) }}</p>
                        <button type="button" data-flash-close class="shrink-0 text-white/70 hover:text-white" aria-label="Tutup notifikasi">✕</button>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <main id="konten">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 bg-sangrai text-karung/80">
        <div class="border-b border-white/10 bg-jago">
            <p class="mx-auto max-w-7xl px-4 py-4 text-center font-marker text-xl text-stiker sm:text-2xl">
                “Gue nggak pernah bercanda soal KOPI!”
            </p>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="font-marker text-sm tracking-[0.25em] text-stiker">KOPI</p>
                <p class="font-display text-2xl text-karung">{{ $storeName }}</p>
                <p class="mt-3 max-w-xs text-sm leading-relaxed">
                    Warung kopi kampung Jakarta berjiwa Pecinan. Garang seperti ayam jago, tapi hangat dan jujur soal kualitas.
                </p>
            </div>

            <div>
                <h2 class="font-display text-sm uppercase tracking-widest text-stiker">Jelajahi</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-stiker">Semua Biji Kopi</a></li>
                    <li><a href="{{ route('catalog.index', ['type' => 'Robusta']) }}" class="hover:text-stiker">Robusta</a></li>
                    <li><a href="{{ route('catalog.index', ['type' => 'Arabika']) }}" class="hover:text-stiker">Arabika</a></li>
                    <li><a href="{{ route('catalog.index', ['type' => 'Luwak']) }}" class="hover:text-stiker">Luwak</a></li>
                    <li><a href="{{ route('story') }}" class="hover:text-stiker">Cerita Kami</a></li>
                </ul>
            </div>

            <div>
                <h2 class="font-display text-sm uppercase tracking-widest text-stiker">Mampir</h2>
                <address class="mt-3 space-y-2 text-sm not-italic leading-relaxed">
                    <p>{{ $address }}</p>
                    <p>{{ $openHours }}</p>
                    <p><a href="{{ $waLink }}" target="_blank" rel="noopener" class="font-bold text-karung hover:text-stiker">WhatsApp: {{ \App\Models\Setting::get('whatsapp_number') }}</a></p>
                    <p><a href="{{ \App\Models\Setting::get('maps_url') }}" target="_blank" rel="noopener" class="underline decoration-stiker/50 underline-offset-4 hover:text-stiker">Buka di Google Maps</a></p>
                </address>
            </div>

            <div>
                <h2 class="font-display text-sm uppercase tracking-widest text-stiker">Bayar & Kirim</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    <li>Transfer bank: {{ \App\Models\Setting::get('bank_account') }}</li>
                    <li>QRIS tersedia di kasir</li>
                    <li>Pesan langsung via WhatsApp</li>
                    <li>Kemasan valve, sangrai mingguan</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-xs text-karung/50 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ now()->year }} {{ $storeName }}. Dibuat dengan jujur, tanpa lorem ipsum.</p>
                <div class="flex gap-4">
                    <a href="{{ route('sitemap') }}" class="hover:text-stiker">Sitemap</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-stiker">Panel Admin</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Tombol WhatsApp melayang --}}
    <a href="{{ $waLink }}" target="_blank" rel="noopener"
       class="btn btn-wa fixed bottom-4 right-4 z-40 rounded-full shadow-[0_10px_30px_-8px_rgba(30,20,16,0.7)]">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.7c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.1 0 1.2.9 2.5 1 2.5.1.2 1.7 2.7 4.2 3.7 2 .8 2.4.7 2.9.6.4 0 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1 0-.1-.2-.2-.4-.3Z"/></svg>
        <span class="hidden sm:inline">Pesan via WA</span>
        <span class="sm:hidden">WA</span>
    </a>

    @stack('scripts')
</body>
</html>
