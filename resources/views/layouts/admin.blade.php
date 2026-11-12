@php
    $admin = auth()->user();
    $navItems = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M4 13h6V4H4v9Zm10 7h6V11h-6v9ZM4 20h6v-4H4v4Zm10-13h6V4h-6v3Z'],
        ['route' => 'admin.products.index', 'label' => 'Produk', 'icon' => 'M20 7 12 3 4 7v10l8 4 8-4V7Zm-8 2 6-3M4 7l8 4m0 10V11'],
        ['route' => 'admin.categories.index', 'label' => 'Kategori', 'icon' => 'M4 6h16M4 12h16M4 18h10'],
        ['route' => 'admin.orders.index', 'label' => 'Pesanan', 'icon' => 'M6 3h12l2 5v13H4V8l2-5Zm0 5h12M9 13h6'],
        ['route' => 'admin.settings.edit', 'label' => 'Pengaturan', 'icon' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8-3a8 8 0 0 1-.2 1.7l2 1.6-2 3.4-2.4-1a8 8 0 0 1-2.9 1.7L14 22h-4l-.4-2.6a8 8 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A8 8 0 0 1 4 12c0-.6.1-1.2.2-1.7l-2-1.6 2-3.4 2.4 1A8 8 0 0 1 9.5 4.6L10 2h4l.5 2.6a8 8 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.1.5.2 1.1.2 1.7Z'],
        ['route' => 'admin.password.edit', 'label' => 'Keamanan', 'icon' => 'M14 7a4 4 0 1 1-3.2 6.4L4 20v-3h3v-3h3l1.3-1.3A4 4 0 0 1 14 7Zm2 1.5h.01'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Panel Admin') — Kopi Ayam Jago</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='12' fill='%23B3201F'/%3E%3Ctext x='32' y='43' text-anchor='middle' font-family='Georgia,serif' font-size='28' font-weight='bold' fill='%23F6B820'%3EAJ%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Caveat+Brush&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside data-admin-nav class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full bg-sangrai text-karung transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <div class="flex h-full flex-col">
                <div class="border-b border-white/10 px-5 py-5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <span class="grid h-9 w-9 place-items-center rounded-lg border-2 border-jago bg-karung/5">
                            <svg viewBox="0 0 32 32" class="h-6 w-6" aria-hidden="true">
                                <path d="M16 4c1.7 0 3 1.2 3.3 2.8l2.2-.9-.8 2.3 2.4.7-1.8 1.6c1.2 1.4 1.9 3.2 1.9 5.2 0 4.7-3.8 8.5-8.4 8.5S6.4 20.4 6.4 15.7c0-.7.1-1.4.3-2L4 14.8l1.9-2.4-2.3-1.6 2.8-.4L5.6 7l2.9 1L9.9 5l2 1.9L13.2 4l1.1 2.4L16 4z" fill="#F6B820"/>
                                <circle cx="18.4" cy="12.6" r="1.15" fill="#1E1410"/>
                            </svg>
                        </span>
                        <span class="leading-none">
                            <span class="block font-marker text-[10px] tracking-[0.25em] text-stiker">PANEL</span>
                            <span class="block font-display text-base text-karung">AYAM JAGO</span>
                        </span>
                    </a>
                </div>

                <nav class="flex-1 space-y-1 px-3 py-4" aria-label="Navigasi admin">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition',
                               'bg-jago text-white' => request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])),
                               'text-karung/70 hover:bg-white/5 hover:text-karung' => ! request()->routeIs($item['route']) && ! request()->routeIs(str_replace('.index', '.*', $item['route'])),
                           ])>
                            <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $item['icon'] }}"/></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-white/10 p-4">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center justify-between text-xs font-bold text-karung/60 hover:text-stiker">
                        Lihat toko
                        <span aria-hidden="true">↗</span>
                    </a>
                    <div class="mt-4 rounded-lg bg-white/5 px-3 py-2.5">
                        <p class="truncate text-xs font-extrabold text-karung">{{ $admin?->name }}</p>
                        <p class="truncate text-[10px] text-karung/50">{{ $admin?->email }}</p>
                        <a href="{{ route('admin.password.edit') }}" class="mt-1 block text-[11px] font-bold text-karung/60 hover:text-stiker">Ganti password</a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-[11px] font-bold text-stiker hover:underline">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-espresso/10 bg-karung/95 px-4 py-3 backdrop-blur lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" data-admin-nav-toggle class="grid h-9 w-9 place-items-center rounded-lg border-2 border-espresso/15 lg:hidden" aria-label="Buka menu admin">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Panel Admin</p>
                        <h1 class="font-display text-lg leading-tight text-espresso sm:text-xl">@yield('heading', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="hidden rounded-lg border-2 border-espresso/15 px-3 py-1.5 text-xs font-bold text-espresso/70 hover:border-jago hover:text-jago sm:block">Lihat website</a>
                    <span class="hidden h-9 w-9 place-items-center rounded-full bg-jago font-display text-sm text-karung sm:grid">{{ mb_substr($admin?->name ?? 'A', 0, 1) }}</span>
                </div>
            </header>

            @if (session('success') || session('error'))
                <div class="px-4 pt-4 lg:px-8">
                    @foreach (['success' => 'border-daun bg-daun/10 text-daun', 'error' => 'border-jago bg-jago/10 text-jago'] as $type => $classes)
                        @if (session($type))
                            <div data-flash class="flex items-start gap-3 rounded-xl border-2 {{ $classes }} px-4 py-3 text-sm font-semibold transition-all">
                                <span class="flex-1">{{ session($type) }}</span>
                                <button type="button" data-flash-close class="text-current/60" aria-label="Tutup">✕</button>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <main class="px-4 py-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
