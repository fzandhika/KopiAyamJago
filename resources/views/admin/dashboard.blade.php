@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="font-marker text-lg text-jago">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}</p>
            <p class="text-sm text-espresso/60">Ringkasan toko hari ini. Angka dihitung langsung dari database.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-jago btn-sm">+ Tambah Produk</a>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Total produk', number_format($stats['products'], 0, ',', '.'), 'termasuk yang nonaktif', 'bg-sangrai text-karung'],
            ['Produk tayang', number_format($stats['active_products'], 0, ',', '.'), 'terlihat di katalog', 'bg-[#fffaf0] text-espresso'],
            ['Pesanan pending', number_format($stats['pending_orders'], 0, ',', '.'), 'menunggu konfirmasi WA', 'bg-stiker text-espresso'],
            ['Omzet terkonfirmasi', 'Rp '.number_format($stats['revenue'], 0, ',', '.'), 'status confirmed ke atas', 'bg-jago text-white'],
        ] as [$label, $value, $hint, $classes])
            <div class="rounded-2xl border-2 border-espresso/10 p-5 {{ $classes }}">
                <p class="text-[10px] font-extrabold uppercase tracking-widest opacity-60">{{ $label }}</p>
                <p class="mt-2 font-display text-2xl leading-none">{{ $value }}</p>
                <p class="mt-2 text-[11px] font-semibold opacity-60">{{ $hint }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_0.6fr]">
        <section class="overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80">
            <div class="flex items-center justify-between gap-3 border-b border-espresso/10 px-5 py-4">
                <h2 class="font-display text-lg text-espresso">Pesanan terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-extrabold text-jago underline underline-offset-4">Semua pesanan</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="font-marker text-lg text-espresso/50">Belum ada pesanan.</p>
                    <p class="mt-1 text-xs text-espresso/45">Pesanan dari website akan muncul di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left text-sm">
                        <thead class="bg-karung-dark/30 text-[10px] uppercase tracking-widest text-espresso/50">
                            <tr>
                                <th class="px-5 py-2.5 font-extrabold">Kode</th>
                                <th class="px-3 py-2.5 font-extrabold">Pemesan</th>
                                <th class="px-3 py-2.5 font-extrabold">Total</th>
                                <th class="px-3 py-2.5 font-extrabold">Status</th>
                                <th class="px-5 py-2.5 text-right font-extrabold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-espresso/10">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-white/50">
                                    <td class="px-5 py-3">
                                        <p class="font-extrabold text-espresso">{{ $order->order_code }}</p>
                                        <p class="text-[10px] font-semibold text-espresso/45">{{ $order->created_at->format('d M Y H:i') }}</p>
                                    </td>
                                    <td class="px-3 py-3">
                                        <p class="font-bold text-espresso">{{ $order->customer_name }}</p>
                                        <p class="text-[10px] text-espresso/45">{{ $order->phone }}</p>
                                    </td>
                                    <td class="px-3 py-3 font-extrabold text-jago">{{ $order->formattedTotal() }}</td>
                                    <td class="px-3 py-3">
                                        <span @class([
                                            'rounded-full px-2 py-1 text-[10px] font-extrabold uppercase tracking-wide',
                                            'bg-stiker/40 text-espresso' => $order->status === 'pending',
                                            'bg-daun/20 text-daun' => in_array($order->status, ['confirmed', 'shipped']),
                                            'bg-sangrai text-karung' => $order->status === 'completed',
                                            'bg-jago/15 text-jago' => $order->status === 'cancelled',
                                        ])>{{ $order->statusLabel() }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-[11px] font-extrabold text-jago underline underline-offset-4">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="space-y-6">
            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                <h2 class="font-display text-lg text-espresso">Stok menipis</h2>
                <p class="text-[11px] font-semibold text-espresso/45">Varian dengan stok ≤ 5.</p>

                @if ($lowStock->isEmpty())
                    <p class="mt-4 rounded-lg bg-daun/10 px-3 py-3 text-xs font-semibold text-daun">Semua stok masih aman.</p>
                @else
                    <ul class="mt-3 divide-y divide-espresso/10">
                        @foreach ($lowStock as $variant)
                            <li class="flex items-center justify-between gap-2 py-2.5">
                                <div class="min-w-0">
                                    <p class="truncate text-xs font-extrabold text-espresso">{{ $variant->product?->name }}</p>
                                    <p class="text-[10px] text-espresso/50">{{ $variant->label }}</p>
                                </div>
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-[10px] font-extrabold',
                                    'bg-jago text-white' => $variant->stock === 0,
                                    'bg-stiker text-espresso' => $variant->stock > 0,
                                ])>{{ $variant->stock }} tersisa</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="rounded-2xl border-2 border-sangrai bg-sangrai p-5 text-karung">
                <h2 class="font-display text-lg">Aksi cepat</h2>
                <div class="mt-3 space-y-2 text-xs font-bold">
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center justify-between rounded-lg bg-white/5 px-3 py-2.5 hover:bg-white/10">Pesanan pending <span aria-hidden="true">→</span></a>
                    <a href="{{ route('admin.settings.edit') }}" class="flex items-center justify-between rounded-lg bg-white/5 px-3 py-2.5 hover:bg-white/10">Ubah nomor WA & jam buka <span aria-hidden="true">→</span></a>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-lg bg-white/5 px-3 py-2.5 hover:bg-white/10">Buka website toko <span aria-hidden="true">↗</span></a>
                </div>
            </section>
        </div>
    </div>
@endsection
