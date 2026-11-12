@extends('layouts.app')

@section('title', 'Keranjang — Kopi Ayam Jago')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:py-14">
        <nav class="text-[11px] font-bold uppercase tracking-widest text-espresso/45" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-jago">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-espresso/70">Keranjang</span>
        </nav>

        <div class="mt-3 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="font-marker text-lg text-jago">Belanjaan kamu</p>
                <h1 class="mt-1 text-4xl leading-none text-espresso">Keranjang</h1>
            </div>
            <p class="text-sm font-semibold text-espresso/55">{{ $items->sum('qty') }} item · {{ count($items) }} baris pesanan</p>
        </div>

        @if ($items->isEmpty())
            <div class="mt-8 rounded-3xl border-2 border-dashed border-espresso/20 bg-white/40 px-6 py-16 text-center">
                <div class="mx-auto max-w-[140px] opacity-70">
                    <div class="jar-lid mx-auto h-4 w-[55%]"></div>
                    <div class="jar-body -mt-1 aspect-[5/6]">
                        <div class="jar-coffee absolute inset-x-0 bottom-0 h-[35%]"></div>
                    </div>
                </div>
                <p class="mt-6 font-display text-2xl text-espresso">Keranjang masih kosong.</p>
                <p class="mx-auto mt-2 max-w-md text-sm text-espresso/60">
                    Rak toples kami penuh. Pilih dulu biji yang mau kamu coba — mulai dari 100 gram, nggak ada salahnya.
                </p>
                <a href="{{ route('catalog.index') }}" class="btn btn-jago mt-6">Lihat Biji Kopi</a>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_360px] lg:items-start">
                <div class="space-y-4">
                    @foreach ($items as $key => $item)
                        <article class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-4 sm:p-5">
                            <div class="flex gap-4">
                                <a href="{{ route('catalog.show', $item['product']) }}" class="w-20 shrink-0 sm:w-24">
                                    <x-product-jar :product="$item['product']" :variant="$item['variant']" compact />
                                </a>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-daun">{{ $item['product']->typeLabel() }} · {{ $item['product']->origin }}</p>
                                            <h2 class="mt-0.5 font-display text-lg leading-tight text-espresso">
                                                <a href="{{ route('catalog.show', $item['product']) }}" class="hover:text-jago">{{ $item['product']->name }}</a>
                                            </h2>
                                            <p class="mt-1 text-xs font-semibold text-espresso/55">{{ $item['variant']->label }} · {{ $grindTypes[$item['grind']] }}</p>
                                        </div>
                                        <p class="font-display text-lg text-jago">{{ 'Rp '.number_format($item['subtotal'], 0, ',', '.') }}</p>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-end justify-between gap-3 border-t border-dashed border-espresso/15 pt-3">
                                        <form method="POST" action="{{ route('cart.update', $item['variant']) }}" class="flex flex-wrap items-end gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="original_grind" value="{{ $item['grind'] }}">
                                            <div>
                                                <label for="grind-{{ $item['variant']->id }}-{{ $item['grind'] }}" class="label-jago mb-1 text-[10px]">Gilingan</label>
                                                <select id="grind-{{ $item['variant']->id }}-{{ $item['grind'] }}" name="grind" class="input-jago w-auto py-1.5 text-xs">
                                                    @foreach ($grindTypes as $value => $label)
                                                        <option value="{{ $value }}" @selected($item['grind'] === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="qty-{{ $item['variant']->id }}-{{ $item['grind'] }}" class="label-jago mb-1 text-[10px]">Jumlah</label>
                                                <input id="qty-{{ $item['variant']->id }}-{{ $item['grind'] }}" type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="20" class="input-jago w-20 py-1.5 text-xs">
                                            </div>
                                            <button type="submit" class="btn btn-outline btn-sm">Perbarui</button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.remove', ['variant' => $item['variant'], 'grind' => $item['grind']]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-bold text-espresso/45 underline underline-offset-4 hover:text-jago">Hapus</button>
                                        </form>
                                    </div>

                                    <p class="mt-2 text-[11px] font-semibold {{ $item['variant']->stock > 0 ? 'text-espresso/45' : 'text-jago' }}">
                                        {{ $item['variant']->stock > 0 ? 'Stok tersisa '.$item['variant']->stock.' pack' : 'Stok habis, hubungi kami' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-espresso/60 hover:text-jago">
                        <span aria-hidden="true">←</span> Lanjut pilih biji kopi
                    </a>
                </div>

                <aside class="lg:sticky lg:top-28">
                    <div class="rounded-2xl border-2 border-sangrai bg-[#fffaf0] p-5 shadow-[6px_6px_0_0_#1E1410]">
                        <h2 class="font-display text-lg text-espresso">Ringkasan</h2>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between gap-3">
                                <dt class="text-espresso/60">Subtotal</dt>
                                <dd class="font-extrabold text-espresso">{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-espresso/60">Ongkir</dt>
                                <dd class="text-right text-[11px] font-semibold text-espresso/55">Dihitung saat konfirmasi</dd>
                            </div>
                        </dl>

                        <div class="mt-4 flex items-end justify-between border-t-2 border-dashed border-espresso/15 pt-4">
                            <span class="text-[11px] font-bold uppercase tracking-widest text-espresso/50">Total sementara</span>
                            <span class="font-display text-2xl text-jago">{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn btn-jago mt-5 w-full py-3">Lanjut ke Checkout</a>
                        <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau pesan: ') }}" target="_blank" rel="noopener" class="btn btn-wa mt-2 w-full">Pesan Langsung via WA</a>

                        <p class="mt-4 rounded-lg bg-daun/10 px-3 py-2 text-[11px] leading-relaxed font-semibold text-daun">
                            {{ \App\Models\Setting::get('free_shipping_note') }}
                        </p>
                    </div>

                    <ul class="mt-4 space-y-2 px-1 text-[11px] font-semibold text-espresso/50">
                        <li>✓ Pembayaran transfer, QRIS, atau konfirmasi via WhatsApp</li>
                        <li>✓ Bisa ambil sendiri di toko</li>
                        <li>✓ Dikemas dengan valve agar aroma terjaga</li>
                    </ul>
                </aside>
            </div>
        @endif
    </div>
@endsection
