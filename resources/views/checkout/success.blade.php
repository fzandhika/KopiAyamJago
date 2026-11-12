@extends('layouts.app')

@section('title', 'Pesanan '.$order->order_code.' — Kopi Ayam Jago')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16">
        <div class="rounded-3xl border-2 border-sangrai bg-[#fffaf0] p-6 shadow-[8px_8px_0_0_#1E1410] sm:p-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="font-marker text-lg text-jago">Pesanan tercatat</p>
                    <h1 class="mt-1 text-3xl leading-none text-espresso sm:text-4xl">Terima kasih, {{ explode(' ', $order->customer_name)[0] }}!</h1>
                </div>
                <div class="rounded-lg border-2 border-emas bg-karung px-3 py-2 text-center">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-espresso/50">Kode pesanan</p>
                    <p class="font-display text-base text-jago">{{ $order->order_code }}</p>
                </div>
            </div>

            <p class="mt-4 max-w-xl text-sm leading-relaxed text-espresso/70">
                Pesananmu sudah masuk buku. Langkah terakhir: konfirmasi via WhatsApp biar kami cek stok,
                hitung ongkir, dan kirim detail pembayaran. Kalau belum chat, pesanan belum diproses, ya.
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                @foreach ([
                    ['1', 'Konfirmasi via WA', 'Kirim kode pesanan ke admin'],
                    ['2', 'Bayar', 'Transfer, QRIS, atau tunai di toko'],
                    ['3', 'Dikemas & dikirim', 'Sangrai baru, dikemas hari itu'],
                ] as [$step, $title, $description])
                    <div class="rounded-xl border border-espresso/10 bg-white/50 p-4">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-jago font-display text-xs text-karung">{{ $step }}</span>
                        <p class="mt-3 text-sm font-extrabold text-espresso">{{ $title }}</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-espresso/55">{{ $description }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-7 rounded-2xl border border-espresso/10">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-espresso/10 px-4 py-3">
                    <p class="font-display text-sm uppercase tracking-widest text-espresso">Rincian pesanan</p>
                    <span class="rounded-full bg-stiker/25 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-espresso">{{ $order->statusLabel() }}</span>
                </div>
                <ul class="divide-y divide-espresso/10">
                    @foreach ($order->items as $item)
                        <li class="flex items-start justify-between gap-3 px-4 py-3">
                            <div>
                                <p class="text-sm font-extrabold text-espresso">{{ $item->product_name }}</p>
                                <p class="text-[11px] font-semibold text-espresso/50">
                                    {{ $item->weight_gram }} g · {{ $item->grindLabel() }} · {{ $item->qty }}×
                                </p>
                            </div>
                            <p class="text-sm font-extrabold text-jago">{{ 'Rp '.number_format($item->subtotal, 0, ',', '.') }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="flex items-end justify-between border-t-2 border-dashed border-espresso/15 px-4 py-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-espresso/45">Metode</p>
                        <p class="text-xs font-bold text-espresso">{{ $order->paymentLabel() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-espresso/45">Total sementara</p>
                        <p class="font-display text-2xl text-jago">{{ $order->formattedTotal() }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-xl bg-karung-dark/40 p-4 text-xs leading-relaxed text-espresso/65">
                <p><span class="font-extrabold text-espresso">Dikirim ke:</span> {{ $order->customer_name }} · {{ $order->phone }}</p>
                <p class="mt-1">{{ $order->address }}</p>
                @if ($order->notes)
                    <p class="mt-1"><span class="font-extrabold text-espresso">Catatan:</span> {{ $order->notes }}</p>
                @endif
            </div>

            <div class="mt-7 flex flex-col gap-2 sm:flex-row">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-wa flex-1 py-3.5">Konfirmasi via WhatsApp</a>
                <a href="{{ route('catalog.index') }}" class="btn btn-outline">Lanjut Belanja</a>
            </div>

            <p class="mt-4 text-center text-[11px] font-semibold text-espresso/45">
                Simpan halaman ini atau catat kode <span class="font-extrabold text-espresso">{{ $order->order_code }}</span> untuk cek pesanan.
            </p>
        </div>
    </div>
@endsection
