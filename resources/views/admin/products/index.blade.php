@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Produk')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="font-marker text-lg text-jago">Isi rak toko</p>
            <p class="text-sm text-espresso/60">Tambah biji baru, atur harga per gramasi, dan sembunyikan yang sedang kosong.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-jago btn-sm">+ Tambah Produk</a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="mt-5 flex flex-wrap gap-2">
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama atau asal kopi…" class="input-jago max-w-xs">
        <button type="submit" class="btn btn-outline btn-sm">Cari</button>
        @if ($search !== '')
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm border-espresso/10">Reset</a>
        @endif
    </form>

    <div class="mt-5 overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80">
        @if ($products->isEmpty())
            <div class="px-5 py-16 text-center">
                <p class="font-marker text-xl text-espresso/50">Belum ada produk yang cocok.</p>
                <p class="mt-1 text-xs text-espresso/45">Coba kata kunci lain atau tambah produk baru.</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-jago btn-sm mt-4">Tambah Produk</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-karung-dark/30 text-[10px] uppercase tracking-widest text-espresso/50">
                        <tr>
                            <th class="px-5 py-3 font-extrabold">Produk</th>
                            <th class="px-3 py-3 font-extrabold">Kategori</th>
                            <th class="px-3 py-3 font-extrabold">Jenis / Sangrai</th>
                            <th class="px-3 py-3 font-extrabold">Varian</th>
                            <th class="px-3 py-3 font-extrabold">Harga mulai</th>
                            <th class="px-3 py-3 font-extrabold">Status</th>
                            <th class="px-5 py-3 text-right font-extrabold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-espresso/10">
                        @foreach ($products as $product)
                            <tr class="align-top hover:bg-white/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-lg border border-espresso/10 bg-karung-dark/40">
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                            @else
                                                <span class="font-display text-sm text-espresso/40">{{ mb_substr($product->name, 0, 2) }}</span>
                                            @endif
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-espresso">{{ $product->name }}</p>
                                            <p class="text-[10px] font-semibold text-espresso/45">{{ $product->origin }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-xs font-semibold text-espresso/70">{{ $product->category?->name }}</td>
                                <td class="px-3 py-3 text-xs font-semibold text-espresso/70">{{ $product->type }} · {{ $product->roast_level }}</td>
                                <td class="px-3 py-3 text-xs font-bold text-espresso">{{ $product->variants_count }} berat</td>
                                <td class="px-3 py-3 text-xs font-extrabold text-jago">
                                    {{ $product->min_price ? 'Rp '.number_format($product->min_price, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-3 py-3">
                                    @if ($product->is_active)
                                        <span class="rounded-full bg-daun/15 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wide text-daun">Tayang</span>
                                    @else
                                        <span class="rounded-full bg-espresso/10 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wide text-espresso/50">Disembunyikan</span>
                                    @endif
                                    @if ($product->is_featured)
                                        <span class="mt-1 block w-fit rounded-full bg-stiker/40 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wide text-espresso">Pilihan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('catalog.show', $product) }}" target="_blank" rel="noopener" class="rounded-lg border border-espresso/15 px-2 py-1 text-[11px] font-bold text-espresso/60 hover:border-espresso/30">Lihat</a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-jago/30 bg-jago/5 px-2 py-1 text-[11px] font-bold text-jago hover:bg-jago/10">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Hapus produk {{ $product->name }}? Varian dan gambarnya ikut terhapus.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-jago/20 px-2 py-1 text-[11px] font-bold text-jago/70 hover:bg-jago/10">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-5">{{ $products->links() }}</div>
@endsection
