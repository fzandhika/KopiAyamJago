@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Toko')

@section('content')
    <div class="max-w-3xl">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="font-marker text-lg text-jago">Identitas & info toko</p>
                <p class="text-sm text-espresso/60">Semua data di halaman ini dipakai di header, footer, kontak, dan tombol WhatsApp.</p>
            </div>
        </div>

        <div class="mt-5 flex items-start gap-3 rounded-xl border-2 border-stiker/50 bg-stiker/15 p-4">
            <span class="text-lg" aria-hidden="true">⚠️</span>
            <p class="text-[11px] leading-relaxed font-semibold text-espresso/70">
                Data awal (nomor WhatsApp, alamat, jam buka) masih <span class="font-extrabold">dummy</span>.
                Ganti dengan data asli toko sebelum website dipakai jualan. Nomor WhatsApp wajib format internasional, contoh
                <span class="font-extrabold">6281234567890</span>.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-5 space-y-5">
            @csrf
            @method('PUT')

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                <h2 class="font-display text-lg text-espresso">Kontak & lokasi</h2>
                <div class="mt-4 space-y-4">
                    @foreach (['store_name', 'whatsapp_number', 'instagram', 'open_hours', 'maps_url'] as $key)
                        <div>
                            <label for="setting_{{ $key }}" class="label-jago">{{ $labels[$key] ?? $key }}</label>
                            <input id="setting_{{ $key }}" name="{{ $key }}" type="{{ $key === 'maps_url' ? 'url' : 'text' }}" value="{{ old($key, $settings[$key] ?? '') }}" maxlength="500" class="input-jago @error($key) border-jago @enderror">
                            @if ($key === 'maps_url')
                                <p class="mt-1 text-[10px] text-espresso/40">Tempel link Google Maps hasil “Share → Copy link”.</p>
                            @endif
                            @error($key) <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>
                    @endforeach

                    <div>
                        <label for="setting_address" class="label-jago">{{ $labels['address'] }}</label>
                        <textarea id="setting_address" name="address" rows="3" maxlength="500" class="input-jago @error('address') border-jago @enderror">{{ old('address', $settings['address'] ?? '') }}</textarea>
                        <p class="mt-1 text-[10px] text-espresso/40">Dipakai juga untuk titik peta di halaman Kontak.</p>
                        @error('address') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                <h2 class="font-display text-lg text-espresso">Pembayaran & pengiriman</h2>
                <div class="mt-4 space-y-4">
                    @foreach (['bank_account', 'qris_note', 'free_shipping_note'] as $key)
                        <div>
                            <label for="setting_{{ $key }}" class="label-jago">{{ $labels[$key] ?? $key }}</label>
                            <textarea id="setting_{{ $key }}" name="{{ $key }}" rows="2" maxlength="500" class="input-jago @error($key) border-jago @enderror">{{ old($key, $settings[$key] ?? '') }}</textarea>
                            @error($key) <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="submit" class="btn btn-jago">Simpan Pengaturan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection
