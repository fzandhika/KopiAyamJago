<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Masuk Panel Admin — Kopi Ayam Jago</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Caveat+Brush&family=Nunito+Sans:opsz,wght@6..12,400;6..12,700;6..12,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="paper-dark min-h-screen">
    <div class="mx-auto grid min-h-screen max-w-5xl items-center gap-10 px-4 py-10 lg:grid-cols-2">
        <div class="hidden lg:block">
            <p class="font-marker text-lg text-stiker">Panel Admin</p>
            <h1 class="mt-2 text-4xl leading-none text-karung">Jaga toples, jaga harga.</h1>
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-karung/65">
                Dari sini kamu bisa menambah biji baru, mengubah harga per gramasi, dan memproses pesanan
                yang masuk dari website.
            </p>
            <blockquote class="mt-8 border-l-4 border-stiker pl-4 font-marker text-xl text-stiker">
                “Gue nggak pernah bercanda soal KOPI!”
            </blockquote>
        </div>

        <div class="rounded-2xl border-2 border-sangrai bg-karung p-6 shadow-[8px_8px_0_0_#B3201F] sm:p-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-espresso/50 hover:text-jago">
                <span aria-hidden="true">←</span> Kembali ke toko
            </a>

            <h2 class="mt-4 font-display text-2xl text-espresso">Masuk sebagai admin</h2>
            <p class="mt-1 text-xs font-semibold text-espresso/55">Gunakan akun yang sudah terdaftar sebagai admin toko.</p>

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="label-jago">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@kopiayamjago.test" class="input-jago @error('email') border-jago @enderror">
                    @error('email') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label-jago">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="input-jago @error('password') border-jago @enderror">
                    @error('password') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-xs font-semibold text-espresso/65">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-2 border-espresso/30 text-jago focus:ring-emas">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="btn btn-jago w-full py-3">Masuk Panel</button>
            </form>

            <!-- <p class="mt-5 rounded-lg bg-stiker/20 px-3 py-2 text-[11px] leading-relaxed font-semibold text-espresso/65">
                Akun demo dari seeder: <span class="font-extrabold text-espresso">admin@kopiayamjago.test</span> /
                <span class="font-extrabold text-espresso">ayamjago123</span>. Segera ganti setelah dipakai.
            </p> -->
        </div>
    </div>
</body>
</html>
