@extends('layouts.app')

@php
    $defaultVariant = $product->starter_variant;
    $taste = $product->taste_profile;
@endphp

@section('title', $product->name.' — Kopi Ayam Jago')
@section('description', Str::limit($product->description, 155))
@section('og_type', 'product')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@'.'context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description,
            'brand' => ['@type' => 'Brand', 'name' => 'Kopi Ayam Jago'],
            'category' => $product->typeLabel(),
            'url' => route('catalog.show', $product),
            'offers' => $product->variants->map(fn ($variant) => [
                '@type' => 'Offer',
                'name' => $variant->label,
                'price' => $variant->price,
                'priceCurrency' => 'IDR',
                'availability' => $variant->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('catalog.show', $product),
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:py-12">
        <nav class="text-[11px] font-bold uppercase tracking-widest text-espresso/45" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-jago">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-jago">Katalog</a>
            <span class="mx-2">/</span>
            <span class="text-espresso/70">{{ $product->name }}</span>
        </nav>

        <div class="mt-6 grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-14">
            {{-- Visual --}}
            <div>
                <div class="paper-dark relative overflow-hidden rounded-3xl border-2 border-emas/50 px-6 py-10">
                    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                        <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-stiker/10 blur-3xl"></div>
                        <div class="absolute -right-6 top-6 h-24 w-24 rounded-full bg-[#7c5bff]/15 blur-2xl"></div>
                    </div>

                    <div class="relative mx-auto max-w-[280px]">
                        <x-product-jar :product="$product" :variant="$defaultVariant" />
                    </div>

                    <div class="relative mt-8 flex flex-wrap justify-center gap-2">
                        <span class="rounded-full border border-stiker/40 bg-stiker/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-stiker">{{ $product->typeLabel() }}</span>
                        <span class="rounded-full border border-karung/20 bg-karung/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-karung/70">Sangrai {{ $product->roast_level }}</span>
                        <span class="rounded-full border border-karung/20 bg-karung/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-widest text-karung/70">{{ $product->origin }}</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach ([
                        ['Sangrai', 'Mingguan'],
                        ['Kemasan', 'Valve'],
                        ['Gramasi', '100g–1kg'],
                    ] as [$label, $value])
                        <div class="rounded-xl border border-espresso/10 bg-white/40 p-3 text-center">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-espresso/45">{{ $label }}</p>
                            <p class="mt-1 text-sm font-extrabold text-espresso">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Detail & pembelian --}}
            <div>
                <p class="font-marker text-lg text-jago">{{ $product->category->name }}</p>
                <h1 class="mt-1 text-4xl leading-none text-espresso sm:text-5xl">{{ $product->name }}</h1>
                <p class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm font-semibold text-espresso/60">
                    <span>Asal {{ $product->origin }}</span>
                    <span class="h-1 w-1 rounded-full bg-espresso/30"></span>
                    <span>Sangrai {{ $product->roast_level }}</span>
                    <span class="h-1 w-1 rounded-full bg-espresso/30"></span>
                    <span class="{{ $product->variants->sum('stock') > 0 ? 'text-daun' : 'text-jago' }}">
                        {{ $product->variants->sum('stock') > 0 ? 'Stok tersedia' : 'Stok habis' }}
                    </span>
                </p>

                <p class="mt-5 text-sm leading-relaxed text-espresso/75 sm:text-base">{{ $product->description }}</p>

                <div class="mt-6 rounded-2xl border border-espresso/10 bg-white/40 p-5">
                    <div class="flex items-center justify-between">
                        <p class="font-display text-sm uppercase tracking-widest text-espresso">Profil Rasa</p>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-espresso/40">Skala 1–5</span>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <x-taste-bar label="Pahit" :value="$taste['bitterness']" />
                        <x-taste-bar label="Asam" :value="$taste['acidity']" />
                        <x-taste-bar label="Aroma" :value="$taste['aroma']" />
                        <x-taste-bar label="Body" :value="$taste['body']" />
                    </div>
                    <p class="mt-4 border-t border-dashed border-espresso/15 pt-3 text-sm italic text-espresso/70">
                        “{{ $taste['notes'] }}”
                    </p>
                </div>

                <form method="POST" action="{{ route('cart.add', $defaultVariant) }}" data-order-form class="mt-7 rounded-2xl border-2 border-espresso/10 bg-[#fffaf0] p-5 shadow-[6px_6px_0_0_rgba(200,150,46,0.22)]">
                    @csrf

                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-espresso/45">Harga</p>
                            <p data-price class="font-display text-3xl text-jago">{{ $defaultVariant?->formattedPrice() ?? '—' }}</p>
                        </div>
                        <p class="text-[11px] font-semibold text-espresso/50">Harga sudah termasuk kemasan valve</p>
                    </div>

                    <fieldset class="mt-5">
                        <legend class="label-jago">Pilih Berat</legend>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            @foreach ($product->variants as $variant)
                                <label @class(['cursor-pointer', 'opacity-40' => $variant->stock < 1])>
                                    <input type="radio" name="weight" value="{{ $variant->id }}" class="peer sr-only"
                                           data-price="{{ $variant->price }}" data-url="{{ route('cart.add', $variant) }}"
                                           @checked($variant->id === $defaultVariant?->id) @disabled($variant->stock < 1)>
                                    <span class="flex flex-col items-center rounded-lg border-2 border-espresso/15 px-2 py-2.5 text-center transition peer-checked:border-jago peer-checked:bg-jago peer-checked:text-karung">
                                        <span class="text-sm font-extrabold">{{ $variant->label }}</span>
                                        <span class="text-[10px] font-semibold opacity-70">{{ $variant->formattedPrice() }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="mt-4 grid gap-4 sm:grid-cols-[1fr_auto]">
                        <div>
                            <label for="grind" class="label-jago">Gilingan</label>
                            <select id="grind" name="grind" class="input-jago">
                                @foreach (\App\Models\OrderItem::GRIND_TYPES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <span class="label-jago">Jumlah</span>
                            <div class="flex items-center gap-1 rounded-lg border-2 border-espresso/20 bg-white px-1.5 py-1">
                                <button type="button" data-qty-minus class="grid h-8 w-8 place-items-center rounded text-lg font-bold text-espresso/60 hover:bg-sangrai/5" aria-label="Kurangi jumlah">−</button>
                                <input data-qty-input type="number" name="qty" value="1" min="1" max="20" class="w-10 border-0 bg-transparent text-center text-sm font-extrabold text-espresso focus:outline-none" aria-label="Jumlah">
                                <button type="button" data-qty-plus class="grid h-8 w-8 place-items-center rounded text-lg font-bold text-espresso/60 hover:bg-sangrai/5" aria-label="Tambah jumlah">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                        <button type="submit" class="btn btn-jago flex-1 py-3.5 text-base" @disabled($product->variants->sum('stock') < 1)>
                            Pesen Sekarang
                        </button>
                        <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau tanya '.$product->name.'.') }}" target="_blank" rel="noopener" class="btn btn-wa sm:w-auto">
                            Tanya via WA
                        </a>
                    </div>

                    <ul class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-[11px] font-semibold text-espresso/55">
                        <li>✓ Bisa pilih biji utuh atau digiling</li>
                        <li>✓ Dikemas hari yang sama</li>
                        <li>✓ Bisa ambil di toko</li>
                    </ul>
                </form>

                <div class="mt-6 divide-y divide-espresso/10 rounded-xl border border-espresso/10 bg-white/30">
                    @foreach ([
                        ['Cara seduh yang disarankan', 'Untuk sangrai '.$product->roast_level.', mulai dari rasio 1:15 (1 gram kopi : 15 ml air) di suhu 90–93°C. Sesuaikan grind sesuai alat: kasar untuk French press, medium untuk V60, halus untuk espresso atau tubruk.'],
                        ['Penyimpanan', 'Simpan di kemasan valve, jauh dari sinar matahari. Biji utuh bertahan 4–6 minggu setelah sangrai; gilingan sebaiknya dihabiskan dalam 2 minggu.'],
                        ['Pengiriman', \App\Models\Setting::get('free_shipping_note')],
                    ] as [$title, $body])
                        <details class="group px-4 py-3">
                            <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-extrabold text-espresso">
                                {{ $title }}
                                <span class="text-jago transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                            </summary>
                            <p class="mt-2 text-xs leading-relaxed text-espresso/65">{{ $body }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="mt-20">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="font-marker text-lg text-jago">Satu keluarga</p>
                        <h2 class="mt-1 text-2xl text-espresso sm:text-3xl">Biji {{ $product->typeLabel() }} lainnya</h2>
                    </div>
                    <a href="{{ route('catalog.index', ['type' => $product->type]) }}" class="text-sm font-extrabold text-jago underline decoration-2 underline-offset-4">Lihat semua →</a>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
