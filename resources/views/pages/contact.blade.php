@extends('layouts.app')

@section('title', 'Kontak & Lokasi — Kopi Ayam Jago')
@section('description', 'Alamat, jam buka, dan kontak Kopi Ayam Jago. Mampir ke ruko di gang permukiman Jakarta atau pesan via WhatsApp.')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@'.'context' => 'https://schema.org',
            '@type' => 'Store',
            'name' => \App\Models\Setting::get('store_name'),
            'telephone' => '+'.\App\Models\Setting::get('whatsapp_number'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => \App\Models\Setting::get('address'),
                'addressLocality' => 'Jakarta',
                'addressCountry' => 'ID',
            ],
            'openingHours' => \App\Models\Setting::get('open_hours'),
            'hasMap' => \App\Models\Setting::get('maps_url'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <section class="border-b border-espresso/10 bg-karung-dark/30">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:py-16">
            <nav class="text-[11px] font-bold uppercase tracking-widest text-espresso/45" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-jago">Beranda</a>
                <span class="mx-2">/</span>
                <span class="text-espresso/70">Kontak & Lokasi</span>
            </nav>
            <p class="mt-4 font-marker text-lg text-jago">Mampir ke gang</p>
            <h1 class="mt-1 text-4xl leading-none text-espresso sm:text-5xl">Kontak & Lokasi</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-espresso/65 sm:text-base">
                Toko kami kecil, di gang permukiman. Kalau bingung, chat dulu — nanti kami arahkan sampai ketemu
                papan merah-hitam Ayam Jago.
            </p>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-12">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="space-y-4">
                <div class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-6">
                    <h2 class="font-display text-lg text-espresso">Alamat toko</h2>
                    <address class="mt-2 text-sm not-italic leading-relaxed text-espresso/70">
                        {{ \App\Models\Setting::get('address') }}
                    </address>
                    <a href="{{ \App\Models\Setting::get('maps_url') }}" target="_blank" rel="noopener" class="btn btn-jago mt-4">Buka Rute di Maps</a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Jam buka</p>
                        <p class="mt-2 text-sm font-bold text-espresso">{{ \App\Models\Setting::get('open_hours') }}</p>
                        <p class="mt-2 text-[11px] leading-relaxed text-espresso/55">Kalau datang malam, cari lampu neon dan lampion merah.</p>
                    </div>

                    <div class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Instagram</p>
                        <p class="mt-2 text-sm font-bold text-espresso">{{ \App\Models\Setting::get('instagram') }}</p>
                        <p class="mt-2 text-[11px] leading-relaxed text-espresso/55">Foto stok terbaru dan jadwal sangrai biasanya muncul di sini.</p>
                    </div>
                </div>

                <div class="rounded-2xl border-2 border-daun/30 bg-daun/10 p-6">
                    <h2 class="font-display text-lg text-espresso">Pesan jarak jauh</h2>
                    <p class="mt-2 text-sm leading-relaxed text-espresso/70">
                        Kirim pesan ke WhatsApp kami. Sebutkan biji, berat, dan gilingan yang diinginkan.
                        Kami balas dengan stok dan total ongkir sebelum kamu bayar.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau pesan biji kopi.') }}" target="_blank" rel="noopener" class="btn btn-wa">WhatsApp {{ \App\Models\Setting::get('whatsapp_number') }}</a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline">Lihat Katalog</a>
                    </div>
                </div>

                <div class="rounded-2xl border border-espresso/10 bg-white/40 p-5 text-xs leading-relaxed text-espresso/65">
                    <p class="font-extrabold text-espresso">Pembayaran</p>
                    <p class="mt-1">{{ \App\Models\Setting::get('bank_account') }}</p>
                    <p class="mt-1">{{ \App\Models\Setting::get('qris_note') }}</p>
                    <p class="mt-2">{{ \App\Models\Setting::get('free_shipping_note') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="overflow-hidden rounded-2xl border-2 border-sangrai shadow-[6px_6px_0_0_#1E1410]">
                    <iframe
                        title="Peta lokasi Kopi Ayam Jago"
                        src="https://www.google.com/maps?q={{ urlencode(\App\Models\Setting::get('address')) }}&output=embed"
                        class="h-[420px] w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
                </div>

                <div class="rounded-2xl border border-espresso/10 bg-[#fffaf0]/70 p-5">
                    <p class="font-marker text-lg text-jago">Tanda kamu sudah sampai</p>
                    <ul class="mt-2 grid gap-2 text-xs leading-relaxed text-espresso/65 sm:grid-cols-2">
                        <li>✓ Papan merah-hitam “AYAM JAGO”</li>
                        <li>✓ Tanaman rambat di depan ruko</li>
                        <li>✓ Lampu neon biru, merah, ungu</li>
                        <li>✓ Lampion merah & maneki-neko</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
