@extends('layouts.admin')

@section('title', 'Pesanan')
@section('heading', 'Pesanan')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="font-marker text-lg text-jago">Buku pesanan</p>
            <p class="text-sm text-espresso/60">Semua pesanan dari website tercatat di sini, termasuk yang belum dibayar.</p>
        </div>
        <span class="rounded-full border-2 border-espresso/10 bg-[#fffaf0] px-3 py-1.5 text-[11px] font-extrabold text-espresso/60">{{ number_format($totalOrders, 0, ',', '.') }} pesanan total</span>
    </div>

    <div class="mt-5 flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.index') }}" @class([
            'rounded-full border-2 px-3 py-1.5 text-[11px] font-extrabold transition',
            'border-sangrai bg-sangrai text-karung' => ! $status,
            'border-espresso/15 text-espresso/60 hover:border-espresso/30' => $status,
        ])>Semua</a>
        @foreach (\App\Models\Order::STATUSES as $value => $label)
            <a href="{{ route('admin.orders.index', ['status' => $value]) }}" @class([
                'rounded-full border-2 px-3 py-1.5 text-[11px] font-extrabold transition',
                'border-jago bg-jago text-white' => $status === $value,
                'border-espresso/15 text-espresso/60 hover:border-espresso/30' => $status !== $value,
            ])>{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-5 overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80">
        @if ($orders->isEmpty())
            <div class="px-5 py-16 text-center">
                <p class="font-marker text-xl text-espresso/50">Belum ada pesanan di filter ini.</p>
                <p class="mt-1 text-xs text-espresso/45">Pesanan baru akan masuk otomatis setelah checkout.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-sm">
                    <thead class="bg-karung-dark/30 text-[10px] uppercase tracking-widest text-espresso/50">
                        <tr>
                            <th class="px-5 py-3 font-extrabold">Kode & waktu</th>
                            <th class="px-3 py-3 font-extrabold">Pemesan</th>
                            <th class="px-3 py-3 font-extrabold">Item</th>
                            <th class="px-3 py-3 font-extrabold">Bayar</th>
                            <th class="px-3 py-3 font-extrabold">Total</th>
                            <th class="px-3 py-3 font-extrabold">Status</th>
                            <th class="px-5 py-3 text-right font-extrabold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-espresso/10">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-white/50">
                                <td class="px-5 py-3">
                                    <p class="font-extrabold text-espresso">{{ $order->order_code }}</p>
                                    <p class="text-[10px] font-semibold text-espresso/45">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-3 py-3">
                                    <p class="font-bold text-espresso">{{ $order->customer_name }}</p>
                                    <p class="text-[10px] text-espresso/45">{{ $order->phone }}</p>
                                </td>
                                <td class="px-3 py-3 text-xs font-bold text-espresso/70">{{ $order->items_count }} item</td>
                                <td class="px-3 py-3 text-xs font-semibold text-espresso/70">{{ $order->paymentLabel() }}</td>
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
                                    <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg border border-jago/30 bg-jago/5 px-2.5 py-1 text-[11px] font-bold text-jago hover:bg-jago/10">Kelola</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
