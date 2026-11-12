<?php

namespace App;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja berbasis session.
 *
 * Setiap baris disimpan dengan kunci "{variant_id}|{grind_type}" agar satu
 * varian berat dengan gilingan berbeda dihitung sebagai baris terpisah.
 */
final class Cart
{
    public const SESSION_KEY = 'cart';

    /**
     * @return array<string, array{qty: int, grind: string}>
     */
    public static function raw(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    /**
     * Baris keranjang yang sudah dihidrasi dengan data produk terbaru.
     *
     * @return Collection<string, array{variant: ProductVariant, product: Product, grind: string, qty: int, subtotal: int}>
     */
    public static function items(): Collection
    {
        $raw = self::raw();

        if ($raw === []) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with('product')
            ->whereIn('id', array_map(fn (string $key): int => (int) explode('|', $key)[0], array_keys($raw)))
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->map(function (array $line, string $key) use ($variants): ?array {
                $variant = $variants->get((int) explode('|', $key)[0]);

                if (! $variant || ! $variant->product?->is_active) {
                    return null;
                }

                $grind = array_key_exists($line['grind'] ?? '', OrderItem::GRIND_TYPES) ? $line['grind'] : 'whole';
                $qty = max(1, (int) $line['qty']);

                return [
                    'variant' => $variant,
                    'product' => $variant->product,
                    'grind' => $grind,
                    'qty' => $qty,
                    'subtotal' => $variant->price * $qty,
                ];
            })
            ->filter();
    }

    public static function count(): int
    {
        return array_sum(array_map(fn (array $line): int => (int) $line['qty'], self::raw()));
    }

    public static function subtotal(): int
    {
        return self::items()->sum('subtotal');
    }

    public static function add(ProductVariant $variant, string $grind, int $qty): void
    {
        $key = $variant->id.'|'.$grind;
        $cart = self::raw();
        $current = (int) ($cart[$key]['qty'] ?? 0);

        $cart[$key] = ['qty' => min($current + $qty, max(1, $variant->stock)), 'grind' => $grind];
        session()->put(self::SESSION_KEY, $cart);
    }

    public static function update(ProductVariant $variant, string $grind, int $qty, ?string $originalGrind = null): void
    {
        $cart = self::raw();
        $oldKey = $variant->id.'|'.($originalGrind ?? $grind);
        $newKey = $variant->id.'|'.$grind;

        if (! isset($cart[$oldKey])) {
            return;
        }

        $quantity = $qty;

        if ($oldKey !== $newKey) {
            $quantity += (int) ($cart[$newKey]['qty'] ?? 0);
            unset($cart[$newKey]);
        }

        unset($cart[$oldKey]);

        $cart[$newKey] = [
            'qty' => min($quantity, max(1, $variant->stock)),
            'grind' => $grind,
        ];

        session()->put(self::SESSION_KEY, $cart);
    }

    public static function remove(string $key): void
    {
        $cart = self::raw();
        unset($cart[$key]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function lineKey(ProductVariant $variant, string $grind): string
    {
        return $variant->id.'|'.$grind;
    }
}
