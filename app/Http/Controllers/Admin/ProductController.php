<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $products = Product::query()
            ->with('category')
            ->withCount('variants')
            ->when($search !== '', fn ($query) => $query->where(
                fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%")
            ))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(['is_active' => true, 'type' => 'Robusta', 'roast_level' => 'Medium']),
            'categories' => Category::query()->orderBy('name')->get(),
            'defaultVariants' => collect([
                ['weight_gram' => 100, 'price' => 25000, 'stock' => 20],
                ['weight_gram' => 250, 'price' => 55000, 'stock' => 20],
                ['weight_gram' => 500, 'price' => 100000, 'stock' => 10],
                ['weight_gram' => 1000, 'price' => 190000, 'stock' => 5],
            ]),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request): Product {
            $data = $this->productData($request);
            $data['image'] = $this->uploadImage($request);

            $product = Product::create($data);
            $this->syncVariants($product, $request->input('variants', []));

            return $product;
        });

        Cache::forget('catalog.origins');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load('variants');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'defaultVariants' => $product->variants,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product): void {
            $data = $this->productData($request);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $this->uploadImage($request);
            }

            $product->update($data);
            $this->syncVariants($product, $request->input('variants', []));
        });

        Cache::forget('catalog.origins');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        Cache::forget('catalog.origins');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "'.$product->name.'" sudah dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function productData(StoreProductRequest $request): array
    {
        $validated = $request->validated();
        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        return [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($slug, $request->route('product')),
            'origin' => $validated['origin'],
            'type' => $validated['type'],
            'roast_level' => $validated['roast_level'],
            'description' => $validated['description'],
            'flavor_notes' => $validated['flavor_notes'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function uniqueSlug(string $slug, ?Product $ignore = null): string
    {
        $base = Str::slug($slug) ?: 'biji-kopi';
        $candidate = $base;
        $counter = 2;

        while (Product::query()->where('slug', $candidate)->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))->exists()) {
            $candidate = $base.'-'.$counter++;
        }

        return $candidate;
    }

    private function uploadImage(StoreProductRequest $request): ?string
    {
        return $request->file('image')?->store('products', 'public');
    }

    /**
     * Sinkronkan varian berat: perbarui yang ada, tambah yang baru, hapus sisanya.
     *
     * @param  array<int, array{id?: int|null, weight_gram: int, price: int, stock: int}>  $rows
     */
    private function syncVariants(Product $product, array $rows): void
    {
        $keptIds = [];

        foreach ($rows as $row) {
            $variant = isset($row['id']) && $row['id']
                ? $product->variants()->find($row['id'])
                : new ProductVariant(['product_id' => $product->id]);

            if (! $variant) {
                continue;
            }

            $variant->fill([
                'weight_gram' => (int) $row['weight_gram'],
                'price' => (int) $row['price'],
                'stock' => (int) $row['stock'],
            ])->save();

            $keptIds[] = $variant->id;
        }

        $product->variants()->whereNotIn('id', $keptIds)->delete();
    }
}
