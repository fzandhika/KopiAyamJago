<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi data awal toko: kategori, 10 biji kopi, varian berat, testimoni,
     * pengaturan toko, dan satu akun admin.
     *
     * Seeder aman dijalankan ulang: produk & kategori memakai updateOrCreate,
     * pengaturan tidak menimpa nilai yang sudah diubah admin.
     */
    public function run(): void
    {
        $categories = $this->seedCategories();
        $this->seedProducts($categories);
        $this->seedTestimonials();
        $this->seedSettings();
        $this->seedAdmin();
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $rows = [
            ['name' => 'Robusta', 'slug' => 'robusta', 'description' => 'Body tebal, pahit tegas, harga bersahabat. Andalan untuk kopi susu dan tubruk.', 'sort_order' => 1],
            ['name' => 'Arabika', 'slug' => 'arabika', 'description' => 'Keasaman cerah dan aroma kompleks. Enak untuk manual brew dan pour over.', 'sort_order' => 2],
            ['name' => 'Luwak', 'slug' => 'luwak', 'description' => 'Fermentasi alami, stok terbatas. Untuk yang mau coba pengalaman rasa berbeda.', 'sort_order' => 3],
        ];

        return collect($rows)->mapWithKeys(function (array $row): array {
            $category = Category::updateOrCreate(['slug' => $row['slug']], $row);

            return [$row['slug'] => $category];
        })->all();
    }

    /**
     * @param  array<string, Category>  $categories
     */
    private function seedProducts(array $categories): void
    {
        $products = [
            [
                'slug' => 'loewak-ternak',
                'category' => 'luwak',
                'name' => 'Loewak Ternak',
                'origin' => 'Sumatra',
                'type' => 'Luwak',
                'roast_level' => 'Medium-Dark',
                'description' => 'Biji luwak hasil ternak, difermentasi alami lalu disangrai medium-dark. Rasanya pekat dan manis, bukan sekadar mahal. Stok sedikit karena produksinya memang tidak bisa dikejar.',
                'flavor_notes' => ['bitterness' => 2, 'acidity' => 2, 'aroma' => 5, 'body' => 4, 'notes' => 'Cokelat pekat, gula aren, aftertaste panjang'],
                'is_featured' => true,
                'variants' => [
                    [100, 110_000, 4],
                    [250, 265_000, 3],
                    [500, 520_000, 2],
                    [1000, 1_000_000, 1],
                ],
            ],
            [
                'slug' => 'arabica-mandheling',
                'category' => 'arabika',
                'name' => 'Arabica Mandheling',
                'origin' => 'Mandailing, Sumatra Utara',
                'type' => 'Arabika',
                'roast_level' => 'Dark',
                'description' => 'Arabika klasik Sumatra dengan body berat dan kesan earthy yang kuat. Disangrai dark supaya rempahnya keluar. Cocok untuk yang suka kopi tanpa asam menusuk.',
                'flavor_notes' => ['bitterness' => 4, 'acidity' => 2, 'aroma' => 5, 'body' => 5, 'notes' => 'Rempah, kakao, tanah basah'],
                'is_featured' => false,
                'variants' => [
                    [100, 28_000, 24],
                    [250, 65_000, 18],
                    [500, 125_000, 12],
                    [1000, 220_000, 6],
                ],
            ],
            [
                'slug' => 'arabica-toraja',
                'category' => 'arabika',
                'name' => 'Arabica Toraja',
                'origin' => 'Toraja, Sulawesi Selatan',
                'type' => 'Arabika',
                'roast_level' => 'Medium',
                'description' => 'Biji arabika Toraja dengan keasaman yang hidup dan aroma bunga. Disangrai medium supaya karakter buahnya tidak hilang. Salah satu yang paling sering kami rekomendasikan untuk V60.',
                'flavor_notes' => ['bitterness' => 2, 'acidity' => 3, 'aroma' => 4, 'body' => 3, 'notes' => 'Jeruk, gula merah, bunga'],
                'is_featured' => true,
                'variants' => [
                    [100, 25_000, 24],
                    [250, 59_000, 18],
                    [500, 112_000, 12],
                    [1000, 200_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-sidikalang',
                'category' => 'robusta',
                'name' => 'Robusta Sidikalang',
                'origin' => 'Sidikalang, Sumatra Utara',
                'type' => 'Robusta',
                'roast_level' => 'Medium-Dark',
                'description' => 'Robusta Sidikalang dengan pahit yang bersih dan body tebal. Ini biang kopi susu yang bikin melek. Kalau diseduh tubruk, aromanya langsung penuh di ruangan.',
                'flavor_notes' => ['bitterness' => 4, 'acidity' => 1, 'aroma' => 4, 'body' => 4, 'notes' => 'Kacang sangrai, kakao, pahit bersih'],
                'is_featured' => true,
                'variants' => [
                    [100, 16_000, 24],
                    [250, 37_000, 18],
                    [500, 69_000, 12],
                    [1000, 120_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-toraja',
                'category' => 'robusta',
                'name' => 'Robusta Toraja',
                'origin' => 'Toraja, Sulawesi Selatan',
                'type' => 'Robusta',
                'roast_level' => 'Medium-Dark',
                'description' => 'Robusta Toraja yang lebih lembut dibanding Sidikalang, dengan sentuhan cokelat hitam. Enak untuk espresso rumah atau campuran kopi susu biar nggak terlalu pahit.',
                'flavor_notes' => ['bitterness' => 4, 'acidity' => 1, 'aroma' => 3, 'body' => 4, 'notes' => 'Cokelat hitam, tembakau'],
                'is_featured' => false,
                'variants' => [
                    [100, 15_000, 24],
                    [250, 34_000, 18],
                    [500, 63_000, 12],
                    [1000, 110_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-lampung',
                'category' => 'robusta',
                'name' => 'Robusta Lampung',
                'origin' => 'Lampung',
                'type' => 'Robusta',
                'roast_level' => 'Medium-Dark',
                'description' => 'Robusta Lampung yang jujur: pahit, tebal, dan murah. Bukan kopi untuk pamer, tapi kopi yang bikin pagi jalan. Banyak warung langganan kami pakai ini tiap hari.',
                'flavor_notes' => ['bitterness' => 4, 'acidity' => 1, 'aroma' => 3, 'body' => 4, 'notes' => 'Kacang, rempah, body tebal'],
                'is_featured' => false,
                'variants' => [
                    [100, 13_000, 24],
                    [250, 28_000, 18],
                    [500, 52_000, 12],
                    [1000, 90_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-temanggung',
                'category' => 'robusta',
                'name' => 'Robusta Temanggung',
                'origin' => 'Temanggung, Jawa Tengah',
                'type' => 'Robusta',
                'roast_level' => 'Medium',
                'description' => 'Robusta Temanggung dengan manis tembakau yang khas dan keasaman tipis. Disangrai medium supaya nggak terlalu gosong. Salah satu favorit pelanggan yang ngopi tanpa gula.',
                'flavor_notes' => ['bitterness' => 3, 'acidity' => 2, 'aroma' => 3, 'body' => 4, 'notes' => 'Tembakau manis, kayu manis'],
                'is_featured' => true,
                'variants' => [
                    [100, 12_500, 24],
                    [250, 27_000, 18],
                    [500, 49_000, 12],
                    [1000, 85_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-bengkulu',
                'category' => 'robusta',
                'name' => 'Robusta Bengkulu',
                'origin' => 'Bengkulu',
                'type' => 'Robusta',
                'roast_level' => 'Medium-Dark',
                'description' => 'Robusta Bengkulu dengan kesan earthy dan pahit yang tegas. Kalau kamu suka kopi tubruk kental, ini pilihan yang tepat. Nggak ada rasa asam yang mengganggu.',
                'flavor_notes' => ['bitterness' => 4, 'acidity' => 1, 'aroma' => 3, 'body' => 4, 'notes' => 'Rempah, tanah basah, pahit tegas'],
                'is_featured' => false,
                'variants' => [
                    [100, 13_000, 24],
                    [250, 28_000, 18],
                    [500, 52_000, 12],
                    [1000, 90_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-dampit-malang',
                'category' => 'robusta',
                'name' => 'Robusta Dampit Malang',
                'origin' => 'Dampit, Malang',
                'type' => 'Robusta',
                'roast_level' => 'Medium',
                'description' => 'Robusta Dampit dengan manis gula aren dan sedikit buah di ujung rasa. Disangrai medium, jadi lebih ramah di lambung. Enak untuk kopi susu gula aren rumahan.',
                'flavor_notes' => ['bitterness' => 3, 'acidity' => 2, 'aroma' => 3, 'body' => 4, 'notes' => 'Gula aren, kacang, sedikit buah'],
                'is_featured' => false,
                'variants' => [
                    [100, 13_500, 24],
                    [250, 29_500, 18],
                    [500, 55_000, 12],
                    [1000, 95_000, 6],
                ],
            ],
            [
                'slug' => 'robusta-bali',
                'category' => 'robusta',
                'name' => 'Robusta Bali',
                'origin' => 'Kintamani, Bali',
                'type' => 'Robusta',
                'roast_level' => 'Medium',
                'description' => 'Robusta Bali dengan aroma lebih segar dan sentuhan jeruk nipis. Bukan robusta biasa yang cuma pahit. Cocok buat yang mau coba robusta dengan karakter lebih ringan.',
                'flavor_notes' => ['bitterness' => 3, 'acidity' => 2, 'aroma' => 4, 'body' => 3, 'notes' => 'Jeruk nipis, cokelat, aroma segar'],
                'is_featured' => false,
                'variants' => [
                    [100, 14_000, 24],
                    [250, 31_000, 18],
                    [500, 58_000, 12],
                    [1000, 100_000, 6],
                ],
            ],
        ];

        foreach ($products as $row) {
            $variants = $row['variants'];
            $categorySlug = $row['category'];

            unset($row['variants'], $row['category']);

            $product = Product::updateOrCreate(
                ['slug' => $row['slug']],
                [
                    ...$row,
                    'category_id' => $categories[$categorySlug]->id,
                    'is_active' => true,
                ]
            );

            foreach ($variants as [$weight, $price, $stock]) {
                ProductVariant::updateOrCreate(
                    ['product_id' => $product->id, 'weight_gram' => $weight],
                    ['price' => $price, 'stock' => $stock]
                );
            }
        }
    }

    private function seedTestimonials(): void
    {
        $rows = [
            ['name' => 'Bang Dedi', 'city' => 'Kebayoran Lama', 'rating' => 5, 'quote' => 'Robusta Sidikalang-nya nendang tapi nggak bikin sakit perut. Sudah tiga kali beli, selalu dikirim hari itu juga.'],
            ['name' => 'Mbak Sari', 'city' => 'Cilandak', 'rating' => 5, 'quote' => 'Awalnya cuma coba Arabica Toraja 100 gram. Sekarang tiap bulan ambil satu kilo. Dijelaskan cara seduhnya juga, bukan cuma jualan.'],
            ['name' => 'Pak Anton', 'city' => 'Pesanggrahan', 'rating' => 4, 'quote' => 'Harga di toples jelas, timbangannya di depan. Datang sore bisa ngobrol dulu soal biji sebelum beli. Warung langganan yang jujur.'],
        ];

        foreach ($rows as $row) {
            Testimonial::updateOrCreate(
                ['name' => $row['name'], 'quote' => $row['quote']],
                [...$row, 'is_active' => true]
            );
        }
    }

    private function seedSettings(): void
    {
        foreach (Setting::DEFAULTS as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('settings.all');
    }

    private function seedAdmin(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@kopiayamjago.test'],
            [
                'name' => 'Admin Ayam Jago',
                'password' => Hash::make('ayamjago123'),
                'is_admin' => true,
            ]
        );
    }
}
