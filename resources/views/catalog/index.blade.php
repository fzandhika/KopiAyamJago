@extends('layouts.app')

@section('title', 'Katalog Biji Kopi — Kopi Ayam Jago')
@section('description', 'Jelajahi biji kopi Robusta, Arabika, dan Luwak Ayam Jago. Filter berdasarkan jenis, asal daerah, tingkat sangrai, dan harga.')

@section('content')
    <section class="border-b border-espresso/10 bg-karung-dark/30">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:py-16">
            <nav class="text-[11px] font-bold uppercase tracking-widest text-espresso/45" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-jago">Beranda</a>
                <span class="mx-2">/</span>
                <span class="text-espresso/70">Katalog</span>
            </nav>

            <div class="mt-4 flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="font-marker text-lg text-jago">Isi rak toples kami</p>
                    <h1 class="mt-1 text-4xl leading-none text-espresso sm:text-5xl">Katalog Biji Kopi</h1>
                    <p class="mt-3 text-sm leading-relaxed text-espresso/65 sm:text-base">
                        Semua biji disangrai mingguan dan bisa dibeli mulai 100 gram. Pilih jenis, asal, atau
                        langsung cari nama yang kamu hafal.
                    </p>
                </div>

                <form action="{{ route('catalog.index') }}" method="GET" class="w-full max-w-md">
                    @if (($filters['type'] ?? null))
                        <input type="hidden" name="type" value="{{ $filters['type'] }}">
                    @endif
                    <label for="q" class="sr-only">Cari biji kopi</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <svg viewBox="0 0 20 20" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-espresso/40" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="9" r="6"/><path d="m13.5 13.5 4 4" stroke-linecap="round"/></svg>
                            <input id="q" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Cari Sidikalang, Toraja, Luwak…"
                                   class="input-jago pl-9">
                        </div>
                        <button type="submit" class="btn btn-jago shrink-0">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="grid gap-8 lg:grid-cols-[260px_1fr]">
            {{-- Filter --}}
            <aside>
                <details class="group rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/70 lg:hidden" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 font-display text-base text-espresso">
                        Filter & Urutkan
                        <span class="text-jago transition-transform group-open:rotate-180" aria-hidden="true">▾</span>
                    </summary>
                    <div class="border-t border-espresso/10 px-4 py-4">
                        @include('catalog.partials.filters')
                    </div>
                </details>

                <div class="sticky top-28 hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/70 p-5 lg:block">
                    <p class="font-display text-lg text-espresso">Filter</p>
                    <div class="mt-4">
                        @include('catalog.partials.filters')
                    </div>
                </div>
            </aside>

            {{-- Hasil --}}
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-espresso/60">
                        Menampilkan <span class="font-extrabold text-espresso">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span>
                        dari <span class="font-extrabold text-espresso">{{ $products->total() }}</span> biji kopi
                    </p>

                    <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2">
                        @foreach (['q', 'type', 'origin', 'roast', 'min_price', 'max_price'] as $key)
                            @if (! empty($filters[$key]))
                                <input type="hidden" name="{{ $key }}" value="{{ $filters[$key] }}">
                            @endif
                        @endforeach
                        <label for="sort" class="text-[11px] font-bold uppercase tracking-widest text-espresso/50">Urutkan</label>
                        <select id="sort" name="sort" onchange="this.form.submit()" class="input-jago w-auto py-1.5 text-xs font-bold">
                            <option value="featured" @selected(($filters['sort'] ?? 'featured') === 'featured')>Pilihan Jago</option>
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Terbaru</option>
                            <option value="cheapest" @selected(($filters['sort'] ?? '') === 'cheapest')>Harga termurah</option>
                            <option value="expensive" @selected(($filters['sort'] ?? '') === 'expensive')>Harga termahal</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="mt-8 rounded-2xl border-2 border-dashed border-espresso/20 bg-white/40 px-6 py-16 text-center">
                        <p class="font-display text-2xl text-espresso">Toplesnya kosong untuk filter ini.</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-espresso/60">
                            Coba longgarkan filter atau reset pencarian. Kalau tetap nggak ketemu, chat kami —
                            biasanya ada stok yang belum sempat masuk rak.
                        </p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-jago mt-6">Reset Filter</a>
                    </div>
                @else
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
