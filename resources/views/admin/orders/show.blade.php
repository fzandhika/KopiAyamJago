@extends('layouts.admin')

@section('title', 'Pesanan '.$order->order_code)
@section('heading', 'Detail Pesanan')

@php
    $waPhone = preg_replace('/^0/', '62', preg_replace('/\D/', '', $order->phone));
    $waText = rawurlencode('Halo '.$order->customer_name.', ini admin Kopi Ayam Jago soal pesanan '.$order->order_code.'. ');
@endphp

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-espresso/45 hover:text-jago">← Semua pesanan</a>
            <p class="mt-2 font-marker text-lg text-jago">{{ $order->order_code }}</p>
            <p class="text-sm text-espresso/60">Dibuat {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <span @class([
            'rounded-full border-2 px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wide',
            'border-stiker bg-stiker/30 text-espresso' => $order->status === 'pending',
            'border-daun/30 bg-daun/10 text-daun' => in_array($order->status, ['confirmed', 'shipped']),
            'border-sangrai bg-sangrai text-karung' => $order->status === 'completed',
            'border-jago/30 bg-jago/10 text-jago' => $order->status === 'cancelled',
        ])>{{ $order->statusLabel() }}</span>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_360px] xl:items-start">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80">
                <div class="border-b border-espresso/10 px-5 py-4">
                    <h2 class="font-display text-lg text-espresso">Item pesanan</h2>
                </div>
                <ul class="divide-y divide-espresso/10">
                    @foreach ($order->items as $item)
                        <li class="flex items-start justify-between gap-4 px-5 py-4">
                            <div>
                                <p class="font-extrabold text-espresso">{{ $item->product_name }}</p>
                                <p class="mt-0.5 text-[11px] font-semibold text-espresso/50">
                                    {{ $item->weight_gram }} g · {{ $item->grindLabel() }} · {{ $item->qty }}×
                                </p>
                                <p class="mt-0.5 text-[10px] text-espresso/40">Harga satuan {{ 'Rp '.number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="shrink-0 font-extrabold text-jago">{{ 'Rp '.number_format($item->subtotal, 0, ',', '.') }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="flex items-end justify-between border-t-2 border-dashed border-espresso/15 px-5 py-4">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Metode bayar</p>
                        <p class="text-sm font-bold text-espresso">{{ $order->paymentLabel() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Total</p>
                        <p class="font-display text-2xl text-jago">{{ $order->formattedTotal() }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                <h2 class="font-display text-lg text-espresso">Data pengiriman</h2>
                <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Nama</dt>
                        <dd class="font-bold text-espresso">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Telepon</dt>
                        <dd class="font-bold text-espresso">{{ $order->phone }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Alamat</dt>
                        <dd class="leading-relaxed text-espresso/75">{{ $order->address }}</dd>
                    </div>
                    @if ($order->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Catatan pembeli</dt>
                            <dd class="leading-relaxed text-espresso/75">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                <h2 class="font-display text-lg text-espresso">Ubah status</h2>
                <p class="text-[11px] font-semibold text-espresso/45">Status hanya catatan internal, tidak mengirim notifikasi otomatis.</p>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-3 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="input-jago">
                        @foreach (\App\Models\Order::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    <button type="submit" class="btn btn-jago btn-sm w-full">Simpan Status</button>
                </form>
            </section>

            <section class="rounded-2xl border-2 border-daun/25 bg-daun/10 p-5">
                <h2 class="font-display text-lg text-espresso">Hubungi pembeli</h2>
                <p class="mt-1 text-[11px] leading-relaxed font-semibold text-espresso/60">Konfirmasi stok, ongkir, dan pembayaran lewat WhatsApp.</p>
                <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm mt-3 w-full">Chat {{ $order->customer_name }}</a>
            </section>

            <section class="rounded-2xl border border-espresso/10 bg-white/40 p-5 text-xs leading-relaxed text-espresso/60">
                <p class="font-extrabold text-espresso">Alur aman</p>
                <ol class="mt-2 list-decimal space-y-1 pl-4">
                    <li>Chat pembeli dan pastikan stok masih ada.</li>
                    <li>Kirim total akhir termasuk ongkir.</li>
                    <li>Setelah bayar, ubah status ke Dikonfirmasi.</li>
                    <li>Kirim paket, lalu ubah ke Dikirim.</li>
                </ol>
            </section>
        </aside>
    </div>
@endsection
