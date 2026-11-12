<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index(ProductFilterRequest $request): View
    {
        $filters = $request->validated();

        $products = Product::query()
            ->active()
            ->with(['category', 'variants'])
            ->when($filters['q'] ?? null, function (Builder $query, string $term): void {
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('origin', 'like', "%{$term}%")
                        ->orWhere('type', 'like', "%{$term}%");
                });
            })
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->when($filters['origin'] ?? null, fn (Builder $query, string $origin) => $query->where('origin', $origin))
            ->when($filters['roast'] ?? null, fn (Builder $query, string $roast) => $query->where('roast_level', $roast))
            ->when($filters['min_price'] ?? null, fn (Builder $query, int $price) => $query->whereHas(
                'variants',
                fn (Builder $variants) => $variants->where('price', '>=', $price)
            ))
            ->when($filters['max_price'] ?? null, fn (Builder $query, int $price) => $query->whereHas(
                'variants',
                fn (Builder $variants) => $variants->where('price', '<=', $price)
            ))
            ->when(($filters['sort'] ?? 'featured') === 'cheapest', fn (Builder $query) => $query->orderBy(
                ProductVariant::select('price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('price')
                    ->limit(1)
            ))
            ->when(($filters['sort'] ?? 'featured') === 'expensive', fn (Builder $query) => $query->orderByDesc(
                ProductVariant::select('price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('price')
                    ->limit(1)
            ))
            ->when(($filters['sort'] ?? 'featured') === 'featured', fn (Builder $query) => $query->orderByDesc('is_featured')->latest())
            ->when(($filters['sort'] ?? 'featured') === 'newest', fn (Builder $query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        // Simpan array string (bukan objek) agar aman dengan serializable_classes = false.
        $origins = Cache::remember('catalog.origins', 3600, fn (): array => Product::query()
            ->active()
            ->distinct()
            ->orderBy('origin')
            ->pluck('origin')
            ->all()
        );

        return view('catalog.index', [
            'products' => $products,
            'origins' => $origins,
            'filters' => $filters,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'variants']);

        $related = Product::query()
            ->active()
            ->whereKeyNot($product->id)
            ->where('type', $product->type)
            ->with(['category', 'variants'])
            ->take(3)
            ->get();

        return view('catalog.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
