@extends('layouts.app')

@section('title', 'Checkout — Kopi Ayam Jago')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:py-14">
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
            <a href="{{ route('cart.index') }}" class="text-espresso/45 hover:text-jago">01 Keranjang</a>
            <span class="text-espresso/25">—</span>
            <span class="rounded-full bg-sangrai px-3 py-1 text-karung">02 Data & Pembayaran</span>
            <span class="text-espresso/25">—</span>
            <span class="text-espresso/45">03 Konfirmasi</span>
        </div>

        <div class="mt-4">
            <p class="font-marker text-lg text-jago">Tinggal satu langkah</p>
            <h1 class="mt-1 text-4xl leading-none text-espresso">Checkout</h1>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_400px] lg:items-start">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-6">
                @csrf

                <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                    <div class="flex items-center gap-2">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-jago font-display text-xs text-karung">1</span>
                        <h2 class="font-display text-lg text-espresso">Data penerima</h2>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="label-jago">Nama lengkap</label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required autocomplete="name" placeholder="Nama yang menerima paket" class="input-jago @error('customer_name') border-jago @enderror">
                            @error('customer_name') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="label-jago">Nomor WhatsApp</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" placeholder="081234567890" class="input-jago @error('phone') border-jago @enderror">
                            @error('phone') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="label-jago">Alamat pengiriman</label>
                            <textarea id="address" name="address" rows="3" required autocomplete="street-address" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos" class="input-jago @error('address') border-jago @enderror">{{ old('address') }}</textarea>
                            @error('address') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="label-jago">Catatan (opsional)</label>
                            <textarea id="notes" name="notes" rows="2" placeholder="Contoh: titip ke pos keamanan, atau minta digiling halus" class="input-jago">{{ old('notes') }}</textarea>
                            @error('notes') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                    <div class="flex items-center gap-2">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-jago font-display text-xs text-karung">2</span>
                        <h2 class="font-display text-lg text-espresso">Cara bayar</h2>
                    </div>

                    <div class="mt-4 grid gap-3">
                        @foreach ([
                            ['whatsapp', 'Pesan via WhatsApp', 'Paling cepat. Kami konfirmasi stok, ongkir, dan total di chat.', 'Direkomendasikan'],
                            ['transfer', 'Transfer Bank', \App\Models\Setting::get('bank_account'), null],
                            ['qris', 'QRIS', \App\Models\Setting::get('qris_note'), null],
                        ] as [$value, $label, $description, $badge])
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method', 'whatsapp') === $value)>
                                <span class="flex items-start gap-3 rounded-xl border-2 border-espresso/15 bg-white/60 p-4 transition peer-checked:border-jago peer-checked:bg-jago/5">
                                    <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full border-2 border-espresso/25 transition peer-checked:border-jago peer-checked:[&>span]:opacity-100">
                                        <span class="h-2.5 w-2.5 rounded-full bg-jago opacity-0 transition"></span>
                                    </span>
                                    <span class="flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="text-sm font-extrabold text-espresso">{{ $label }}</span>
                                            @if ($badge)
                                                <span class="rounded-full bg-stiker px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-widest text-espresso">{{ $badge }}</span>
                                            @endif
                                        </span>
                                        <span class="mt-1 block text-xs leading-relaxed text-espresso/60">{{ $description }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                        @error('payment_method') <p class="text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-5 flex items-start gap-2 rounded-xl bg-stiker/15 p-3">
                        <span class="mt-0.5 text-sm" aria-hidden="true">💡</span>
                        <p class="text-[11px] leading-relaxed font-semibold text-espresso/70">
                            Pesanan dicatat dulu, lalu dikonfirmasi lewat WhatsApp. Nggak ada pembayaran otomatis di website ini —
                            jadi kamu bisa tanya stok dan ongkir sebelum transfer.
                        </p>
                    </div>
                </section>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-espresso/10 bg-white/40 p-4">
                    <input type="checkbox" name="agree" value="1" required class="mt-0.5 h-4 w-4 rounded border-2 border-espresso/30 text-jago focus:ring-emas">
                    <span class="text-xs leading-relaxed text-espresso/70">
                        Saya sudah cek data pesanan dan setuju dihubungi via WhatsApp untuk konfirmasi stok, ongkir, dan pembayaran.
                    </span>
                </label>
                @error('agree') <p class="-mt-3 text-xs font-semibold text-jago">{{ $message }}</p> @enderror

                <button type="submit" class="btn btn-jago w-full py-4 text-base">Buat Pesanan</button>
                <p class="text-center text-[11px] font-semibold text-espresso/45">Data kamu hanya dipakai untuk memproses pesanan ini.</p>
            </form>

            <aside class="lg:sticky lg:top-28">
                <div class="rounded-2xl border-2 border-sangrai bg-[#fffaf0] p-5 shadow-[6px_6px_0_0_#1E1410]">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-lg text-espresso">Pesananmu</h2>
                        <a href="{{ route('cart.index') }}" class="text-[11px] font-bold text-jago underline underline-offset-4">Ubah</a>
                    </div>

                    <ul class="mt-4 space-y-3">
                        @foreach ($items as $item)
                            <li class="flex gap-3">
                                <div class="w-14 shrink-0">
                                    <x-product-jar :product="$item['product']" :variant="$item['variant']" compact />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-extrabold text-espresso">{{ $item['product']->name }}</p>
                                    <p class="text-[11px] font-semibold text-espresso/50">{{ $item['variant']->label }} · {{ \App\Models\OrderItem::GRIND_TYPES[$item['grind']] }} · {{ $item['qty'] }}×</p>
                                    <p class="mt-1 text-xs font-extrabold text-jago">{{ 'Rp '.number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="mt-4 space-y-2 border-t-2 border-dashed border-espresso/15 pt-4 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-espresso/60">Subtotal</dt>
                            <dd class="font-extrabold text-espresso">{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-espresso/60">Ongkir</dt>
                            <dd class="text-right text-[11px] font-semibold text-espresso/55">Dikonfirmasi via WA</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex items-end justify-between border-t-2 border-dashed border-espresso/15 pt-4">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-espresso/50">Total sementara</span>
                        <span class="font-display text-2xl text-jago">{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-daun/25 bg-daun/10 p-4">
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-daun">Belum yakin?</p>
                    <p class="mt-1 text-xs leading-relaxed text-espresso/65">
                        Chat dulu aja. Kami bantu cek stok dan rekomendasi gilingan sebelum kamu bayar.
                    </p>
                    <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau tanya sebelum checkout.') }}" target="_blank" rel="noopener" class="mt-2 inline-block text-xs font-extrabold text-daun underline underline-offset-4">Chat via WhatsApp →</a>
                </div>
            </aside>
        </div>
    </div>
@endsection
