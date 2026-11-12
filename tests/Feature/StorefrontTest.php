<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_tampil_dengan_hero_dan_produk_unggulan(): void
    {
        $product = Product::factory()->create([
            'name' => 'Arabica Toraja',
            'type' => 'Arabika',
            'is_featured' => true,
        ]);

        ProductVariant::factory()->for($product)->create([
            'weight_gram' => 100,
            'price' => 25_000,
            'stock' => 10,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Kopi Serius', false)
            ->assertSee('Harga Jujur', false)
            ->assertSee('Arabica Toraja');
    }

    public function test_katalog_bisa_disaring_berdasarkan_jenis(): void
    {
        $arabika = Product::factory()->create(['name' => 'Arabica Toraja', 'type' => 'Arabika']);
        $robusta = Product::factory()->create(['name' => 'Robusta Lampung', 'type' => 'Robusta']);

        ProductVariant::factory()->for($arabika)->create(['weight_gram' => 100, 'price' => 25_000]);
        ProductVariant::factory()->for($robusta)->create(['weight_gram' => 100, 'price' => 13_000]);

        $this->get('/katalog?type=Arabika')
            ->assertOk()
            ->assertSee('Arabica Toraja')
            ->assertDontSee('Robusta Lampung');
    }

    public function test_halaman_detail_produk_tampil(): void
    {
        $product = Product::factory()->create(['name' => 'Robusta Sidikalang']);
        ProductVariant::factory()->for($product)->create(['weight_gram' => 100, 'price' => 16_000, 'stock' => 5]);

        $this->get('/katalog/'.$product->slug)
            ->assertOk()
            ->assertSee('Robusta Sidikalang')
            ->assertSee('Gilingan');
    }

    public function test_checkout_membuat_pesanan_dan_mengosongkan_keranjang(): void
    {
        $product = Product::factory()->create(['name' => 'Robusta Lampung']);
        $variant = ProductVariant::factory()->for($product)->create([
            'weight_gram' => 250,
            'price' => 28_000,
            'stock' => 10,
        ]);

        $response = $this->withSession([
            'cart' => [$variant->id.'|whole' => ['qty' => 2, 'grind' => 'whole']],
        ])->post('/checkout', [
            'customer_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1, Jakarta Selatan',
            'payment_method' => 'whatsapp',
            'notes' => 'Titip di pos keamanan',
            'agree' => '1',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Santoso',
            'total' => 56_000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Robusta Lampung',
            'grind_type' => 'whole',
            'qty' => 2,
        ]);

        $this->assertSame(8, $variant->fresh()->stock);
        $this->assertNull(session('cart'));
    }

    public function test_checkout_menolak_data_kosong(): void
    {
        $this->from('/checkout')->post('/checkout', [])
            ->assertRedirect('/checkout')
            ->assertSessionHasErrors(['customer_name', 'phone', 'address', 'payment_method', 'agree']);
    }

    public function test_keranjang_bisa_mengganti_gilingan_tanpa_kehilangan_baris(): void
    {
        $product = Product::factory()->create(['name' => 'Robusta Temanggung']);
        $variant = ProductVariant::factory()->for($product)->create([
            'weight_gram' => 250,
            'price' => 27_000,
            'stock' => 10,
        ]);

        $this->withSession([
            'cart' => [$variant->id.'|whole' => ['qty' => 2, 'grind' => 'whole']],
        ])->patch('/keranjang/'.$variant->id, [
            'grind' => 'fine',
            'qty' => 3,
            'original_grind' => 'whole',
        ])->assertRedirect();

        $cart = session('cart');

        $this->assertArrayNotHasKey($variant->id.'|whole', $cart);
        $this->assertSame(['qty' => 3, 'grind' => 'fine'], $cart[$variant->id.'|fine']);
    }

    public function test_admin_wajib_login_dan_hanya_untuk_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));

        $customer = User::factory()->create();
        $this->actingAs($customer)->get('/admin')->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_admin_bisa_menambah_produk_beserta_variannya(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Robusta Uji',
            'origin' => 'Bengkulu',
            'type' => 'Robusta',
            'roast_level' => 'Medium',
            'description' => 'Produk uji dengan deskripsi yang cukup panjang supaya lolos validasi form.',
            'flavor_notes' => [
                'bitterness' => 4,
                'acidity' => 1,
                'aroma' => 3,
                'body' => 4,
                'notes' => 'Kacang sangrai',
            ],
            'is_active' => '1',
            'variants' => [
                ['weight_gram' => 100, 'price' => 15_000, 'stock' => 10],
                ['weight_gram' => 250, 'price' => 34_000, 'stock' => 5],
            ],
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Robusta Uji',
            'slug' => 'robusta-uji',
        ]);
        $this->assertDatabaseCount('product_variants', 2);
    }

    public function test_admin_bisa_mengunggah_foto_produk(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Arabika Uji Foto',
            'origin' => 'Toraja',
            'type' => 'Arabika',
            'roast_level' => 'Medium',
            'description' => 'Produk uji unggah foto dengan deskripsi yang cukup panjang untuk validasi.',
            'flavor_notes' => [
                'bitterness' => 2,
                'acidity' => 4,
                'aroma' => 5,
                'body' => 3,
                'notes' => 'Jeruk dan gula aren',
            ],
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('foto-produk.png', 800, 800),
            'variants' => [
                ['weight_gram' => 100, 'price' => 30_000, 'stock' => 8],
            ],
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'arabika-uji-foto')->firstOrFail();

        $this->assertNotNull($product->image);
        $this->assertStringStartsWith('products/', $product->image);
        $this->assertSame(route('media', ['path' => $product->image]), $product->image_url);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_foto_produk_disajikan_lewat_route_media(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/contoh.png', 'isi-gambar');

        $this->get(route('media', ['path' => 'products/contoh.png']))->assertOk();
        $this->get(route('media', ['path' => 'products/tidak-ada.png']))->assertNotFound();
    }

    public function test_foto_produk_lebih_dari_2mb_ditolak(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->from('/admin/products/create')
            ->post('/admin/products', [
                'category_id' => $category->id,
                'name' => 'Robusta Foto Kegedean',
                'origin' => 'Lampung',
                'type' => 'Robusta',
                'roast_level' => 'Dark',
                'description' => 'Produk uji dengan foto yang ukurannya melebihi batas dua megabita.',
                'flavor_notes' => [
                    'bitterness' => 5,
                    'acidity' => 1,
                    'aroma' => 3,
                    'body' => 5,
                ],
                'is_active' => '1',
                'image' => UploadedFile::fake()->image('foto-besar.png', 1200, 1200)->size(3000),
                'variants' => [
                    ['weight_gram' => 100, 'price' => 20_000, 'stock' => 5],
                ],
            ])
            ->assertRedirect('/admin/products/create')
            ->assertSessionHasErrors(['image' => 'Ukuran foto maksimal 2 MB. Kompres dulu fotonya, ya.']);

        $this->assertDatabaseMissing('products', [
            'name' => 'Robusta Foto Kegedean',
        ]);
    }
}
