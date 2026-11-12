@extends('layouts.app')

@section('title', 'Cerita Kami — Kopi Ayam Jago')
@section('description', 'Cerita warung kopi kampung Jakarta berjiwa Pecinan: rak kayu, toples kaca, dan prinsip dagang yang jujur sejak awal.')

@section('content')
    <section class="paper-dark relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -left-20 top-10 h-64 w-64 rounded-full bg-jago/25 blur-3xl"></div>
            <div class="absolute right-4 top-0 h-32 w-[2px] bg-karung/20"></div>
            <div class="absolute right-1 top-32 hidden h-9 w-10 rounded-[45%] lantern sm:block"></div>
        </div>

        <div class="relative mx-auto max-w-4xl px-4 py-16 text-center sm:py-24">
            <p class="font-marker text-lg text-stiker">Cerita Kami</p>
            <h1 class="mt-2 text-4xl leading-[0.95] text-karung sm:text-6xl">
                Ruko kecil di gang,<br>kopi yang nggak main-main.
            </h1>
            <p class="mx-auto mt-5 max-w-2xl text-sm leading-relaxed text-karung/70 sm:text-base">
                Nggak ada interior marble, nggak ada barista berseragam rapi. Yang ada: rak kayu, toples kaca,
                lampu neon, dan orang yang benar-benar tahu biji kopi mana yang dia jual.
            </p>
        </div>
    </section>

    <div class="mx-auto max-w-5xl px-4 py-14 sm:py-20">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
            <div class="space-y-5 text-sm leading-relaxed text-espresso/75 sm:text-base">
                <p class="first-letter:float-left first-letter:mr-2 first-letter:font-display first-letter:text-5xl first-letter:leading-[0.8] first-letter:text-jago">
                    Semua mulai dari satu rak kayu. Pemilik toko — warga gang yang sudah lama berjualan —
                    menyusun toples kaca berisi biji kopi dari berbagai daerah. Robusta Sidikalang di ujung,
                    Arabika Toraja di tengah, Luwak di baris paling atas.
                </p>
                <p>
                    Papan nama merah-hitam bertuliskan <span class="font-bold text-espresso">AYAM JAGO</span> dipasang
                    biar orang nggak salah masuk. Tanaman rambat dibiarkan tumbuh, sangkar burung kuning digantung
                    di depan, lampion merah dan maneki-neko menemani rak. Suasananya campuran kampung Jakarta dan
                    Pecinan — apa adanya, nggak dibuat-buat.
                </p>
                <p>
                    Yang bikin orang balik bukan cuma suasananya. Tapi caranya berdagang: timbangan ditaruh di depan,
                    harga ditempel di toples, dan kalau biji lagi nggak bagus, ya dikatakan nggak bagus. Nggak ada
                    cerita “kopi paling mahal pasti paling enak”.
                </p>
                <blockquote class="rounded-xl border-l-4 border-jago bg-white/50 px-5 py-4 font-marker text-xl text-jago">
                    “Gue nggak pernah bercanda soal KOPI!”
                </blockquote>
                <p>
                    Itu bukan slogan tempelan. Itu cara kami memilih green bean, mengatur jadwal sangrai, dan
                    menjaga stok supaya nggak mengendap. Kalau kamu datang dan minta rekomendasi, kami akan tanya
                    dulu: seduhnya pakai apa, suka asam atau pahit, ngopi sendiri atau rame-rame.
                </p>
            </div>

            <div class="space-y-4">
                <div class="relative overflow-hidden rounded-2xl border-2 border-emas/60 paper-dark p-5">
                    <div class="flex justify-center gap-4" aria-hidden="true">
                        @foreach ([0, 1] as $i)
                            <div class="flex flex-col items-center">
                                <span class="h-8 w-[1px] bg-karung/25"></span>
                                <span class="lantern h-6 w-7 rounded-[45%]" style="animation-delay: -{{ $i * 2 }}s"></span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 rounded-lg border-4 border-sangrai bg-jago px-4 py-4 text-center">
                        <p class="font-marker text-[10px] tracking-[0.3em] text-stiker">KOPI</p>
                        <p class="font-display text-2xl leading-none text-white">AYAM JAGO</p>
                        <p class="mt-1 text-[9px] font-semibold text-white/70">Menyediakan biji kopi Robusta, Arabika, dsb.</p>
                    </div>
                    <p class="mt-4 text-center font-marker text-sm text-stiker">Ruko lama, gang permukiman, Jakarta.</p>
                </div>

                <div class="rounded-2xl border border-espresso/10 bg-white/50 p-5">
                    <p class="font-display text-sm uppercase tracking-widest text-espresso">Yang selalu kami jaga</p>
                    <ul class="mt-3 space-y-3 text-xs leading-relaxed text-espresso/70">
                        @foreach ([
                            ['Biji, bukan perasa.', 'Semua rasa datang dari biji dan proses sangrai. Nggak ada tambahan aroma.'],
                            ['Harga terbuka.', 'Harga per gramasi ditempel di toples. Ongkir dikonfirmasi sebelum bayar.'],
                            ['Rekomendasi jujur.', 'Kalau biji mahal nggak cocok dengan alat seduhmu, kami bilang.'],
                        ] as [$title, $body])
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-jago"></span>
                                <span><span class="font-extrabold text-espresso">{{ $title }}</span> {{ $body }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <p class="font-marker text-lg text-jago">Perjalanan singkat</p>
            <h2 class="mt-1 text-2xl text-espresso sm:text-3xl">Dari satu rak ke banyak cangkir</h2>

            <ol class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Awal', 'Satu rak kayu', 'Berjualan biji kopi untuk warga gang. Tiga jenis Robusta, satu Arabika.'],
                    ['Rak bertambah', 'Toples kaca', 'Permintaan naik. Rak ditambah, toples ditata per daerah asal.'],
                    ['Dikenal', 'Lampu neon', 'Neon biru, merah, ungu dan lampion jadi penanda. Maneki-neko ikut menjaga.'],
                    ['Sekarang', 'Online & tetap ramah', 'Bisa pesan dari mana saja, tapi timbangan tetap di depan toko.'],
                ] as [$tag, $title, $body])
                    <li class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/70 p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-jago">{{ $tag }}</p>
                        <p class="mt-2 font-display text-lg leading-tight text-espresso">{{ $title }}</p>
                        <p class="mt-2 text-xs leading-relaxed text-espresso/65">{{ $body }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        @if ($testimonials->isNotEmpty())
            <div class="mt-16">
                <div class="flex items-end justify-between gap-3">
                    <h2 class="text-2xl text-espresso sm:text-3xl">Kata yang sudah mampir</h2>
                    <a href="{{ route('catalog.index') }}" class="text-sm font-extrabold text-jago underline decoration-2 underline-offset-4">Lihat katalog →</a>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($testimonials as $testimonial)
                        <figure class="rounded-2xl border border-espresso/10 bg-white/50 p-5">
                            <blockquote class="text-sm leading-relaxed text-espresso/75">“{{ $testimonial->quote }}”</blockquote>
                            <figcaption class="mt-3 text-[11px] font-extrabold uppercase tracking-widest text-espresso/50">
                                {{ $testimonial->name }} · {{ $testimonial->city }}
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-14 rounded-2xl border-2 border-sangrai bg-jago p-6 text-center text-karung sm:p-8">
            <p class="font-marker text-lg text-stiker">Mampir, nggak perlu beli dulu</p>
            <h2 class="mt-1 text-2xl text-white sm:text-3xl">Ngobrol soal kopi aja boleh.</h2>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <a href="{{ \App\Models\Setting::whatsappLink('Halo Ayam Jago, saya mau mampir ke toko.') }}" target="_blank" rel="noopener" class="btn btn-stiker">Chat WhatsApp</a>
                <a href="{{ route('contact') }}" class="btn btn-outline border-karung text-karung hover:bg-karung hover:text-jago">Lihat Lokasi</a>
            </div>
        </div>
    </div>
@endsection
