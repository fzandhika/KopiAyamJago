# Kopi Ayam Jago

Website penjualan biji kopi untuk warung **Kopi Ayam Jago** — warung kopi kampung Jakarta berjiwa Pecinan. Dibangun dengan Laravel, Blade, dan Tailwind CSS, tanpa framework JavaScript tambahan.

> “Gue nggak pernah bercanda soal KOPI!”

---

## Daftar Isi

- [Tentang](#tentang)
- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Akun Admin](#akun-admin)
- [Rute Utama](#rute-utama)
- [Struktur Folder](#struktur-folder)
- [Design System](#design-system)
- [Pengujian](#pengujian)
- [Build & Deploy](#build--deploy)
- [Data Dummy](#data-dummy)
- [Keamanan](#keamanan)

---

## Tentang

Website ini menampilkan katalog biji kopi Robusta, Arabika, dan Luwak dari berbagai daerah — Sidikalang, Toraja, Mandheling, Lampung, Temanggung, Bengkulu, Dampit Malang, Bali, dan Luwak Ternak.

Pembeli dapat melihat profil rasa, memilih berat (100 g–1 kg) dan tingkat gilingan, lalu menyelesaikan pesanan melalui checkout yang dikonfirmasi lewat WhatsApp. Pemilik toko mengelola produk, stok, pesanan, dan informasi toko melalui panel admin.

---

## Fitur

### Storefront

- **Beranda** — hero, kategori, produk unggulan, cerita toko, testimoni.
- **Katalog** — pencarian, filter jenis/asal/sangrai/rentang harga, pengurutan, pagination.
- **Detail produk** — foto atau ilustrasi toples, profil rasa (pahit, asam, aroma, body), pilihan berat & gilingan, harga dinamis.
- **Keranjang** — berbasis session, ubah jumlah/gilingan, validasi stok.
- **Checkout** — data penerima, pilihan pembayaran (WhatsApp, transfer, QRIS), ringkasan pesanan.
- **Halaman sukses** — kode pesanan, rincian, tombol konfirmasi WhatsApp.
- **Cerita Kami** dan **Kontak & Lokasi** — Google Maps, jam buka, alamat.
- **Mobile-first**, tombol WhatsApp melayang, animasi ringan (`prefers-reduced-motion` didukung).

### Panel Admin (`/admin`)

- Dashboard statistik: produk, stok menipis, pesanan menunggu, pendapatan.
- CRUD produk + varian berat/harga/stok, upload gambar, profil rasa.
- CRUD kategori dengan proteksi kategori yang masih dipakai.
- Daftar & detail pesanan, ubah status, tombol chat WhatsApp pembeli.
- Pengaturan toko: nama, nomor WA, alamat, jam buka, Maps, Instagram, rekening, QRIS, catatan ongkir.

### Teknis

- Keranjang session dengan harga yang selalu dibaca ulang dari database saat checkout.
- Stok dikurangi secara transaksional (`lockForUpdate`).
- Validasi melalui Form Request, CSRF di semua form, rate limiting login & checkout.
- SEO: meta title/description, canonical, Open Graph, JSON-LD Product/LocalBusiness/Store, `sitemap.xml`, `robots.txt`.
- Upload gambar divalidasi (JPG/PNG/WebP, maks. 2 MB) dan disimpan di disk publik.

---

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Tampilan | Blade, Tailwind CSS v4 |
| Interaksi | Vanilla JavaScript |
| Build | Vite 8 |
| Database | SQLite (default), MySQL didukung |
| Testing | PHPUnit |

---

## Kebutuhan Sistem

- PHP **8.3** atau lebih baru
- Composer
- Node.js **20+** dan npm
- SQLite (bawaan PHP) atau MySQL

---

## Instalasi

```bash
# 1. Dependensi PHP
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database + data contoh
php artisan migrate --seed

# 4. Dependensi frontend
npm install
npm run build

# 5. Link folder upload
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk development dengan hot reload:

```bash
npm run dev
```

Di Windows PowerShell, jika `php` atau `composer` tidak dikenali, tambahkan folder PHP ke PATH terlebih dahulu atau gunakan `php.exe` dari XAMPP/FlyEnv.

---

## Konfigurasi Database

### SQLite (default)

Tidak perlu konfigurasi tambahan. Pastikan file `database/database.sqlite` ada:

```bash
type nul > database/database.sqlite   # Windows
touch database/database.sqlite        # Linux/macOS
php artisan migrate --seed
```

### MySQL

Ubah `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kopi_ayam_jago
DB_USERNAME=root
DB_PASSWORD=
```

Lalu:

```bash
php artisan migrate:fresh --seed
```

---

## Akun Admin

Login di `/admin/login`.

| Field | Nilai |
|---|---|
| Email | `admin@kopiayamjago.test` |
| Password | `ayamjago123` |

Akun ini dibuat oleh seeder dan memiliki `is_admin = true`. **Ganti password setelah pertama kali dipakai** melalui menu **Keamanan** di panel admin (`/admin/password`).

Hanya user dengan `is_admin = true` yang dapat mengakses panel. User biasa akan mendapat respons 403.

---

## Rute Utama

| Method | URL | Keterangan |
|---|---|---|
| GET | `/` | Beranda |
| GET | `/katalog` | Katalog + filter |
| GET | `/katalog/{slug}` | Detail produk |
| GET | `/keranjang` | Keranjang |
| POST | `/keranjang/{variant}` | Tambah ke keranjang |
| PATCH | `/keranjang/{variant}` | Ubah jumlah/gilingan |
| DELETE | `/keranjang/{variant}` | Hapus item |
| GET | `/checkout` | Form checkout |
| POST | `/checkout` | Simpan pesanan |
| GET | `/pesanan/{kode}` | Halaman sukses |
| GET | `/cerita-kami` | Cerita toko |
| GET | `/kontak` | Kontak & lokasi |
| GET | `/sitemap.xml` | Sitemap |
| GET | `/robots.txt` | Robots |
| GET | `/admin/login` | Login admin |
| GET | `/admin` | Dashboard admin |

Daftar lengkap: `php artisan route:list`.

---

## Struktur Folder

```
app/
├── Cart.php                       # Keranjang berbasis session
├── Http/
│   ├── Controllers/               # Controller storefront
│   ├── Controllers/Admin/         # Controller panel admin
│   ├── Middleware/EnsureUserIsAdmin.php
│   └── Requests/                  # Validasi form
└── Models/                        # Category, Product, Variant, Order, dll.

database/
├── migrations/
├── seeders/DatabaseSeeder.php     # Produk, kategori, testimoni, settings, admin
└── factories/

resources/
├── css/app.css                    # Design system Tailwind v4
├── js/app.js                      # Interaksi UI
└── views/
    ├── layouts/                   # Layout toko & admin
    ├── components/                # product-jar, product-card, taste-bar
    ├── home.blade.php
    ├── catalog/
    ├── cart/
    ├── checkout/
    ├── pages/
    ├── admin/
    └── sitemap.blade.php

routes/web.php
```

---

## Design System

Warna dan tipografi didefinisikan di `resources/css/app.css` menggunakan `@theme` Tailwind v4.

| Token | Nilai | Penggunaan |
|---|---|---|
| `jago` | `#B3201F` | Merah utama, CTA |
| `sangrai` | `#1E1410` | Hitam sangrai, hero |
| `karung` | `#F4E9D3` | Krem latar |
| `emas` | `#C8962E` | Border rak |
| `stiker` | `#F6B820` | Label harga |
| `harga` | `#E8531F` | Aksen harga |
| `daun` | `#4F7A3A` | Hijau daun |
| `espresso` | `#3B2418` | Teks utama |

Font: **Alfa Slab One** (heading), **Caveat Brush** (label), **Nunito Sans** (body).

Ilustrasi toples dibuat murni dengan CSS (`.jar-body`, `.jar-lid`, `.jar-coffee`) dan otomatis diganti foto jika admin mengunggah gambar produk.

---

## Pengujian

```bash
php artisan test --compact
```

Mencakup alur beranda, filter katalog, detail produk, checkout (buat pesanan, kurangi stok, kosongkan keranjang), validasi, akses admin, dan pembuatan produk dengan varian.

Format kode PHP:

```bash
vendor/bin/pint --dirty --format agent
```

---

## Build & Deploy

1. Jalankan `npm run build` sebelum upload (menghasilkan `public/build`).
2. Arahkan document root domain ke folder **`public/`**, bukan root project.
3. Jika hosting tidak bisa mengubah document root, pindahkan isi `public/` ke `public_html` dan sesuaikan path di `index.php`.
4. File yang tidak perlu di-upload: `node_modules/`, `.git/`, `tests/`.
5. Di server:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan folder `storage/` dan `bootstrap/cache/` dapat ditulis.

---

## Data Dummy

Data berikut berasal dari seeder dan **wajib diverifikasi** sebelum dipakai jualan:

- Nomor WhatsApp: `0812-3456-7890`
- Alamat: Gang H. Saidi No. 12, Kebayoran Lama, Jakarta Selatan
- Jam buka: `07.00 – 21.00 WIB`
- Rekening: `BCA 1234567890 a.n. Kopi Ayam Jago`
- Harga: asumsi per kg dari foto (Rp 85.000 – Rp 1.000.000), dipecah ke 100 g/250 g/500 g/1 kg

Semua dapat diubah melalui **Admin → Pengaturan** tanpa menyentuh kode. Harga dan stok produk diubah melalui **Admin → Produk**.

---

## Keamanan

- Password di-hash dengan bcrypt.
- CSRF token pada seluruh form.
- Middleware `auth` + `admin` untuk panel.
- Rate limit: login admin 5 percobaan/menit, checkout 10/menit.
- Validasi upload gambar berdasarkan MIME dan ukuran.
- `.env` tidak disertakan dalam repository.

---

Dibuat untuk **Kopi Ayam Jago** — kopi serius, harga jujur.
