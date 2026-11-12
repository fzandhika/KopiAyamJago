@extends('layouts.admin')

@section('title', 'Keamanan Akun')
@section('heading', 'Keamanan Akun')

@section('content')
    <div class="grid max-w-4xl gap-5 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <form method="POST" action="{{ route('admin.password.update') }}" class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/85 p-5 sm:p-6">
            @csrf
            @method('PUT')

            <div>
                <p class="font-marker text-lg text-jago">Jaga akses toko</p>
                <h2 class="mt-0.5 font-display text-xl text-espresso">Ganti password admin</h2>
                <p class="mt-1 text-xs leading-relaxed text-espresso/55">
                    Masukkan password lama dulu, lalu tentukan password baru minimal 8 karakter.
                </p>
            </div>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="current_password" class="label-jago">Password lama</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                           class="input-jago @error('current_password') border-jago @enderror" placeholder="Password yang dipakai sekarang">
                    @error('current_password')
                        <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label-jago">Password baru</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="input-jago @error('password') border-jago @enderror" placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label-jago">Ulangi password baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="input-jago" placeholder="Ketik ulang password baru">
                </div>
            </div>

            <button type="submit" class="btn btn-jago mt-6 w-full py-3">Simpan Password Baru</button>
        </form>

        <aside class="space-y-4">
            <div class="rounded-2xl border-2 border-espresso/10 bg-white/45 p-5">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-espresso/45">Akun yang sedang dipakai</p>
                <p class="mt-2 truncate text-sm font-extrabold text-espresso">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs font-semibold text-espresso/55">{{ auth()->user()->email }}</p>
                <p class="mt-3 rounded-lg bg-stiker/20 px-3 py-2 text-[11px] leading-relaxed font-semibold text-espresso/65">
                    Setelah password diganti, sesi ini tetap aktif. Perangkat lain yang masih login tidak otomatis keluar.
                </p>
            </div>

            <div class="rounded-2xl border border-daun/25 bg-daun/10 p-5">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-daun">Kebiasaan aman</p>
                <ul class="mt-2 space-y-2 text-[11px] leading-relaxed text-espresso/65">
                    <li>• Jangan pakai password demo <span class="font-bold">ayamjago123</span> di website yang sudah online.</li>
                    <li>• Gunakan kombinasi huruf, angka, dan simbol yang hanya kamu tahu.</li>
                    <li>• Ganti password lagi kalau ada orang lain yang pernah memegang akses admin.</li>
                    <li>• Jangan simpan password di chat atau catatan publik.</li>
                </ul>
            </div>

            <a href="{{ route('admin.settings.edit') }}" class="block rounded-xl border-2 border-espresso/10 bg-[#fffaf0]/70 px-4 py-3 text-xs font-bold text-espresso/70 hover:border-jago hover:text-jago">
                Kembali ke Pengaturan Toko →
            </a>
        </aside>
    </div>
@endsection
