@extends('layouts.app')

@section('title', 'Kopi Ayam Jago — Kopi Serius, Harga Jujur')
@section('description', 'Biji kopi Robusta, Arabika, dan Luwak pilihan dari Sidikalang sampai Toraja. Warung kopi kampung Jakarta dengan harga jujur dan sangrai mingguan.')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@'.'context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => \App\Models\Setting::get('store_name'),
            'description' => 'Toko biji kopi Robusta, Arabika, dan Luwak di Jakarta. Harga jujur, sangrai mingguan.',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => \App\Models\Setting::get('address'),
                'addressLocality' => 'Jakarta',
                'addressCountry' => 'ID',
            ],
            'telephone' => '+'.\App\Models\Setting::get('whatsapp_number'),
            'openingHours' => \App\Models\Setting::get('open_hours'),
            'url' => route('home'),
            'priceRange' => 'Rp 25.000 – Rp 1.000.000',
            'hasMap' => \App\Models\Setting::get('maps_url'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    {{-- HERO --}}
    <section class="paper-dark relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-jago/25 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-[#5b6cff]/10 blur-3xl neon-glow"></div>
            <div class="absolute left-6 top-0 hidden h-40 w-[2px] bg-karung/20 sm:block"></div>
            <div class="absolute left-3 top-40 hidden h-8 w-9 rounded-[45%] lantern sm:block"></div>
            <div class="absolute right-10 top-0 hidden h-52 w-[2px] bg-karung/20 sm:block"></div>
            <div class="absolute right-6 top-52 hidden h-9 w-10 rounded-[45%] lantern sm:block" style="animation-delay: -2s"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 pb-16 pt-12 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:gap-6 lg:pb-24 lg:pt-20">
            <div class="rise">
                <p class="inline-flex items-center gap-2 rounded-full border border-stiker/40 bg-stiker/10 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.18em] text-stiker">
                    <span class="h-1.5 w-1.5 rounded-full bg-stiker"></span>
                    Warung kopi kampung · Jakarta
                </p>

                <h1 class="mt-5 text-[2.75rem] leading-[0.95] text-karung sm:text-6xl xl:text-7xl">
                    Kopi Serius,<br>
                    <span class="relative inline-block text-stiker">
                        Harga Jujur.
                        <svg viewBox="0 0 300 14" class="absolute -bottom-2 left-0 h-3 w-full text-jago" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M2 10c60-7 120-8 180-4s80 5 116 1" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="mt-7 max-w-xl text-base leading-relaxed text-karung/75 sm:text-lg">
                    Dari Sidikalang sampai Toraja, semua ada di toples Ayam Jago. Biji pilihan, disangrai mingguan,
                    ditimbang terbuka di depan kamu. Nggak ada harga siluman, nggak ada rasa yang dibagus-bagusin.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('catalog.index') }}" class="btn btn-stiker text-base">
                        Lihat Biji Kopi
                        <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M4 10h11M11 5.5 15.5 10 11 14.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau pesan biji kopi.') }}" target="_blank" rel="noopener" class="btn btn-wa text-base">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.7c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.1 0 1.2.9 2.5 1 2.5.1.2 1.7 2.7 4.2 3.7 2 .8 2.4.7 2.9.6.4 0 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1 0-.1-.2-.2-.4-.3Z"/></svg>
                        Pesan via WhatsApp
                    </a>
                </div>

                <dl class="mt-10 grid max-w-lg grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    @foreach ([
                        ['10+', 'pilihan biji'],
                        ['100g–1kg', 'gramasi bebas'],
                        ['Mingguan', 'jadwal sangrai'],
                    ] as [$value, $label])
                        <div>
                            <dt class="font-display text-lg text-stiker sm:text-xl">{{ $value }}</dt>
                            <dd class="text-[11px] font-bold uppercase tracking-widest text-karung/50">{{ $label }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Komposisi toples --}}
            <div class="relative">
                <div class="pointer-events-none absolute inset-x-4 top-6 bottom-6 rounded-[40%] bg-stiker/10 blur-2xl" aria-hidden="true"></div>
                <div class="relative flex items-end justify-center gap-2 sm:gap-4">
                    @foreach ($newest->take(3) as $index => $product)
                        <div @class([
                            'w-[36%] sm:w-[34%]',
                            '-translate-y-2' => $index === 1,
                            'translate-y-5 rotate-[-4deg]' => $index === 0,
                            'translate-y-5 rotate-[4deg]' => $index === 2,
                        ])>
                            <x-product-jar :product="$product" compact />
                        </div>
                    @endforeach
                </div>

                <div class="relative mx-auto mt-10 w-fit -rotate-1 rounded-lg border-2 border-stiker/50 bg-sangrai/80 px-4 py-2 text-center shadow-lg backdrop-blur">
                    <p class="font-marker text-sm text-stiker">“Gue nggak pernah bercanda soal KOPI!”</p>
                </div>
            </div>
        </div>

        <div class="relative border-t-2 border-sangrai bg-jago py-2.5">
            <p class="mx-auto max-w-7xl px-4 text-center text-[11px] font-extrabold uppercase tracking-[0.28em] text-karung/90 sm:text-xs">
                Robusta · Arabika · Luwak · Sangrai Mingguan · Timbangan Terbuka
            </p>
        </div>
    </section>

    {{-- KATEGORI --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:py-20">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="font-marker text-lg text-jago">Pilih karakternya</p>
                <h2 class="mt-1 text-3xl text-espresso sm:text-4xl">Tiga keluarga biji kopi</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-sm font-extrabold text-jago underline decoration-2 underline-offset-4 hover:text-jago-dark">
                Lihat semua toples →
            </a>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ route('catalog.index', ['type' => $category->name]) }}"
                   class="group relative overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/70 p-6 transition hover:-translate-y-1 hover:border-jago hover:shadow-[8px_8px_0_0_rgba(179,32,31,0.15)]">
                    <span class="absolute -right-2 -top-4 font-display text-6xl text-espresso/5">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <p class="font-display text-2xl text-jago">{{ $category->name }}</p>
                    <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-espresso/65">{{ $category->description }}</p>
                    <p class="mt-5 flex items-center justify-between text-xs font-extrabold uppercase tracking-widest text-espresso/50">
                        {{ $category->products_count }} pilihan biji
                        <span class="text-jago transition-transform group-hover:translate-x-1">→</span>
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- PILIHAN JAGO --}}
    <section class="border-y border-espresso/10 bg-karung-dark/40 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="font-marker text-lg text-jago">Paling sering dibuka</p>
                    <h2 class="mt-1 text-3xl text-espresso sm:text-4xl">Toples pilihan Ayam Jago</h2>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-espresso/60">
                    Empat biji yang paling sering dibeli pelanggan gang. Semua bisa dicoba per 100 gram dulu.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($featured as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="text-sm text-espresso/60">Belum ada produk unggulan. Jalankan seeder dulu.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- JANJI JUJUR --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:py-20">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div>
                <p class="font-marker text-lg text-jago">Nggak ada yang disembunyiin</p>
                <h2 class="mt-1 text-3xl text-espresso sm:text-4xl">Kenapa orang balik lagi?</h2>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-espresso/70">
                    Karena kopi itu soal kepercayaan. Kami jual biji apa adanya, timbang di depan pembeli,
                    dan kasih tahu mana yang cocok buat seduhanmu — bukan yang paling mahal.
                </p>
                <a href="{{ route('story') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-extrabold text-jago hover:text-jago-dark">
                    Baca cerita toko
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['Sangrai mingguan', 'Biji disangrai tiap minggu dalam batch kecil. Nggak ada stok mengendap berbulan-bulan.'],
                    ['Timbangan terbuka', 'Gramasi 100 g sampai 1 kg, ditimbang di depan kamu. Lebih atau kurang, kelihatan.'],
                    ['Harga per gram', 'Harga ditempel jelas di toples. Nggak ada biaya kejutan saat bayar.'],
                ] as [$title, $body])
                    <div class="rounded-2xl border border-espresso/10 bg-white/40 p-5">
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-jago/10 font-display text-sm text-jago">{{ $loop->iteration }}</span>
                        <h3 class="mt-4 font-display text-base text-espresso">{{ $title }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-espresso/65">{{ $body }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CERITA TEASER --}}
    <section class="paper-dark relative overflow-hidden py-16 sm:py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 lg:grid-cols-2 lg:items-center">
            <div class="relative">
                <div class="relative overflow-hidden rounded-2xl border-2 border-emas/60 p-6 pt-4">
                    <div class="absolute inset-x-0 top-0 flex justify-around" aria-hidden="true">
                        @foreach ([0, 1, 2] as $i)
                            <div class="flex flex-col items-center">
                                <span class="h-10 w-[1px] bg-karung/30"></span>
                                <span class="lantern h-6 w-7 rounded-[45%]" style="animation-delay: -{{ $i }}s"></span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-16 rounded-lg border-4 border-sangrai bg-jago p-5 text-center shadow-[0_16px_40px_-20px_rgba(0,0,0,0.9)]">
                        <p class="font-marker text-[11px] tracking-[0.3em] text-stiker">KOPI</p>
                        <p class="font-display text-3xl leading-none text-white sm:text-4xl">AYAM JAGO</p>
                        <p class="mt-1.5 text-[10px] font-semibold tracking-wide text-white/75">Menyediakan biji kopi Robusta, Arabika, dsb.</p>
                    </div>

                    <div class="mt-5 grid grid-cols-3 gap-2">
                        @foreach ($newest->take(3) as $product)
                            <div class="rounded-lg border border-emas/40 bg-sangrai/60 p-2 text-center">
                                <span class="mx-auto block h-10 w-8 rounded-b-md rounded-t-sm jar-coffee border border-emas/40"></span>
                                <p class="mt-1.5 line-clamp-1 font-marker text-[10px] text-stiker">{{ $product->name }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pointer-events-none absolute -left-3 top-24 h-16 w-10 rounded-full bg-daun/70 blur-[2px]" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -right-4 top-36 h-20 w-12 rounded-full bg-daun/50 blur-[3px]" aria-hidden="true"></div>
                </div>
            </div>

            <div>
                <p class="font-marker text-lg text-stiker">Dari gang, untuk cangkir kamu</p>
                <h2 class="mt-1 text-3xl text-karung sm:text-4xl">Ruko kecil, toples penuh, cerita panjang.</h2>
                <p class="mt-4 text-sm leading-relaxed text-karung/70 sm:text-base">
                    Mulai dari satu rak kayu dan beberapa toples kaca, Ayam Jago jadi tempat singgah warga gang
                    buat cari biji kopi yang jujur. Lampu neon, lampion, dan maneki-neko masih di tempatnya —
                    sama seperti resep sangrai kami.
                </p>
                <blockquote class="mt-6 border-l-4 border-stiker pl-4 font-marker text-xl text-stiker">
                    “Gue nggak pernah bercanda soal KOPI!”
                </blockquote>
                <a href="{{ route('story') }}" class="btn btn-stiker mt-7">Baca Cerita Kami</a>
            </div>
        </div>
    </section>

    {{-- TESTIMONI --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:py-20">
        <div class="text-center">
            <p class="font-marker text-lg text-jago">Kata pelanggan</p>
            <h2 class="mt-1 text-3xl text-espresso sm:text-4xl">Yang sudah mampir, balik lagi</h2>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <figure class="flex flex-col rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/70 p-6">
                    <div class="flex gap-0.5 text-stiker" aria-label="{{ $testimonial->rating }} dari 5">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 20 20" class="h-4 w-4 {{ $i <= $testimonial->rating ? 'fill-current' : 'fill-espresso/15' }}" aria-hidden="true"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 14.9l-5.2 2.7 1-5.8L1.5 7.7l5.9-.9L10 1.5z"/></svg>
                        @endfor
                    </div>
                    <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-espresso/80">“{{ $testimonial->quote }}”</blockquote>
                    <figcaption class="mt-4 flex items-center gap-3 border-t border-dashed border-espresso/15 pt-4">
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-jago font-display text-sm text-karung">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                        <span>
                            <span class="block text-sm font-extrabold text-espresso">{{ $testimonial->name }}</span>
                            <span class="block text-[11px] font-semibold uppercase tracking-widest text-espresso/50">{{ $testimonial->city }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 pb-4">
        <div class="relative overflow-hidden rounded-3xl border-2 border-sangrai bg-jago px-6 py-12 text-center text-karung sm:px-12">
            <div class="pointer-events-none absolute inset-0 opacity-30" aria-hidden="true">
                <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full border-[10px] border-stiker/40"></div>
                <div class="absolute -bottom-16 right-0 h-52 w-52 rounded-full border-[14px] border-stiker/20"></div>
            </div>
            <p class="font-marker text-lg text-stiker">Mampir atau titip kurir</p>
            <h2 class="relative mx-auto mt-1 max-w-2xl text-3xl leading-tight text-white sm:text-4xl">
                Bingung pilih biji? Chat dulu, kami racik rekomendasinya.
            </h2>
            <p class="relative mx-auto mt-3 max-w-xl text-sm text-karung/80">
                Ceritakan alat seduhmu dan selera rasa. Kami bantu pilih dari toples yang paling pas — tanpa maksa.
            </p>
            <div class="relative mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau minta rekomendasi biji kopi.') }}" target="_blank" rel="noopener" class="btn btn-stiker">Chat via WhatsApp</a>
                <a href="{{ route('contact') }}" class="btn btn-outline border-karung text-karung hover:bg-karung hover:text-jago">Lihat Lokasi</a>
            </div>
        </div>
    </section>
@endsection
